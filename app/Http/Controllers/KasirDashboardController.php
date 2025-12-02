<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class KasirDashboardController extends Controller
{
    /**
     * Display the kasir dashboard.
     */
    public function index(): View
    {
        return view('kasir.dashboard');
    }
    public function tickets()
    {
        try {
            // Get branches using stored procedure
            $branches = collect(DB::select('EXEC sp_GetCabangs'));

            // Get today's transactions
            $transaksiToday = collect(DB::select('EXEC sp_ViewTransaksiHeader'))
                ->filter(function ($item) {
                    $waktu = Carbon::parse($item->Waktu);
                    return $waktu->isToday();
                });

            // Get today's transaction details for more detailed stats
            $transaksiDetail = collect(DB::select('EXEC sp_ViewTransaksiDetail ?, ?, ?', [null, null, null]))
                ->filter(function ($item) {
                    $waktu = Carbon::parse($item->Waktu);
                    return $waktu->isToday();
                });

            // Calculate summary statistics
            $tiketTerjual = $transaksiDetail->count(); // Total tickets sold today
            $totalPenjualan = $transaksiToday->sum('Total Bayar'); // Total sales amount today
            $filmBerbeda = $transaksiDetail->pluck('Film')->unique()->count(); // Unique films today

            // Calculate refunds (you might need to adjust this based on your refund logic)
            // Assuming refunds are negative amounts or have a specific status
            $refund = $transaksiToday->where('Total Bayar', '<', 0)->count();
            // Or if you have a refund status field:
            // $refund = $transaksiToday->where('Status', 'Refund')->count();

            $summaryData = [
                'tiket_terjual' => $tiketTerjual,
                'total_penjualan' => $totalPenjualan,
                'film_berbeda' => $filmBerbeda,
                'refund' => $refund,
                'transaksi_hari_ini' => $transaksiToday->count(),
            ];

            // Additional data for tickets operations
            $recentTransactions = $transaksiToday->take(5); // Last 5 transactions today
            $popularFilms = $transaksiDetail->groupBy('Film')
                ->map(function ($group) {
                    return [
                        'film' => $group->first()->Film,
                        'tickets_sold' => $group->count(),
                        'revenue' => $group->sum('Harga Tiket')
                    ];
                })
                ->sortByDesc('tickets_sold')
                ->take(3); // Top 3 popular films today

            return view('kasir.tickets', [
                'summary' => $summaryData,
                'branches' => $branches,
                'recentTransactions' => $recentTransactions,
                'popularFilms' => $popularFilms
            ]);
        } catch (\Exception $e) {
            // Fallback data in case of error
            $summaryData = [
                'tiket_terjual' => 0,
                'total_penjualan' => 0,
                'film_berbeda' => 0,
                'refund' => 0,
                'transaksi_hari_ini' => 0,
            ];

            return view('kasir.tickets', [
                'summary' => $summaryData,
                'branches' => collect([]),
                'recentTransactions' => collect([]),
                'popularFilms' => collect([]),
                'error' => 'Gagal memuat data: ' . $e->getMessage()
            ]);
        }
    }

    public function schedules()
    {
        $jadwals = collect(DB::select('EXEC sp_ViewJadwalLengkap'));
        // dd($jadwals);
        return view('kasir.schedules', ['jadwals' => $jadwals]);
    }

    public function transactions()
    {
        $transaksiheader = collect(DB::select('EXEC sp_ViewTransaksiHeader'));
        // $transaksidetail = collect(DB::select('EXEC sp_ViewTransaksiDetail'));
        // dd($transaksidetail);
        return view('kasir.transactions', ['transaksiheader' => $transaksiheader]);
    }

    public function getTransactionDetail($id)
    {
        try {
            // Get transaction header
            $header = collect(DB::select('EXEC sp_ViewTransaksiHeader'))->where('ID', $id)->first();

            if (!$header) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            // Get transaction details
            $details = collect(DB::select('EXEC sp_ViewTransaksiDetail ?, ?, ?', [null, null, $id]));
            // dd($details);
            return response()->json([
                'success' => true,
                'transaction' => [
                    'header' => $header,
                    'details' => $details->values()->toArray()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function printTransaction($id)
    {
        try {
            $header = collect(DB::select('EXEC sp_ViewTransaksiHeader'))->where('ID', $id)->first();
            $details = collect(DB::select('EXEC sp_ViewTransaksiDetail ?, ?, ?', [null, null, $id]));
            return view('kasir.print-transaction', [
                'header' => $header,
                'details' => $details
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data untuk print');
        }
    }


    public function customers()
    {
        // Call a stored procedure without parameters
        $results = collect(DB::select('EXEC sp_GetPelanggan'));
        $length = $results->count();
        // $results = DB::select('EXEC sp_GetPelanggan ?', ['kasir']);
        // dd($results); // Dump and die to inspect the results
        $pelanggan_bulan_ini = $results->filter(function ($item) {
            $created_at = \Carbon\Carbon::parse($item->Bergabung_Sejak);
            return $created_at->isCurrentMonth();
        })->count();
        return view('kasir.customers', ['customers' => $results, 'length' => $length, 'pelanggan_bulan_ini' => $pelanggan_bulan_ini]);
    }

    /**
     * Create a new ticket transaction using stored procedure
     */
    public function createTicket(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'nullable|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'schedule' => 'required|array',
                'seats' => 'required|array|min:1',
                'payment_method' => 'required|string',
                'ticket_count' => 'required|integer|min:1',
                'total_amount' => 'required|numeric|min:0'
            ]);

            // Extract schedule information
            $schedule = $validatedData['schedule'];
            $jadwalId = $schedule['id_jadwal'];
            $customerName = $validatedData['customer_name'];
            $customerPhone = $validatedData['customer_phone'];
            $customerEmail = $validatedData['customer_email'] ?? '';
            $totalAmount = $validatedData['total_amount'];
            $paymentMethod = $validatedData['payment_method'];
            $seats = $validatedData['seats'];

            // Create or get customer using stored procedure
            $customerId = $this->createOrGetCustomer($customerName, $customerPhone, $customerEmail);

            // Create transaction using stored procedure
            $transactionResult = DB::select('EXEC sp_CreateTransaksi ?, ?, ?, ?, ?', [
                $customerId,
                $jadwalId,
                $totalAmount,
                $paymentMethod,
                now()->format('Y-m-d H:i:s')
            ]);

            if (empty($transactionResult)) {
                throw new \Exception('Gagal membuat transaksi');
            }

            $transactionId = $transactionResult[0]->id_transaksi ?? $transactionResult[0]->id;

            // Create transaction details for each seat using stored procedure
            foreach ($seats as $seat) {
                // Create detail using stored procedure
                $detailResult = DB::select('EXEC sp_CreateDetailTransaksi ?, ?, ?, ?', [
                    $transactionId,
                    $jadwalId,
                    $seat, // seat_code like "A1", "B5"
                    $schedule['harga_film']
                ]);

                if (empty($detailResult)) {
                    Log::warning("Failed to create detail for seat {$seat}");
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'transaction_id' => $transactionId,
                'total_amount' => $validatedData['total_amount'],
                'ticket_count' => $validatedData['ticket_count']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create or get existing customer
     */
    private function createOrGetCustomer($name, $phone, $email = '')
    {
        try {
            // Check if customer exists using stored procedure
            $existingCustomer = DB::select('EXEC sp_GetPelangganByPhone ?', [$phone]);

            if (!empty($existingCustomer)) {
                return $existingCustomer[0]->id_pelanggan ?? $existingCustomer[0]->id;
            }

            // Create new customer using stored procedure
            $customerResult = DB::select('EXEC sp_CreatePelanggan ?, ?, ?', [
                $name,
                $email,
                $phone
            ]);

            if (empty($customerResult)) {
                throw new \Exception('Gagal membuat pelanggan');
            }

            return $customerResult[0]->id_pelanggan ?? $customerResult[0]->id;
        } catch (\Exception $e) {
            // Fallback: create customer manually if stored procedures fail
            try {
                $customerId = DB::table('pelanggans')->insertGetId([
                    'nama' => $name,
                    'email' => $email,
                    'no_telepon' => $phone,
                    'tanggal_daftar' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                return $customerId;
            } catch (\Exception $fallbackError) {
                throw new \Exception('Gagal membuat pelanggan: ' . $e->getMessage());
            }
        }
    }

    public function getBookedSeats($jadwalId, $studioId = null)
    {
        try {
            // Use the same stored procedure but filter for booked seats only
            $seatData = collect(DB::select('EXEC sp_CheckSeatMapTersedia ?', [$jadwalId]));

            // Filter only booked seats
            $bookedSeats = $seatData
                ->where('status_kursi', 'Booked')
                ->map(function ($seat) {
                    return [
                        'seat_id' => $seat->seat_code,
                        'kursi_row' => substr($seat->seat_code, 0, 1), // Extract row letter
                        'kursi_nomor' => substr($seat->seat_code, 1) // Extract seat number
                    ];
                })
                ->values()
                ->toArray();

            return response()->json([
                'success' => true,
                'booked_seats' => $bookedSeats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kursi: ' . $e->getMessage(),
                'booked_seats' => []
            ], 500);
        }
    }

    public function getSchedulesByBranch($branchId)
    {
        try {
            $currentDate = Carbon::now()->format('Y-m-d');
            $schedules = collect(DB::select('EXEC sp_CheckJadwalTersedia ?, ?', [$branchId, $currentDate]));

            return response()->json([
                'success' => true,
                'schedules' => $schedules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil jadwal: ' . $e->getMessage(),
                'schedules' => []
            ], 500);
        }
    }

    public function getSeatMap($id_jadwal)
    {
        try {
            // Use your stored procedure that gets seat map with booking status
            $seatMapData = collect(DB::select('EXEC sp_CheckSeatMapTersedia ?', [$id_jadwal]));

            if ($seatMapData->count() > 0) {
                // Transform the data to match the expected format
                $transformedData = $seatMapData->map(function ($seat) {
                    return [
                        'seat_id' => $seat->seat_code, // Use seat_code (A1, A2, B1, etc.)
                        'row' => $seat->no_baris,
                        'col' => $seat->no_kolom,
                        'status' => strtolower($seat->status_kursi), // 'booked' or 'available'
                    ];
                });

                return response()->json([
                    'success' => true,
                    'seat_map' => $transformedData->toArray()
                ]);
            } else {
                // Return empty seat map if no data
                return response()->json([
                    'success' => true,
                    'seat_map' => [],
                    'message' => 'No seat data found for this schedule'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data seat map: ' . $e->getMessage(),
                'seat_map' => []
            ], 500);
        }
    }
}
