<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Array untuk menyimpan data hasil query
$data = [];

try {
    // Cek apakah ada parameter GET '_id' atau 'id'
    // Jika ada, maka hanya ambil data spesifik berdasarkan parameter tersebut
    if (isset($_GET['_id']) || isset($_GET['id'])) {

        // Jika parameter '_id' disediakan, cari berdasarkan _id
        if (isset($_GET['_id'])) {
            $_id = $_GET['_id'];
            // Mempersiapkan statement SQL untuk mencari data mahasiswa beserta kategori_id
            $stmt = $conn->prepare("
                SELECT m.*, j.satwa_id, j.kategori_id, j.kode_jurusan
                FROM satwa_kategori m
                LEFT JOIN kategori_id j ON m.satwa_id = j.satwa_id
                WHERE m._id = ?
            ");
            // Eksekusi statement dengan parameter
            $stmt->execute([$_id]);
        } else {
            // Jika parameter 'id' disediakan, cari berdasarkan id
            $id = $_GET['id'];
            // Mempersiapkan statement SQL untuk mencari data satwa_kategori beserta kategori_id
            $stmt = $conn->prepare("
                SELECT m.*, j.satwa_id, j.kategori_id, j.kode_jurusan
                FROM satwa_kategori m
                LEFT JOIN kategori_id j ON m.satwa_id = j.satwa_id
                WHERE m._id = ?
            ");
            // Eksekusi statement dengan parameter
            $stmt->execute([$id]);
        }

        // Ambil semua hasil query
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {
        // Jika tidak ada parameter GET, ambil semua data satwa_kategori beserta kategori_id
        $stmt = $conn->prepare("
            SELECT m.*, j.satwa_id, j.kategori_id, j.kode_jurusan
            FROM mahasiswa m
            LEFT JOIN kategori_id j ON m.satwa_id = j.satwa_id
        ");
        $stmt->execute();

        // Ambil semua hasil query
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kirimkan data dalam format JSON
    echo json_encode([
        "status"  => "success",
        "message" => count($data) > 0 ? "Data ditemukan" : "Data kosong",
        "data"    => $data
    ]);

} catch(PDOException $e) {
    // Jika eksekusi gagal, kirimkan pesan error
    echo json_encode([
        "status"  => "error",
        "message" => $e->getMessage(),
        "data"    => []
    ]);
}

// Koneksi akan ditutup otomatis saat script selesai
/*
PETUNJUK UNTUK MENYESUAIKAN DENGAN SCHEMA TABEL LAIN:

Jika ingin menggunakan skema tabel yang berbeda, ubah bagian-bagian berikut:
1. Nama tabel: Ganti 'mahasiswa' dan 'kategori_id' dengan nama tabel Anda
2. Nama kolom: Ganti kolom sesuai dengan kolom pencarian di tabel Anda
3. Parameter GET: Sesuaikan dengan nama parameter yang ingin Anda gunakan untuk pencarian
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>