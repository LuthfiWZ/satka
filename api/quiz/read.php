<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Array untuk menyimpan data hasil query
$data = [];

try {
    // Cek apakah ada parameter GET 'kode_jurusan' atau 'id'
    // Jika ada, maka hanya ambil data spesifik berdasarkan parameter tersebut
    if (isset($_GET['pertanyaan']) || isset($_GET['id'])) {

        // Jika parameter 'pertanyaan' disediakan, cari berdasarkan pertanyaan
        if (isset($_GET['pertanyaan'])) {
            $pertanyaan = $_GET['pertanyaan'];
            // Mempersiapkan statement SQL untuk mencari data berdasarkan pertanyaan
            $stmt = $conn->prepare("SELECT * FROM quiz WHERE pertanyaan = ?");
            // Eksekusi statement dengan parameter
            $stmt->execute([$pertanyaan]);
        } else {
            // Jika parameter 'id' disediakan, cari berdasarkan id
            $id = $_GET['id'];
            // Mempersiapkan statement SQL untuk mencari data berdasarkan id
            $stmt = $conn->prepare("SELECT * FROM quiz WHERE satwa_id = ?");
            // Eksekusi statement dengan parameter
            $stmt->execute([$id]);
        }

        // Ambil semua hasil query
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {
        // Jika tidak ada parameter GET, ambil semua data dari tabel
        $stmt = $conn->prepare("SELECT * FROM quiz");
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
1. Nama tabel: Ganti 'quiz' dengan nama tabel Anda
2. Nama kolom: Ganti 'pertanyaan', 'id' sesuai dengan kolom pencarian di tabel Anda
3. Parameter GET: Sesuaikan dengan nama parameter yang ingin Anda gunakan untuk pencarian
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>