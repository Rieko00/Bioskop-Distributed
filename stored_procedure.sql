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

-- =====================================================
-- ADMIN CENTER MANAGEMENT STORED PROCEDURES
-- =====================================================

-- 1. CABANG MANAGEMENT PROCEDURES
CREATE PROCEDURE sp_GetAllCabangs
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        id_cabang,
        nama_cabang,
        alamat,
        kode_cabang_kota,
        created_at,
        updated_at
    FROM cabangs
    ORDER BY nama_cabang ASC;
END;
GO

CREATE PROCEDURE sp_GetCabangById
    @IdCabang INT
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        id_cabang,
        nama_cabang,
        alamat,
        kode_cabang_kota,
        created_at,
        updated_at
    FROM cabangs
    WHERE id_cabang = @IdCabang;
END;
GO

CREATE PROCEDURE sp_CreateCabang
    @NamaCabang NVARCHAR(255),
    @Alamat NVARCHAR(500),
    @KodeCabangKota NVARCHAR(10),
    @IdCabang INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    
    INSERT INTO cabangs (nama_cabang, alamat, kode_cabang_kota, created_at, updated_at)
    VALUES (@NamaCabang, @Alamat, @KodeCabangKota, GETDATE(), GETDATE());
    
    SET @IdCabang = SCOPE_IDENTITY();
    
    SELECT @IdCabang as id_cabang;
END;
GO

CREATE PROCEDURE sp_UpdateCabang
    @IdCabang INT,
    @NamaCabang NVARCHAR(255),
    @Alamat NVARCHAR(500),
    @KodeCabangKota NVARCHAR(10)
AS
BEGIN
    SET NOCOUNT ON;
    
    UPDATE cabangs 
    SET 
        nama_cabang = @NamaCabang,
        alamat = @Alamat,
        kode_cabang_kota = @KodeCabangKota,
        updated_at = GETDATE()
    WHERE id_cabang = @IdCabang;
    
    SELECT @@ROWCOUNT as affected_rows;
END;
GO

CREATE PROCEDURE sp_DeleteCabang
    @IdCabang INT
AS
BEGIN
    SET NOCOUNT ON;
    
    -- Check if cabang has studios
    DECLARE @StudioCount INT;
    SELECT @StudioCount = COUNT(*) FROM studios WHERE id_cabang = @IdCabang;
    
    IF @StudioCount > 0
    BEGIN
        RAISERROR('Cannot delete cabang. It has associated studios.', 16, 1);
        RETURN;
    END
    
    DELETE FROM cabangs WHERE id_cabang = @IdCabang;
    
    SELECT @@ROWCOUNT as affected_rows;
END;
GO

-- 2. STUDIO MANAGEMENT PROCEDURES
CREATE PROCEDURE sp_GetAllStudios
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        s.id_studio,
        s.id_cabang,
        c.nama_cabang,
        s.nama_studio,
        s.tipe_studio,
        s.kapasitas,
        s.created_at,
        s.updated_at
    FROM studios s
    JOIN cabangs c ON s.id_cabang = c.id_cabang
    ORDER BY c.nama_cabang ASC, s.nama_studio ASC;
END;
GO

CREATE PROCEDURE sp_GetStudiosByCabang
    @IdCabang INT
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        s.id_studio,
        s.id_cabang,
        c.nama_cabang,
        s.nama_studio,
        s.tipe_studio,
        s.kapasitas,
        s.created_at,
        s.updated_at
    FROM studios s
    JOIN cabangs c ON s.id_cabang = c.id_cabang
    WHERE s.id_cabang = @IdCabang
    ORDER BY s.nama_studio ASC;
END;
GO

CREATE PROCEDURE sp_CreateStudio
    @IdCabang INT,
    @NamaStudio NVARCHAR(255),
    @TipeStudio NVARCHAR(100),
    @Kapasitas INT,
    @IdStudio INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    
    INSERT INTO studios (id_cabang, nama_studio, tipe_studio, kapasitas, created_at, updated_at)
    VALUES (@IdCabang, @NamaStudio, @TipeStudio, @Kapasitas, GETDATE(), GETDATE());
    
    SET @IdStudio = SCOPE_IDENTITY();
    
    SELECT @IdStudio as id_studio;
END;
GO

CREATE PROCEDURE sp_UpdateStudio
    @IdStudio INT,
    @IdCabang INT,
    @NamaStudio NVARCHAR(255),
    @TipeStudio NVARCHAR(100),
    @Kapasitas INT
