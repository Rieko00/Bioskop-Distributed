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
        // Mulai database transaction di level PHP untuk safety tambahan
        // (opsional, karena SP header sudah insert, tapi berguna jika loop detail gagal)

        try {
            // 1. Validate the request data
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

            // Extract info
            $schedule = $validatedData['schedule'];
            $jadwalId = $schedule['id_jadwal'];
            $customerName = $validatedData['customer_name'];
            $customerPhone = $validatedData['customer_phone'];
            $customerEmail = $validatedData['customer_email'] ?? '';
            $totalAmount = (int) $validatedData['total_amount']; // Cast ke int sesuai SP
            $paymentMethod = $validatedData['payment_method'];
            $seats = $validatedData['seats'];

            // 2. Create or get customer
            $customerId = $this->createOrGetCustomer($customerName, $customerPhone, $customerEmail);

            // 3. Create Transaction Header
            // Params: @id_pelanggan, @id_jadwal, @total_bayar, @metode_pembayaran, @waktu_transaksi
            $transactionResult = DB::select('EXEC sp_CreateTransaksi ?, ?, ?, ?, ?', [
                $customerId,
                $jadwalId,
                $totalAmount,
                $paymentMethod,
                now()->format('Y-m-d H:i:s') // Waktu transaksi
            ]);

            if (empty($transactionResult)) {
                throw new \Exception('Gagal membuat header transaksi.');
            }

            $transactionId = $transactionResult[0]->id_transaksi;

            // 4. Create Transaction Details (Looping Kursi)
            foreach ($seats as $seatCode) {
                // Params: @id_transaksi, @id_jadwal, @seat_code, @harga
                $detailResult = DB::select('EXEC sp_CreateDetailTransaksi ?, ?, ?, ?', [
                    $transactionId,
                    $jadwalId,
                    $seatCode,
                    $schedule['harga_film']
                ]);

                // Cek Status dari SP
                // SP return: status, seat_id, message
                if (empty($detailResult) || $detailResult[0]->status === 'error') {

                    // Ambil pesan error dari SP
                    $errorMsg = $detailResult[0]->message ?? 'Unknown error';

                    // Critical: Jika satu kursi gagal (misal taken), batalkan semua?
                    // Disini kita throw exception agar masuk catch block
                    throw new \Exception("Gagal booking kursi {$seatCode}: {$errorMsg}");
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'transaction_id' => $transactionId,
                'total_amount' => $totalAmount,
                'ticket_count' => count($seats)
            ]);
        } catch (\Exception $e) {
            // Jika terjadi error (misal kursi sudah dibooking orang lain),
            // Idealnya kita hapus header transaksi yang sudah terlanjur dibuat agar tidak jadi data sampah.
            // DB::statement('DELETE FROM transaksis WHERE id_transaksi = ?', [$transactionId ?? 0]);

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
            // 1. Cek Customer via SP
            $existingCustomer = DB::select('EXEC sp_GetPelangganByPhone ?', [$phone]);

            if (!empty($existingCustomer)) {
                // SP mengembalikan kolom 'id' atau 'id_pelanggan'
                return $existingCustomer[0]->id_pelanggan ?? $existingCustomer[0]->id;
            }

            // 2. Buat Customer Baru via SP
            // Params: @nama, @email, @telp
            $customerResult = DB::select('EXEC sp_CreatePelanggan ?, ?, ?', [
                $name,
                $email,
                $phone
            ]);

            // Cek Status return SP
            if (!empty($customerResult) && $customerResult[0]->status === 'error') {
                // Jika email duplikat atau error lain dari SP
                throw new \Exception($customerResult[0]->message);
            }

            if (empty($customerResult)) {
                throw new \Exception('SP CreatePelanggan tidak mengembalikan data.');
            }

            return $customerResult[0]->id_pelanggan;
        } catch (\Exception $e) {
            // Fallback: Jika SP gagal total, coba insert manual
            // Hati-hati: Fallback hanya jalan jika error bukan dari logic SP (misal koneksi putus)
            // Jika error karena validasi email di SP, insert manual juga akan gagal (constraint).

            try {
                // Perbaiki nama kolom sesuai Schema SQL Server kita (telp, bukan no_telepon)
                $customerId = DB::table('pelanggans')->insertGetId([
                    'nama' => $name,
                    'email' => $email,
                    'telp' => $phone,         // PERBAIKAN: Gunakan 'telp'
                    // 'tanggal_daftar' => now(), // HAPUS: Kolom ini sudah tidak ada, ganti created_at
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                return $customerId;
            } catch (\Exception $fallbackError) {
                // Lempar error asli agar ketahuan kenapa gagal
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
