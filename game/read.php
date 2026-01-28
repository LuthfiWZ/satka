<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Array untuk menyimpan data hasil query
$data = [];

try {
    // Cek apakah ada parameter GET 'nama_game' atau 'id'
    // Jika ada, maka hanya ambil data spesifik berdasarkan parameter tersebut
    if (isset($_GET['nama_game']) || isset($_GET['id'])) {

        // Jika parameter 'nama_game' disediakan, cari berdasarkan nama_game
        if (isset($_GET['nama_game'])) {
            $nama_game = $_GET['nama_game'];
            // Mempersiapkan statement SQL untuk mencari data mahasiswa beserta jurusan
            $stmt = $conn->prepare("
                SELECT m.*, j.nama_game, j.deskripsi, j.tipe_game
                FROM game m
                LEFT JOIN id_game j ON m.nama_game = j.nama_game
                WHERE m.nama_game = ?
            ");
            // Eksekusi statement dengan parameter
            $stmt->execute([$nama_game]);
        } else {
            // Jika parameter 'id' disediakan, cari berdasarkan id
            $id = $_GET['id'];
            // Mempersiapkan statement SQL untuk mencari data game beserta id_game
            $stmt = $conn->prepare("
                SELECT m.*, j.nama_game, j.deskripsi, j.tipe_game
                FROM game m
                LEFT JOIN id_game j ON m.nama_game = j.nama_game
                WHERE m.nama_game = ?
            ");
            // Eksekusi statement dengan parameter
            $stmt->execute([$id]);
        }

        // Ambil semua hasil query
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {
        // Jika tidak ada parameter GET, ambil semua data game beserta id_game
        $stmt = $conn->prepare("
            SELECT m.*, j.nama_game, j.deskripsi, j.tipe_game
            FROM game m
            LEFT JOIN id_game j ON m.nama_game = j.nama_game
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
1. Nama tabel: Ganti 'mahasiswa' dan 'id_game' dengan nama tabel Anda
2. Nama kolom: Ganti kolom sesuai dengan kolom pencarian di tabel Anda
3. Parameter GET: Sesuaikan dengan nama parameter yang ingin Anda gunakan untuk pencarian
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>