<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$id   = $_POST['id'];          // ID untuk mengetahui record mana yang akan diupdate
$nama_game = $_POST['nama_game']; // Kode jurusan
$deskripsi = $_POST['deskripsi']; // Nama jurusan

try {
    // Mempersiapkan statement SQL untuk mengupdate data
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        UPDATE game
        SET nama_game = ?, deskripsi = ?
        WHERE id = ?
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$nama_game, $deskripsi, $id]);

    // Jika eksekusi berhasil, kirimkan respon sukses
    echo json_encode([
        "status"  => "success",
        "message" => "Data jurusan berhasil diperbarui",
        "data"    => [
            "id"   => $id,
            "nama_game" => $nama_game,
            "deskripsi" => $deskripsi
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
2. Nama kolom: Ganti 'id', 'nama_game', 'deskripsi' sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>