<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$nama_game         = $_POST['nama_game'];           // Nomor Induk Mahasiswa
$deskripsi = $_POST['deskripsi']; // Nama lengkap mahasiswa
$tipe_game       = $_POST['tipe_game'];         // Email mahasiswa
$url_game  = $_POST['url_game'];    // ID Jurusan mahasiswa
 // Tanggal lahir mahasiswa
      // Alamat mahasiswa

try {
    // Mempersiapkan statement SQL untuk menyimpan data baru
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        INSERT INTO game (nama_game, deskripsi, tipe_game, url_game)
        VALUES (?, ?, ?, ?)
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$nama_game, $deskripsi, $tipe_game, $url_game]);

    // Jika eksekusi berhasil, ambil ID terakhir yang dimasukkan
    $last_id = $conn->lastInsertId();

    // Kirimkan respon sukses beserta data yang disimpan
    echo json_encode([
        "status"  => "success",
        "message" => "Data mahasiswa berhasil ditambahkan",
        "data"    => [
            "id_mahasiswa"  => $last_id,
            "nama_game"           => $nama_game,
            "deskripsi"  => $deskripsi,
            "tipe_game"         => $tipe_game,
            "url_game"    => $url_game
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
1. Nama tabel: Ganti 'mahasiswa' dengan nama tabel Anda
2. Nama kolom: Ganti 'nama_game', 'deskripsi', 'tipe_game', 'url_game', 'tanggal_lahir', 'alamat' sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>