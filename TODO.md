# TASK BARU: Informasi Ibu + KMS Gabungan

## Fitur yang akan ditambahkan:

### 1. Tambah NIK di Informasi Anak
   - Update migration children table: tambah kolom nik
   - Update Model Child: fillable + accessor
   - Update form create/edit anak: input NIK

### 2. Buat CRUD Informasi Ibu (Baru)
   - Migration: buat table ibu (user_id, nik, nama_lengkap, jenis_kelamin (P), tanggal_kehamilan, berat_badan, foto)
   - Model Ibu baru
   - Controller IbuController
   - View informasi-ibu dengan CRUD lengkap + foto profil
   - Route

### 3. KMS untuk Ibu (Baru)
   - Calculate status kesehatan dari berat badan & tanggal kehamilan
   - Diagram: Sehat vs Perlu Pemeriksaan
   - Ambil data dari table ibu

### 4. KMS Gabungan (Update)
   - Fitur pilih: KMS Anak atau KMS Ibu
   - Tampilkan diagram sesuai pilihan

## Langkah Implementasi:

#### Step 1: Migration + Model Anak (NIK)
- Tambah kolom nik di children

#### Step 2: Buat Table Ibu
- Migration: create_mothers_table
- Model Mother
- Controller MotherController (CRUD)

#### Step 3: View CRUD Ibu
- create.blade.php mother
- edit.blade.php mother
- Dashboard informasi-ibu

#### Step 4: KMS Gabungan
- Update kms.blade.php dengan selector
- Logic diagram calculate
- Tampilkan chart untuk anak/ibu

## Status: MULAI IMPLEMENTASI
