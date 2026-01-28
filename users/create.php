<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$id         = $_POST['id'];           // Nomor Induk Mahasiswa
$username = $_POST['username']; // Nama lengkap mahasiswa
$email       = $_POST['email'];         // Email mahasiswa
$peran  = $_POST['peran'];    // ID Jurusan mahasiswa
$poin_total = $_POST['poin_total']; // Tanggal lahir mahasiswa
$created_at      = $_POST['created_at'];        // Alamat mahasiswa

try {
    // Mempersiapkan statement SQL untuk menyimpan data baru
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        INSERT INTO users (id, username, email, peran, poin_total, created_at)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$id, $username, $email, $peran, $poin_total, $created_at]);

    // Jika eksekusi berhasil, ambil ID terakhir yang dimasukkan
    $last_id = $conn->lastInsertId();

    // Kirimkan respon sukses beserta data yang disimpan
    echo json_encode([
        "status"  => "success",
        "message" => "Data mahasiswa berhasil ditambahkan",
        "data"    => [
            "id_mahasiswa"  => $last_id,
            "id"           => $id,
            "username"  => $username,
            "email"         => $email,
            "peran"    => $peran,
            "poin_total" => $poin_total,
            "created_at"        => $created_at
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
2. Nama kolom: Ganti 'id', 'username', 'email', 'peran', 'poin_total', 'created_at' sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>