<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Cabang;
use App\Models\Studio;
use App\Models\Film;
use App\Models\JadwalTayang;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        try {
            $stats = DB::select('EXEC sp_GetDashboardStats')[0];
            return view('admin.dashboard', compact('stats'));
        } catch (\Exception $e) {
            $stats = (object)[
                'total_cabang' => 0,
                'total_studio' => 0,
                'total_film' => 0,
                'total_jadwal' => 0,
                'total_admin' => 0,
                'total_kasir' => 0,
                'transaksi_hari_ini' => 0,
                'pendapatan_hari_ini' => 0
            ];
            return view('admin.dashboard', compact('stats'));
        }
    }

    // =================== USER MANAGEMENT ===================
    public function users(): View
    {
        try {
            $users = DB::select('EXEC sp_GetUsers');
            // dd($users);
            return view('admin.users', compact('users'));
        } catch (\Exception $e) {
            $users = [];
            return view('admin.users', compact('users'))->with(['error' => 'Failed to load users data.']);
        }
    }

    public function getUsersByRole(Request $request): JsonResponse
    {
        try {
            $role = $request->get('role');
            $users = DB::select('EXEC sp_GetUsersByRole ?', [$role]);
            return response()->json(['success' => true, 'data' => $users]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getUser($id): JsonResponse
    {
        try {
            $user = DB::select('EXEC sp_GetUserById ?', [$id]);
            if (empty($user)) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }
            return response()->json(['success' => true, 'data' => $user[0]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeUser(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,kasir'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $hashedPassword = Hash::make($request->password);
            $result = DB::select('EXEC sp_CreateUser ?, ?, ?, ?', [
                $request->name,
                $request->email,
                $hashedPassword,
                $request->role
            ]);

            return response()->json(['success' => true, 'data' => $result[0], 'message' => 'User created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateUser(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,kasir'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::select('EXEC sp_UpdateUser ?, ?, ?, ?', [
                $id,
                $request->name,
                $request->email,
                $request->role
            ]);

            return response()->json(['success' => true, 'message' => 'User updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateUserPassword(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $hashedPassword = Hash::make($request->password);
            DB::select('EXEC sp_UpdateUserPassword ?, ?', [$id, $hashedPassword]);

            return response()->json(['success' => true, 'message' => 'Password updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteUser($id): JsonResponse
    {
        try {
            DB::select('EXEC sp_DeleteUser ?', [$id]);
            return response()->json(['success' => true, 'message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // =================== CABANG MANAGEMENT ===================
    public function branches(): View
    {
        try {
            $cabangs = DB::select('EXEC sp_GetCabangs');
            return view('admin.branches', compact('cabangs'));
        } catch (\Exception $e) {
            $cabangs = [];
            return view('admin.branches', compact('cabangs'))->with(['error' => 'Failed to load branches data.']);
        }
    }

    public function getCabang($id): JsonResponse
    {
        try {
            $cabang = DB::select('EXEC sp_GetCabangById ?', [$id]);
            if (empty($cabang)) {
                return response()->json(['success' => false, 'message' => 'Branch not found'], 404);
            }
            return response()->json(['success' => true, 'data' => $cabang[0]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeCabang(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'kode_cabang_kota' => 'required|string|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $result = DB::select('EXEC sp_CreateCabang ?, ?, ?', [
                $request->nama_cabang,
                $request->alamat,
                $request->kode_cabang_kota
            ]);

            return response()->json(['success' => true, 'data' => $result[0], 'message' => 'Branch created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateCabang(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'kode_cabang_kota' => 'required|string|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::select('EXEC sp_UpdateCabang ?, ?, ?, ?', [
                $id,
                $request->nama_cabang,
                $request->alamat,
                $request->kode_cabang_kota
            ]);

            return response()->json(['success' => true, 'message' => 'Branch updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteCabang($id): JsonResponse
    {
        try {
            DB::select('EXEC sp_DeleteCabang ?', [$id]);
            return response()->json(['success' => true, 'message' => 'Branch deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // =================== STUDIO MANAGEMENT ===================
    public function studios(): View
    {
        try {
            $studios = DB::select('EXEC sp_GetStudios');
            $cabangs = DB::select('EXEC sp_GetCabangs');
            return view('admin.studios', compact('studios', 'cabangs'));
        } catch (\Exception $e) {
            $studios = [];
            $cabangs = [];
            return view('admin.studios', compact('studios', 'cabangs'))->with(['error' => 'Failed to load studios data.']);
        }
    }

    public function getStudiosByCabang($cabangId): JsonResponse
    {
        try {
            $studios = DB::select('EXEC sp_GetStudiosByCabang ?', [$cabangId]);
            return response()->json(['success' => true, 'data' => $studios]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeStudio(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_cabang' => 'required|integer|exists:cabangs,id_cabang',
            'nama_studio' => 'required|string|max:255',
            'jumlah_baris' => 'required|integer|min:1|max:26',
            'jumlah_kolom_per_baris' => 'required|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $result = DB::select('EXEC sp_CreateStudioWithSeats ?, ?, ?, ?', [
                $request->id_cabang,
                $request->nama_studio,
                $request->jumlah_baris,
                $request->jumlah_kolom_per_baris
            ]);

            return response()->json([
                'success' => true,
                'data' => $result[0],
                'message' => 'Studio dan kursi berhasil dibuat. Total kapasitas: ' . ($request->jumlah_baris * $request->jumlah_kolom_per_baris) . ' kursi'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStudio(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_cabang' => 'required|integer|exists:cabangs,id_cabang',
            'nama_studio' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::select('EXEC sp_UpdateStudio ?, ?, ?', [
                $id,
                $request->id_cabang,
                $request->nama_studio,
            ]);

            return response()->json(['success' => true, 'message' => 'Studio updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteStudio($id): JsonResponse
    {
        try {
            DB::select('EXEC sp_DeleteStudio ?', [$id]);
            return response()->json(['success' => true, 'message' => 'Studio deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getSeatMap($id): JsonResponse
    {
        try {
            $studio = DB::select('EXEC sp_GetStudioById ?', [$id]);
            if (empty($studio)) {
                return response()->json(['success' => false, 'message' => 'Studio not found'], 404);
            }

            $seats = DB::select('SELECT * FROM seat_maps WHERE id_studio = ? ORDER BY no_baris, CAST(no_kolom AS INT)', [$id]);

            return response()->json([
                'success' => true,
                'studio' => $studio[0],
                'seats' => $seats
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // =================== FILM MANAGEMENT ===================
    public function films(): View
    {
        try {
            $films = DB::select('EXEC sp_GetFilms');
            return view('admin.films', compact('films'));
        } catch (\Exception $e) {
            $films = [];
            return view('admin.films', compact('films'))->with(['error' => 'Failed to load films data.']);
        }
    }

    public function getFilm($id): JsonResponse
    {
        try {
            $film = DB::select('EXEC sp_GetFilmById ?', [$id]);
            if (empty($film)) {
                return response()->json(['success' => false, 'message' => 'Film not found'], 404);
            }
            return response()->json(['success' => true, 'data' => $film[0]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeFilm(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'durasi_menit' => 'required|integer|min:1',
            'rating_usia' => 'required|string|max:10',
            'harga_film' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $result = DB::select('EXEC sp_CreateFilm ?, ?, ?, ?, ?', [
                $request->judul,
                $request->sinopsis,
                $request->durasi_menit,
                $request->rating_usia,
                $request->harga_film
            ]);

            return response()->json(['success' => true, 'data' => $result[0], 'message' => 'Film created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateFilm(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'durasi_menit' => 'required|integer|min:1',
            'rating_usia' => 'required|string|max:10',
            'harga_film' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::select('EXEC sp_UpdateFilm ?, ?, ?, ?, ?, ?', [
                $id,
                $request->judul,
                $request->sinopsis,
                $request->durasi_menit,
                $request->rating_usia,
                $request->harga_film
            ]);

            return response()->json(['success' => true, 'message' => 'Film updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteFilm($id): JsonResponse
    {
        try {
            DB::select('EXEC sp_DeleteFilm ?', [$id]);
            return response()->json(['success' => true, 'message' => 'Film deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // =================== JADWAL TAYANG MANAGEMENT ===================
    public function schedules(): View
    {
        try {
            $jadwals = DB::select('EXEC sp_GetJadwalTayang');
            $films = DB::select('EXEC sp_GetFilms');
            $studios = DB::select('EXEC sp_GetStudios');
            return view('admin.schedules', compact('jadwals', 'films', 'studios'));
        } catch (\Exception $e) {
            $jadwals = [];
            $films = [];
            $studios = [];
            return view('admin.schedules', compact('jadwals', 'films', 'studios'))->with(['error' => 'Failed to load schedules data.']);
        }
    }

    public function getSchedulesByCabang($cabangId): JsonResponse
    {
        try {
            $schedules = DB::select('EXEC sp_GetJadwalTayangByCabang ?', [$cabangId]);
            return response()->json(['success' => true, 'data' => $schedules]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeSchedule(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_film' => 'required|integer|exists:films,id_film',
            'id_studio' => 'required|integer|exists:studios,id_studio',
            'waktu_mulai' => 'required|date_format:H:i',
            'tanggal_tayang' => 'required|date|after_or_equal:today'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $result = DB::select('EXEC sp_CreateJadwalTayang ?, ?, ?, ?', [
                $request->id_film,
                $request->id_studio,
                $request->waktu_mulai,
                $request->tanggal_tayang
            ]);

            return response()->json(['success' => true, 'data' => $result[0], 'message' => 'Schedule created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateSchedule(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_film' => 'required|integer|exists:films,id_film',
            'id_studio' => 'required|integer|exists:studios,id_studio',
            'waktu_mulai' => 'required|date_format:H:i',
            'tanggal_tayang' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::select('EXEC sp_UpdateJadwalTayang ?, ?, ?, ?, ?', [
                $id,
                $request->id_film,
                $request->id_studio,
                $request->waktu_mulai,
                $request->tanggal_tayang
            ]);

            return response()->json(['success' => true, 'message' => 'Schedule updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteSchedule($id): JsonResponse
    {
        try {
            DB::select('EXEC sp_DeleteJadwalTayang ?', [$id]);
            return response()->json(['success' => true, 'message' => 'Schedule deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // =================== OTHER VIEWS ===================
    public function reports(): View
    {
        try {
            $logTransaksis = DB::select('EXEC sp_GetLogTransaksis');
            $cabangs = DB::select('EXEC sp_GetCabangs');
            $films = DB::select('EXEC sp_GetFilms');
            return view('admin.log_transaksi', compact('logTransaksis', 'cabangs', 'films'));
        } catch (\Exception $e) {
            $logTransaksis = [];
            $cabangs = [];
            $films = [];
            return view('admin.log_transaksi', compact('logTransaksis', 'cabangs', 'films'))->with(['error' => 'Failed to load transaction logs: ' . $e->getMessage()]);
        }
    }

    public function getLogTransaksiById($rowguid): JsonResponse
    {
        try {
            $logTransaksi = DB::select('EXEC sp_GetLogTransaksiById ?', [$rowguid]);
            if (empty($logTransaksi)) {
                return response()->json(['success' => false, 'message' => 'Log transaksi not found'], 404);
            }
            return response()->json(['success' => true, 'data' => $logTransaksi[0]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function filterLogTransaksi(Request $request): JsonResponse
    {
        try {
            $logTransaksis = DB::select('EXEC sp_GetLogTransaksisByFilter ?, ?, ?, ?', [
                $request->start_date,
                $request->end_date,
                $request->id_cabang,
                $request->id_film
            ]);
            return response()->json(['success' => true, 'data' => $logTransaksis]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function exportLogTransaksiExcel(Request $request): Response
    {
        try {
            $logTransaksis = DB::select('EXEC sp_GetLogTransaksisByFilter ?, ?, ?, ?', [
                $request->start_date,
                $request->end_date,
                $request->id_cabang,
                $request->id_film
            ]);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $headers = [
                'ID',
                'No Transaksi',
                'Cabang',
                'Pelanggan',
                'Email',
                'Film',
                'Studio',
                'Tanggal Tayang',
                'Waktu Mulai',
                'Kursi',
                'Total Bayar',
                'Metode Pembayaran',
                'Status',
                'Waktu Transaksi'
            ];

            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . '1', $header);
                $column++;
            }

            // Data
            $row = 2;
            foreach ($logTransaksis as $log) {
                $sheet->setCellValue('A' . $row, $log->id);
                $sheet->setCellValue('B' . $row, $log->no_transaksi ?? '-');
                $sheet->setCellValue('C' . $row, $log->nama_cabang ?? '-');
                $sheet->setCellValue('D' . $row, $log->nama_pelanggan ?? '-');
                $sheet->setCellValue('E' . $row, $log->email_pelanggan ?? '-');
                $sheet->setCellValue('F' . $row, $log->judul_film ?? '-');
                $sheet->setCellValue('G' . $row, $log->nama_studio ?? '-');
                $sheet->setCellValue('H' . $row, $log->tanggal_tayang ?? '-');
                $sheet->setCellValue('I' . $row, $log->waktu_mulai ?? '-');
                $sheet->setCellValue('J' . $row, $log->seat_code ?? '-');
                $sheet->setCellValue('K' . $row, $log->total_bayar ?? 0);
                $sheet->setCellValue('L' . $row, $log->metode_pembayaran ?? '-');
                $sheet->setCellValue('M' . $row, $log->status_pembayaran ?? '-');
                $sheet->setCellValue('N' . $row, $log->waktu_transaksi ?? '-');
                $row++;
            }

            $writer = new Xlsx($spreadsheet);
            $fileName = 'log_transaksi_' . date('Y_m_d_H_i_s') . '.xlsx';
            $tempPath = storage_path('app/temp/' . $fileName);

            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0777, true);
            }

            $writer->save($tempPath);

            return response()->download($tempPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function exportLogTransaksiPdf(Request $request): Response
    {
        try {
            $logTransaksis = DB::select('EXEC sp_GetLogTransaksisByFilter ?, ?, ?, ?', [
                $request->start_date,
                $request->end_date,
                $request->id_cabang,
                $request->id_film
            ]);

            $pdf = Pdf::loadView('admin.reports.pdf', compact('logTransaksis'))
                ->setPaper('a4', 'landscape');

            $fileName = 'log_transaksi_' . date('Y_m_d_H_i_s') . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function settings(): View
    {
        return view('admin.settings');
    }
}