AS
BEGIN
    SET NOCOUNT ON;
    
    UPDATE studios 
    SET 
        id_cabang = @IdCabang,
        nama_studio = @NamaStudio,
        tipe_studio = @TipeStudio,
        kapasitas = @Kapasitas,
        updated_at = GETDATE()
    WHERE id_studio = @IdStudio;
    
    SELECT @@ROWCOUNT as affected_rows;
END;
GO

CREATE PROCEDURE sp_DeleteStudio
    @IdStudio INT
AS
BEGIN
    SET NOCOUNT ON;
    
    -- Check if studio has schedules
    DECLARE @ScheduleCount INT;
    SELECT @ScheduleCount = COUNT(*) FROM jadwal_tayangs WHERE id_studio = @IdStudio;
    
    IF @ScheduleCount > 0
    BEGIN
        RAISERROR('Cannot delete studio. It has associated schedules.', 16, 1);
        RETURN;
    END
    
    DELETE FROM studios WHERE id_studio = @IdStudio;
    
    SELECT @@ROWCOUNT as affected_rows;
END;
GO

-- 3. FILM MANAGEMENT PROCEDURES
CREATE PROCEDURE sp_GetAllFilms
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        id_film,
        judul,
        sinopsis,
        durasi_menit,
        rating_usia,
        harga_film,
        created_at,
        updated_at
    FROM films
    ORDER BY judul ASC;
END;
GO

CREATE PROCEDURE sp_GetFilmById
    @IdFilm INT
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        id_film,
        judul,
        sinopsis,
        durasi_menit,
        rating_usia,
        harga_film,
        created_at,
        updated_at
    FROM films
    WHERE id_film = @IdFilm;
END;
GO

CREATE PROCEDURE sp_CreateFilm
    @Judul NVARCHAR(255),
    @Sinopsis NVARCHAR(MAX),
    @DurasiMenit INT,
    @RatingUsia NVARCHAR(10),
    @HargaFilm DECIMAL(10,2),
    @IdFilm INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    
    INSERT INTO films (judul, sinopsis, durasi_menit, rating_usia, harga_film, created_at, updated_at)
    VALUES (@Judul, @Sinopsis, @DurasiMenit, @RatingUsia, @HargaFilm, GETDATE(), GETDATE());
    
    SET @IdFilm = SCOPE_IDENTITY();
    
    SELECT @IdFilm as id_film;
END;
GO

CREATE PROCEDURE sp_UpdateFilm
    @IdFilm INT,
    @Judul NVARCHAR(255),
    @Sinopsis NVARCHAR(MAX),
    @DurasiMenit INT,
    @RatingUsia NVARCHAR(10),
    @HargaFilm DECIMAL(10,2)
AS
BEGIN
    SET NOCOUNT ON;
    
    UPDATE films 
    SET 
        judul = @Judul,
        sinopsis = @Sinopsis,
        durasi_menit = @DurasiMenit,
        rating_usia = @RatingUsia,
        harga_film = @HargaFilm,
        updated_at = GETDATE()
    WHERE id_film = @IdFilm;
    
    SELECT @@ROWCOUNT as affected_rows;
END;
GO

CREATE PROCEDURE sp_DeleteFilm
    @IdFilm INT
AS
BEGIN
    SET NOCOUNT ON;
    
    -- Check if film has schedules
    DECLARE @ScheduleCount INT;
    SELECT @ScheduleCount = COUNT(*) FROM jadwal_tayangs WHERE id_film = @IdFilm;
    
    IF @ScheduleCount > 0
    BEGIN
        RAISERROR('Cannot delete film. It has associated schedules.', 16, 1);
        RETURN;
    END
    
    DELETE FROM films WHERE id_film = @IdFilm;
    
    SELECT @@ROWCOUNT as affected_rows;
END;
GO

-- 4. JADWAL TAYANG MANAGEMENT PROCEDURES
CREATE PROCEDURE sp_GetAllJadwalTayang
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        j.id_jadwal,
        j.id_film,
        f.judul as nama_film,
        j.id_studio,
        s.nama_studio,
        c.nama_cabang,
        j.waktu_mulai,
        j.waktu_selesai,
        j.tanggal_tayang,
        j.created_at,
        j.updated_at
    FROM jadwal_tayangs j
    JOIN films f ON j.id_film = f.id_film
    JOIN studios s ON j.id_studio = s.id_studio
    JOIN cabangs c ON s.id_cabang = c.id_cabang
    ORDER BY j.tanggal_tayang ASC, j.waktu_mulai ASC;
