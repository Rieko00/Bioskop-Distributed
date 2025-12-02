<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
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
                'recentTransactions' => collect([]),
                'popularFilms' => collect([]),
                'error' => 'Gagal memuat data: ' . $e->getMessage()
            ]);
        }
        return view('kasir.tickets');
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
}
