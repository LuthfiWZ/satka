<?php
// Koneksi ke database menggunakan file db.php
include_once '../../config/db.php';

// Menentukan bahwa respon akan dalam format JSON
header('Content-Type: application/json');

// Mengambil data dari form POST
$satwa_id   = $_POST['satwa_id'];           // Nomor Induk Mahasiswa
$pertanyaan = $_POST['pertanyaan']; // Nama lengkap mahasiswa
$pilihan_a  = $_POST['pilihan_a'];         // Email mahasiswa
$pilihan_b  = $_POST['pilihan_b'];    // ID Jurusan mahasiswa
$pilihan_c  = $_POST['pilihan_c']; // Tanggal lahir mahasiswa
$pilihan_d  = $_POST['pilihan_d'];        // Alamat mahasiswa
$jawaban_benar = $_POST['jawaban_benar'];
$penjelasan = $_POST['penjelasan'];
$tingkat_kesulitan = $_POST['tingkat_kesulitan'];

try {
    // Mempersiapkan statement SQL untuk menyimpan data baru
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("
        INSERT INTO quiz (satwa_id, pertanyaan, pilihan_a, pilihan_b, pilihan_c, pilihan_d, jawaban_benar, penjelasan, tingkat_kesulitan)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    // Eksekusi statement dengan parameter
    $stmt->execute([$satwa_id, $pertanyaan, $pilihan_a, $pilihan_b, $pilihan_c, $pilihan_d, $jawaban_benar, $penjelasan, $tingkat_kesulitan]);

    // Jika eksekusi berhasil, ambil ID terakhir yang dimasukkan
    $last_id = $conn->lastInsertId();

    // Kirimkan respon sukses beserta data yang disimpan
    echo json_encode([
        "status"  => "success",
        "message" => "Data mahasiswa berhasil ditambahkan",
        "data"    => [
            "id_mahasiswa"  => $last_id,
            "satwa_id"           => $satwa_id,
            "pertanyaan"  => $pertanyaan,
            "pilihan_a"         => $pilihan_a,
            "pilihan_b"    => $pilihan_b,
            "pilihan_c" => $pilihan_c,
            "pilihan_d"        => $pilihan_d,
            "jawaban_benar" => $jawaban_benar,
            "penjelasan" => $penjelasan,
            "tingkat_kesulitan" => $tigkat_kesulitan
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
2. Nama kolom: Ganti 'satwa_id', 'pertanyaan', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d' sesuai dengan kolom di tabel Anda
3. Parameter POST: Sesuaikan dengan nama field yang dikirim dari form Anda
4. Tipe data parameter: Tidak perlu lagi karena PDO menangani tipe data secara otomatis
*/
?>