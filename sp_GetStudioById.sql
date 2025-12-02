CREATE OR ALTER PROCEDURE sp_GetStudioById
    @id_studio BIGINT
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        s.id_studio,
        s.id_cabang,
        s.nama_studio,
        s.tipe_studio,
        s.kapasitas,
        s.created_at,
        s.updated_at,
        c.nama_cabang,
        c.alamat,
        c.kode_cabang_kota
    FROM studios s
    LEFT JOIN cabangs c ON s.id_cabang = c.id_cabang
    WHERE s.id_studio = @id_studio;
END;
GO