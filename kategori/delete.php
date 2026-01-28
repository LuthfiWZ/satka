<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil ID dari form POST untuk mengetahui record mana yang akan dihapus
$id = $_POST['id'];

try {
    // Mempersiapkan statement SQL untuk menghapus data
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("DELETE FROM kategori WHERE nama_kategori = ?");

    // Eksekusi statement dengan parameter
    $stmt->execute([$id]);

    // Jika eksekusi berhasil, kirimkan respon sukses
    echo json_encode([
        "status"  => "success",
        "message" => "Data kategori berhasil dihapus"
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
1. Nama tabel: Ganti 'kategori' dengan nama tabel Anda
2. Nama kolom: Ganti 'id' sesuai dengan kolom primary key di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>