END;
GO

CREATE PROCEDURE sp_GetJadwalTayangByCabang
    @IdCabang INT
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        j.id_jadwal,
        j.id_film,
        f.judul as nama_film,
        j.id_studio,
        s.nama_studio,
        c.nama_cabang,
        j.waktu_mulai,
        j.waktu_selesai,
        j.tanggal_tayang,
        j.created_at,
        j.updated_at
    FROM jadwal_tayangs j
    JOIN films f ON j.id_film = f.id_film
    JOIN studios s ON j.id_studio = s.id_studio
    JOIN cabangs c ON s.id_cabang = c.id_cabang
    WHERE c.id_cabang = @IdCabang
    ORDER BY j.tanggal_tayang ASC, j.waktu_mulai ASC;
END;
GO

CREATE PROCEDURE sp_CreateJadwalTayang
    @IdFilm INT,
    @IdStudio INT,
    @WaktuMulai TIME,
    @WaktuSelesai TIME,
    @TanggalTayang DATE,
    @IdJadwal INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    
    -- Check for schedule conflicts
    DECLARE @ConflictCount INT;
    SELECT @ConflictCount = COUNT(*)
    FROM jadwal_tayangs
    WHERE id_studio = @IdStudio 
    AND tanggal_tayang = @TanggalTayang
    AND (
        (@WaktuMulai BETWEEN waktu_mulai AND waktu_selesai) OR
        (@WaktuSelesai BETWEEN waktu_mulai AND waktu_selesai) OR
        (waktu_mulai BETWEEN @WaktuMulai AND @WaktuSelesai)
    );
    
    IF @ConflictCount > 0
    BEGIN
        RAISERROR('Schedule conflict detected for this studio and time.', 16, 1);
        RETURN;
    END
    
    INSERT INTO jadwal_tayangs (id_film, id_studio, waktu_mulai, waktu_selesai, tanggal_tayang, created_at, updated_at)
    VALUES (@IdFilm, @IdStudio, @WaktuMulai, @WaktuSelesai, @TanggalTayang, GETDATE(), GETDATE());
    
    SET @IdJadwal = SCOPE_IDENTITY();
    
    SELECT @IdJadwal as id_jadwal;
END;
GO

CREATE PROCEDURE sp_UpdateJadwalTayang
    @IdJadwal INT,
    @IdFilm INT,
    @IdStudio INT,
    @WaktuMulai TIME,
    @WaktuSelesai TIME,
    @TanggalTayang DATE
AS
BEGIN
    SET NOCOUNT ON;
    
    -- Check for schedule conflicts (excluding current schedule)
    DECLARE @ConflictCount INT;
    SELECT @ConflictCount = COUNT(*)
    FROM jadwal_tayangs
    WHERE id_studio = @IdStudio 
    AND tanggal_tayang = @TanggalTayang
    AND id_jadwal != @IdJadwal
    AND (
        (@WaktuMulai BETWEEN waktu_mulai AND waktu_selesai) OR
        (@WaktuSelesai BETWEEN waktu_mulai AND waktu_selesai) OR
        (waktu_mulai BETWEEN @WaktuMulai AND @WaktuSelesai)
    );
    
    IF @ConflictCount > 0
    BEGIN
        RAISERROR('Schedule conflict detected for this studio and time.', 16, 1);
        RETURN;
    END
    
    UPDATE jadwal_tayangs 
    SET 
        id_film = @IdFilm,
        id_studio = @IdStudio,
        waktu_mulai = @WaktuMulai,
        waktu_selesai = @WaktuSelesai,
        tanggal_tayang = @TanggalTayang,
        updated_at = GETDATE()
    WHERE id_jadwal = @IdJadwal;
    
    SELECT @@ROWCOUNT as affected_rows;
END;
GO

CREATE PROCEDURE sp_DeleteJadwalTayang
    @IdJadwal INT
AS
BEGIN
    SET NOCOUNT ON;
    
    -- Check if schedule has transactions
    DECLARE @TransactionCount INT;
    SELECT @TransactionCount = COUNT(*) FROM detail_transaksis WHERE id_jadwal = @IdJadwal;
    
    IF @TransactionCount > 0
    BEGIN
        RAISERROR('Cannot delete schedule. It has associated transactions.', 16, 1);
        RETURN;
    END
    
    DELETE FROM jadwal_tayangs WHERE id_jadwal = @IdJadwal;
    
    SELECT @@ROWCOUNT as affected_rows;
