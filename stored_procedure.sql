CREATE PROCEDURE sp_LaporanPendapatanFilm_Cursor
AS
BEGIN
    DECLARE @nama_film NVARCHAR(100);
    DECLARE @total_pendapatan INT;
    DECLARE @jumlah_tiket INT;

    -- 1. Deklarasi Cursor
    DECLARE cur_laporan CURSOR FOR
        SELECT 
            f.judul, 
            SUM(dt.harga) as total_uang,
            COUNT(dt.id_detail_transaksi) as total_tiket
        FROM detail_transaksis dt
        JOIN jadwal_tayangs j ON dt.id_jadwal = j.id_jadwal
        JOIN films f ON j.id_film = f.id_film
        GROUP BY f.judul;

    -- 2. Buka Cursor
    OPEN cur_laporan;

    PRINT '=== LAPORAN PENDAPATAN PER FILM ===';

    -- 3. Ambil baris pertama
    FETCH NEXT FROM cur_laporan INTO @nama_film, @total_pendapatan, @jumlah_tiket;

    -- 4. Looping selama data masih ada (@@FETCH_STATUS = 0 artinya sukses ambil data)
    WHILE @@FETCH_STATUS = 0
    BEGIN
        -- Tampilkan data
        PRINT 'Film: ' + @nama_film;
        PRINT '   - Tiket Terjual: ' + CAST(@jumlah_tiket AS NVARCHAR(10));
        PRINT '   - Total Pendapatan: Rp ' + CAST(@total_pendapatan AS NVARCHAR(20));
        PRINT '-----------------------------------';

        -- Ambil baris selanjutnya
        FETCH NEXT FROM cur_laporan INTO @nama_film, @total_pendapatan, @jumlah_tiket;
    END

    -- 5. Tutup dan Hapus Cursor
    CLOSE cur_laporan;
    DEALLOCATE cur_laporan;
END;
GO

CREATE PROCEDURE sp_ViewJadwalLengkap
    @KodeKota NVARCHAR(50) = NULL -- Parameter opsional (bisa dikosongi)
AS
BEGIN
    SET NOCOUNT ON;

    SELECT 
        f.judul AS [Judul Film],
        f.rating_usia AS [Rating],
        f.durasi_menit AS [Durasi (Menit)],
        s.nama_studio AS [Studio],
        c.nama_cabang AS [Lokasi Bioskop],
        j.waktu_mulai AS [Jam Tayang],
        f.harga_film AS [Harga Tiket]
    FROM jadwal_tayangs j
    JOIN films f ON j.id_film = f.id_film
    JOIN studios s ON j.id_studio = s.id_studio
    JOIN cabangs c ON s.id_cabang = c.id_cabang
    WHERE 
        -- Jika parameter kota diisi, filter berdasarkan kota/alamat
        (@KodeKota IS NULL OR c.kode_cabang_kota LIKE '%' + @KodeKota + '%')
    ORDER BY 
        c.nama_cabang ASC, 
        j.waktu_mulai ASC;
END;
GO


