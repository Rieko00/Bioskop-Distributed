-- Stored Procedure untuk mengambil semua log transaksi
CREATE OR ALTER PROCEDURE sp_GetLogTransaksis
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        lt.id,
        lt.created_at,
        lt.updated_at,
        lt.id_cabang,
        lt.id_pelanggan,
        lt.id_studio,
        lt.id_film,
        lt.id_jadwal,
        lt.seat_id,
        lt.id_transaksi,
        lt.id_detail_transaksi,
        lt.total_bayar,
        lt.waktu_transaksi,
        lt.rowguid,
        c.nama_cabang,
        p.nama as nama_pelanggan,
        p.email as email_pelanggan,
        s.nama_studio,
        f.judul as judul_film,
        jt.tanggal_tayang,
        jt.waktu_mulai,
        sm.seat_code,
        t.id_transaksi,
        t.metode_pembayaran,
        dt.harga
    FROM log_transaksis lt
    LEFT JOIN cabangs c ON lt.id_cabang = c.id_cabang
    LEFT JOIN pelanggans p ON lt.id_pelanggan = p.id_pelanggan
    LEFT JOIN studios s ON lt.id_studio = s.id_studio
    LEFT JOIN films f ON lt.id_film = f.id_film
    LEFT JOIN jadwal_tayangs jt ON lt.id_jadwal = jt.id_jadwal
    LEFT JOIN seat_maps sm ON lt.seat_id = sm.id_seat
    LEFT JOIN transaksis t ON lt.id_transaksi = t.id_transaksi
    LEFT JOIN detail_transaksis dt ON lt.id_detail_transaksi = dt.id_detail_transaksi
    ORDER BY lt.waktu_transaksi DESC;
END;
GO

-- Stored Procedure untuk mengambil log transaksi by GUID
CREATE OR ALTER PROCEDURE sp_GetLogTransaksiById
    @rowguid NVARCHAR(50)
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        lt.id,
        lt.created_at,
        lt.updated_at,
        lt.id_cabang,
        lt.id_pelanggan,
        lt.id_studio,
        lt.id_film,
        lt.id_jadwal,
        lt.seat_id,
        lt.id_transaksi,
        lt.id_detail_transaksi,
        lt.total_bayar,
        lt.waktu_transaksi,
        lt.rowguid,
        c.nama_cabang,
        c.alamat as alamat_cabang,
        c.kode_cabang_kota,
        p.nama as nama_pelanggan,
        p.email as email_pelanggan,
        p.telp as no_telepon,
        s.nama_studio,
        s.tipe_studio,
        s.kapasitas,
        f.judul as judul_film,
        f.sinopsis,
        f.durasi_menit,
        f.rating_usia,
        f.harga_film,
        jt.tanggal_tayang,
        jt.waktu_mulai,
        jt.waktu_selesai,
        sm.seat_code,
        sm.no_baris,
        sm.no_kolom,
        t.id_transaksi,
        t.metode_pembayaran,
        dt.harga
    FROM log_transaksis lt
    LEFT JOIN cabangs c ON lt.id_cabang = c.id_cabang
    LEFT JOIN pelanggans p ON lt.id_pelanggan = p.id_pelanggan
    LEFT JOIN studios s ON lt.id_studio = s.id_studio
    LEFT JOIN films f ON lt.id_film = f.id_film
    LEFT JOIN jadwal_tayangs jt ON lt.id_jadwal = jt.id_jadwal
    LEFT JOIN seat_maps sm ON lt.seat_id = sm.id_seat
    LEFT JOIN transaksis t ON lt.id_transaksi = t.id_transaksi
    LEFT JOIN detail_transaksis dt ON lt.id_detail_transaksi = dt.id_detail_transaksi
    WHERE lt.rowguid = @rowguid;
END;
GO

-- Stored Procedure untuk filter log transaksi berdasarkan parameter
CREATE OR ALTER PROCEDURE sp_GetLogTransaksisByFilter
    @start_date DATE = NULL,
    @end_date DATE = NULL,
    @id_cabang BIGINT = NULL,
    @id_film BIGINT = NULL
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        lt.id,
        lt.created_at,
        lt.updated_at,
        lt.id_cabang,
        lt.id_pelanggan,
        lt.id_studio,
        lt.id_film,
        lt.id_jadwal,
        lt.seat_id,
        lt.id_transaksi,
        lt.id_detail_transaksi,
        lt.total_bayar,
        lt.waktu_transaksi,
        lt.rowguid,
        c.nama_cabang,
        p.nama as nama_pelanggan,
        p.email as email_pelanggan,
        s.nama_studio,
        f.judul as judul_film,
        jt.tanggal_tayang,
        jt.waktu_mulai,
        sm.seat_code,
        t.id_transaksi,
        t.metode_pembayaran,
        dt.harga
    FROM log_transaksis lt
    LEFT JOIN cabangs c ON lt.id_cabang = c.id_cabang
    LEFT JOIN pelanggans p ON lt.id_pelanggan = p.id_pelanggan
    LEFT JOIN studios s ON lt.id_studio = s.id_studio
    LEFT JOIN films f ON lt.id_film = f.id_film
    LEFT JOIN jadwal_tayangs jt ON lt.id_jadwal = jt.id_jadwal
    LEFT JOIN seat_maps sm ON lt.seat_id = sm.seat_code
    LEFT JOIN transaksis t ON lt.id_transaksi = t.id_transaksi
    LEFT JOIN detail_transaksis dt ON lt.id_detail_transaksi = dt.id_detail_transaksi
    WHERE 1=1
        AND (@start_date IS NULL OR CAST(lt.waktu_transaksi AS DATE) >= @start_date)
        AND (@end_date IS NULL OR CAST(lt.waktu_transaksi AS DATE) <= @end_date)
        AND (@id_cabang IS NULL OR lt.id_cabang = @id_cabang)
        AND (@id_film IS NULL OR lt.id_film = @id_film)
    ORDER BY lt.waktu_transaksi DESC;
END;
GO