END;
GO

-- 5. USER MANAGEMENT PROCEDURES
CREATE PROCEDURE sp_GetAllUsers
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        id,
        name,
        email,
        role,
        email_verified_at,
        created_at,
        updated_at
    FROM users
    ORDER BY name ASC;
END;
GO

CREATE PROCEDURE sp_GetUserById
    @Id BIGINT
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        id,
        name,
        email,
        role,
        email_verified_at,
        created_at,
        updated_at
    FROM users
    WHERE id = @Id;
END;
GO

CREATE PROCEDURE sp_GetUsersByRole
    @Role NVARCHAR(50)
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        id,
        name,
        email,
        role,
        email_verified_at,
        created_at,
        updated_at
    FROM users
    WHERE role = @Role
    ORDER BY name ASC;
END;
GO

CREATE PROCEDURE sp_CreateUser
    @Name NVARCHAR(255),
    @Email NVARCHAR(255),
    @Password NVARCHAR(255),
    @Role NVARCHAR(50),
    @UserId BIGINT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    
    -- Check if email already exists
    DECLARE @ExistingCount INT;
    SELECT @ExistingCount = COUNT(*) FROM users WHERE email = @Email;
    
    IF @ExistingCount > 0
    BEGIN
        RAISERROR('Email already exists.', 16, 1);
        RETURN;
    END
    
    INSERT INTO users (name, email, password, role, created_at, updated_at)
    VALUES (@Name, @Email, @Password, @Role, GETDATE(), GETDATE());
    
    SET @UserId = SCOPE_IDENTITY();
    
    SELECT @UserId as id;
END;
GO

CREATE PROCEDURE sp_UpdateUser
    @Id BIGINT,
    @Name NVARCHAR(255),
    @Email NVARCHAR(255),
    @Role NVARCHAR(50)
AS
BEGIN
    SET NOCOUNT ON;
    
    -- Check if email already exists for other users
    DECLARE @ExistingCount INT;
    SELECT @ExistingCount = COUNT(*) FROM users WHERE email = @Email AND id != @Id;
    
    IF @ExistingCount > 0
    BEGIN
        RAISERROR('Email already exists.', 16, 1);
        RETURN;
    END
    
    UPDATE users 
    SET 
        name = @Name,
        email = @Email,
        role = @Role,
        updated_at = GETDATE()
    WHERE id = @Id;
    
    SELECT @@ROWCOUNT as affected_rows;
END;
GO

CREATE PROCEDURE sp_UpdateUserPassword
    @Id BIGINT,
    @Password NVARCHAR(255)
AS
BEGIN
    SET NOCOUNT ON;
    
    UPDATE users 
    SET 
        password = @Password,
        updated_at = GETDATE()
    WHERE id = @Id;
    
    SELECT @@ROWCOUNT as affected_rows;
END;
GO

CREATE PROCEDURE sp_DeleteUser
    @Id BIGINT
AS
BEGIN
    SET NOCOUNT ON;
    
    DELETE FROM users WHERE id = @Id;
    
    SELECT @@ROWCOUNT as affected_rows;
END;
GO

-- 6. DASHBOARD STATISTICS PROCEDURES
CREATE PROCEDURE sp_GetDashboardStats
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        (SELECT COUNT(*) FROM cabangs) as total_cabang,
        (SELECT COUNT(*) FROM studios) as total_studio,
        (SELECT COUNT(*) FROM films) as total_film,
        (SELECT COUNT(*) FROM jadwal_tayangs) as total_jadwal,
        (SELECT COUNT(*) FROM users WHERE role = 'admin') as total_admin,
        (SELECT COUNT(*) FROM users WHERE role = 'kasir') as total_kasir,
        (SELECT COUNT(*) FROM transaksis WHERE CAST(created_at AS DATE) = CAST(GETDATE() AS DATE)) as transaksi_hari_ini,
        (SELECT ISNULL(SUM(dt.harga), 0) FROM detail_transaksis dt JOIN transaksis t ON dt.id_transaksi = t.id_transaksi WHERE CAST(t.created_at AS DATE) = CAST(GETDATE() AS DATE)) as pendapatan_hari_ini;
END;
GO


