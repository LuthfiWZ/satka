<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$id   = $_POST['id'];          // ID untuk mengetahui record mana yang akan diupdate
$nama_latin = $_POST['nama_latin']; // Kode jurusan
$nama_umum = $_POST['nama_umum']; // Nama jurusan

try {
    // Mempersiapkan statement SQL untuk mengupdate data
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        UPDATE satwa
        SET nama_latin = ?, nama_umum = ?
        WHERE id = ?
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$nama_latin, $nama_umum, $id]);

    // Jika eksekusi berhasil, kirimkan respon sukses
    echo json_encode([
        "status"  => "success",
        "message" => "Data jurusan berhasil diperbarui",
        "data"    => [
            "id"   => $id,
            "nama_latin" => $nama_latin,
            "nama_umum" => $nama_umum
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
2. Nama kolom: Ganti 'id', 'nama_latin', 'nama_umum' sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>