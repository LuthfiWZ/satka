<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$id         = $_POST['id'];           // Nomor Induk Mahasiswa
$nama_latin = $_POST['nama_latin']; // Nama lengkap mahasiswa
$nama_umum       = $_POST['nama_umum'];         // Email mahasiswa
$deskripsi  = $_POST['deskripsi'];    // ID Jurusan mahasiswa
$habitat = $_POST['habitat']; // Tanggal lahir mahasiswa
$makanan      = $_POST['makanan'];
$status_konservasi = $_POST['status_konservasi'];
$ancaman = $_POST['ancaman'];
$upaya_konservasi = $_POST['upaya_konservasi'];  
$gambar_url = $_POST['gambar_url'];
$created_at = $_POST['created_at']; 
$updated_at = $_POST['updated_at'];     // Alamat mahasiswa

try {
    // Mempersiapkan statement SQL untuk menyimpan data baru
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        INSERT INTO mahasiswa (id, nama_latin, nama_umum, deskripsi, habitat, makanan, status_konservasi, ancaman, upaya_konservasi, gambar_url, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$id, $nama_latin, $nama_umum, $deskripsi, $habitat, $makanan, $status_konservasi, $makanan, $status_konservasi, $ancaman, $upaya_konservasi, $gambar_url, $created_at, $updated_at]);

    // Jika eksekusi berhasil, ambil ID terakhir yang dimasukkan
    $last_id = $conn->lastInsertId();

    // Kirimkan respon sukses beserta data yang disimpan
    echo json_encode([
        "status"  => "success",
        "message" => "Data mahasiswa berhasil ditambahkan",
        "data"    => [
            "id_mahasiswa"  => $last_id,
            "id"           => $id,
            "nama_latin"  => $nama_latin,
            "nama_umum"         => $nama_umum,
            "deskripsi"    => $deskripsi,
            "habitat" => $habitat,
            "makanan"        => $makanan,
            "status_konservasi" => $status_konservasi,
            "ancaman" => $ancaman,
            "upaya_konservasi" => $upaya_konservasi,
            "gambar_url" => $gambar_url,
            "created_at" => $created_at,
            "updated_at" => $updated_at

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
2. Nama kolom: Ganti 'id', 'nama_latin', 'nama_umum', 'deskripsi', 'habitat', 'makanan' sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>