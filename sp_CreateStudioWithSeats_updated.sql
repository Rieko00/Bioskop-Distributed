CREATE OR ALTER PROCEDURE sp_CreateStudioWithSeats
    @id_cabang BIGINT,
    @nama_studio NVARCHAR(50),
    @jumlah_baris INT,       -- Contoh: 10 Baris (A sampai J)
    @jumlah_kolom_per_baris INT -- Contoh: 12 Kursi per baris
AS
BEGIN
    SET NOCOUNT ON;
    
    BEGIN TRY
        BEGIN TRANSACTION;

        -- 1. Hitung Kapasitas Total Otomatis
        DECLARE @kapasitas_total INT;
        SET @kapasitas_total = @jumlah_baris * @jumlah_kolom_per_baris;

        -- 2. Buat Studio Baru (tanpa tipe_studio)
        DECLARE @new_studio_id BIGINT;

        INSERT INTO studios (id_cabang, nama_studio, kapasitas, created_at, updated_at)
        VALUES (@id_cabang, @nama_studio, @kapasitas_total, GETDATE(), GETDATE());

        SET @new_studio_id = SCOPE_IDENTITY();

        -- 3. Generate Seat Map (Looping Nested)
        DECLARE @i INT = 1; -- Counter Baris
        DECLARE @j INT = 1; -- Counter Kolom
        DECLARE @row_char CHAR(1); -- Huruf Baris (A, B, C...)
        DECLARE @seat_code NVARCHAR(10); -- Kode Kursi (A1, A10)

        -- Loop Baris (Row)
        WHILE @i <= @jumlah_baris
        BEGIN
            -- Konversi Angka ke Huruf ASCII (65 = A, 66 = B, dst)
            -- Jika baris > 26 (Z), logika ini perlu disesuaikan (misal jadi AA), 
            -- tapi untuk bioskop standar A-Z (26 baris) sudah sangat cukup.
            SET @row_char = CHAR(64 + @i); 
            
            SET @j = 1; -- Reset kolom setiap ganti baris

            -- Loop Kolom (Column)
            WHILE @j <= @jumlah_kolom_per_baris
            BEGIN
                -- Buat Seat Code (Misal: A + 1 = A1)
                SET @seat_code = @row_char + CAST(@j AS NVARCHAR(5));

                -- Insert ke seat_maps
                INSERT INTO seat_maps (id_studio, seat_code, no_baris, no_kolom, updated_at)
                VALUES (
                    @new_studio_id, 
                    @seat_code, 
                    @row_char,          -- Kolom no_baris diisi Huruf (A)
                    CAST(@j AS NVARCHAR(5)), -- Kolom no_kolom diisi Angka (1)
                    GETDATE()
                );

                SET @j = @j + 1;
            END

            SET @i = @i + 1;
        END

        COMMIT TRANSACTION;

        -- 4. Return Data Studio Baru
        SELECT 
            @new_studio_id AS id_studio, 
            @nama_studio AS nama_studio, 
            @kapasitas_total AS kapasitas_generated,
            'Berhasil membuat studio dan ' + CAST(@kapasitas_total AS NVARCHAR(10)) + ' kursi.' AS message;

    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK TRANSACTION;
        
        DECLARE @ErrorMessage NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR(@ErrorMessage, 16, 1);
    END CATCH
END;
GO