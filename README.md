# Aplikasi Manajemen Data Mahasiswa

Aplikasi ini adalah sistem manajemen data mahasiswa sederhana yang dibangun dengan PHP dan MySQL. Sistem ini menyediakan fungsi CRUD (Create, Read, Update, Delete) untuk mengelola data mahasiswa.

## Deskripsi

Aplikasi ini menyediakan empat endpoint utama untuk mengelola data mahasiswa:
- **CREATE**: Menambahkan data mahasiswa baru
- **READ**: Membaca/menampilkan data mahasiswa
- **UPDATE**: Memperbarui data mahasiswa yang sudah ada
- **DELETE**: Menghapus data mahasiswa

Semua endpoint mengembalikan respons dalam format JSON.

## Struktur Tabel

Tabel `tb_mahasiswa` memiliki struktur sebagai berikut:

| Kolom     | Tipe Data | Deskripsi             |
|-----------|-----------|------------------------|
| id        | INT       | Primary Key, Auto Increment |
| nim       | VARCHAR   | Nomor Induk Mahasiswa |
| nama      | VARCHAR   | Nama lengkap mahasiswa |
| alamat    | VARCHAR   | Alamat mahasiswa       |
| no_telp   | VARCHAR   | Nomor telepon mahasiswa |

## Endpoint

### 1. CREATE - Menambahkan Data Mahasiswa Baru

**URL**: `/mahasiswa/create.php`

**Metode**: POST

**Parameter**:
- `nim` (string) - Nomor Induk Mahasiswa
- `nama` (string) - Nama lengkap mahasiswa
- `alamat` (string) - Alamat mahasiswa
- `no_telp` (string) - Nomor telepon mahasiswa

**Contoh Request**:
```bash
curl -X POST \
  -d "nim=123456789" \
  -d "nama=John Doe" \
  -d "alamat=Jl. Contoh No. 123" \
  -d "no_telp=081234567890" \
  http://localhost/BE-Latihan-kelas/mahasiswa/create.php
```

**Contoh Respons Sukses**:
```json
{
  "status": "success",
  "message": "Data berhasil ditambahkan",
  "data": {
    "id": 1,
    "nim": "123456789",
    "nama": "John Doe",
    "alamat": "Jl. Contoh No. 123",
    "no_telp": "081234567890"
  }
}
```

**Contoh Respons Error**:
```json
{
  "status": "error",
  "message": "Error message here"
}
```

### 2. READ - Membaca Data Mahasiswa

**URL**: `/mahasiswa/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- `id` (integer) - Untuk mendapatkan data mahasiswa berdasarkan ID
- `nim` (string) - Untuk mendapatkan data mahasiswa berdasarkan NIM

Jika tidak ada parameter, maka akan mengembalikan semua data mahasiswa.

**Contoh Request (Semua Data)**:
```bash
curl http://localhost/BE-Latihan-kelas/mahasiswa/read.php
```

**Contoh Request (Spesifik ID)**:
```bash
curl http://localhost/BE-Latihan-kelas/mahasiswa/read.php?id=1
```

**Contoh Request (Spesifik NIM)**:
```bash
curl http://localhost/BE-Latihan-kelas/mahasiswa/read.php?nim=123456789
```

**Contoh Respons Sukses (Semua Data)**:
```json
{
  "status": "success",
  "message": "Data ditemukan",
  "data": [
    {
      "id": 1,
      "nim": "123456789",
      "nama": "John Doe",
      "alamat": "Jl. Contoh No. 123",
      "no_telp": "081234567890"
    },
    {
      "id": 2,
      "nim": "987654321",
      "nama": "Jane Smith",
      "alamat": "Jl. Contoh No. 456",
      "no_telp": "089876543210"
    }
  ]
}
```

**Contoh Respons Sukses (Data Kosong)**:
```json
{
  "status": "success",
  "message": "Data kosong",
  "data": []
}
```

### 3. UPDATE - Memperbarui Data Mahasiswa

**URL**: `/mahasiswa/update.php`

**Metode**: POST

**Parameter**:
- `id` (integer) - ID mahasiswa yang akan diperbarui
- `nim` (string) - Nomor Induk Mahasiswa baru
- `nama` (string) - Nama lengkap mahasiswa baru
- `alamat` (string) - Alamat mahasiswa baru
- `no_telp` (string) - Nomor telepon mahasiswa baru

**Contoh Request**:
```bash
curl -X POST \
  -d "id=1" \
  -d "nim=123456789" \
  -d "nama=John Updated" \
  -d "alamat=Jl. Updated No. 123" \
  -d "no_telp=081111111111" \
  http://localhost/BE-Latihan-kelas/mahasiswa/update.php
```

**Contoh Respons Sukses**:
```json
{
  "status": "success",
  "message": "Data berhasil diperbarui",
  "data": {
    "id": 1,
    "nim": "123456789",
    "nama": "John Updated",
    "alamat": "Jl. Updated No. 123",
    "no_telp": "081111111111"
  }
}
```

**Contoh Respons Error**:
```json
{
  "status": "error",
  "message": "Error message here"
}
```

### 4. DELETE - Menghapus Data Mahasiswa

**URL**: `/mahasiswa/delete.php`

**Metode**: POST

**Parameter**:
- `id` (integer) - ID mahasiswa yang akan dihapus

**Contoh Request**:
```bash
curl -X POST \
  -d "id=1" \
  http://localhost/BE-Latihan-kelas/mahasiswa/delete.php
```

**Contoh Respons Sukses**:
```json
{
  "status": "success",
  "message": "Data berhasil dihapus"
}
```

**Contoh Respons Error**:
```json
{
  "status": "error",
  "message": "Error message here"
}
```

## Instalasi

1. Pastikan Anda memiliki server web dengan PHP dan MySQL
2. Salin semua file ke direktori web server Anda
3. Buat database MySQL dan import struktur tabel sesuai dengan deskripsi di atas
4. Konfigurasi koneksi database di file `db.php`
5. Akses endpoint sesuai kebutuhan

## Catatan

- Semua endpoint mengembalikan respons dalam format JSON
- Gunakan metode POST untuk CREATE, UPDATE, dan DELETE
- Gunakan metode GET untuk READ
- Gunakan prepared statements untuk mencegah SQL injection
- Pastikan untuk selalu mengecek status respons sebelum memproses data lebih lanjut