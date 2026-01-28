<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$satwa_id   = $_POST['id'];          // ID untuk mengetahui record mana yang akan diupdate
$kategori_id = $_POST['kategori_id']; // Kode jurusan


try {
    // Mempersiapkan statement SQL untuk mengupdate data
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        UPDATE jurusan
        SET kategori_id = ?
        WHERE satwa_id = ?
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$kategori_id, $satwa_id]);

    // Jika eksekusi berhasil, kirimkan respon sukses
    echo json_encode([
        "status"  => "success",
        "message" => "Data jurusan berhasil diperbarui",
        "data"    => [
            "satwa_id"   => $satwa_id,
            "kategori_id" => $kategori_id,
        ]
    ]);

} catch(PDOException $e) {
    // Jika eksekusi gagal, kirimkan pesan error
    echo json_encode([
        "status"  => "error",
        "message" => $e->getMessage()
    ]);
}

// Koneksi akan ditutup otomatis saat script selesai
/*
PETUNJUK UNTUK MENYESUAIKAN DENGAN SCHEMA TABEL LAIN:

Jika ingin menggunakan skema tabel yang berbeda, ubah bagian-bagian berikut:
1. Nama tabel: Ganti 'jurusan' dengan nama tabel Anda
2. Nama kolom: Ganti 'id', 'kategori_id', 'nama_jurusan' sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>