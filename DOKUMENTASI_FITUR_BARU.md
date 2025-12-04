# Dokumentasi Fitur Baru: Profile Photo User dan Multiple File Upload Pelanggan

## 📋 Ringkasan
Implementasi lengkap untuk:
1. **Halaman Edit User dengan Foto Profil** - Upload dan preview foto profil user dengan pagination
2. **Halaman Edit Pelanggan dengan Multiple File Upload** - Upload multiple dokumen untuk data pelanggan

---

## 🔧 Komponen yang Dibuat/Diubah

### 1. Database Migrations

#### `database/migrations/2025_12_04_000001_add_profile_photo_to_users.php`
- Menambahkan kolom `profile_photo` (nullable) ke tabel `users`

#### `database/migrations/2025_12_04_000002_create_pelanggan_attachments_table.php`
- Membuat tabel baru `pelanggan_attachments` dengan struktur:
  - `id` (Primary Key)
  - `pelanggan_id` (Foreign Key ke tabel pelanggan)
  - `file_name` - Nama file asli
  - `file_path` - Path penyimpanan file
  - `file_type` - MIME type file
  - `file_size` - Ukuran file dalam bytes
  - `timestamps` - Created at, Updated at

---

### 2. Models

#### `app/Models/User.php` - Updated
**Perubahan:**
- Menambahkan `'profile_photo'` ke `$fillable` array

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'profile_photo', // NEW
];
```

#### `app/Models/Pelanggan.php` - Updated
**Perubahan:**
- Menambahkan method relationship `attachments()`

```php
public function attachments()
{
    return $this->hasMany(PelangganAttachment::class, 'pelanggan_id', 'pelanggan_id');
}
```

#### `app/Models/PelangganAttachment.php` - New
Model baru untuk mengelola file attachment pelanggan:
- Relationship ke Pelanggan model
- Mass assignment untuk `file_name`, `file_path`, `file_type`, `file_size`

---

### 3. Controllers

#### `app/Http/Controllers/UserController.php` - Updated
**Perubahan:**
- **`index()`** - Menggunakan pagination (10 items per halaman) dengan `paginate(10)`
- **`create()`** - Tetap sama
- **`store()`** - Menambahkan validasi dan file upload untuk foto profil
- **`edit($id)`** - Menampilkan form edit user dengan data dan foto
- **`update($request, $id)`** - Menangani update user dan foto profil baru
- **`destroy($id)`** - Menghapus user dan file foto yang terkait

**Fitur:**
- Validasi file: `image|mimes:jpeg,png,jpg,gif|max:2048`
- Storage path: `public/avatars`
- Automatic deletion of old photo when new one is uploaded

#### `app/Http/Controllers/PelangganController.php` - Updated
**Perubahan:**
- **`index()`** - Tetap sama dengan pagination
- **`create()`** - Tetap sama
- **`store()`** - Menambahkan multiple file upload handling
- **`edit($id)`** - Menampilkan form edit dengan data dan existing files
- **`update($request, $id)`** - Menangani update data dan multiple files
- **`destroy($id)`** - Menghapus pelanggan dan semua file terkait

**Fitur:**
- Multiple file upload
- Validasi file: `mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:5120` (5MB per file)
- Storage path: `public/pelanggan_files`
- Delete existing files functionality
- Private method `storeAttachments()` untuk menangani upload

---

### 4. Views

#### `resources/views/admin/user/edit_new.blade.php` - New
**Fitur:**
- Foto profil dengan preview real-time
- Input fields untuk nama, email, password
- Image upload dengan drag & drop placeholder
- CSS styling untuk avatar preview
- JavaScript untuk preview image sebelum upload
- Error handling dengan alert messages

**Styling:**
- Avatar preview: 150x150px, circular dengan border
- Upload button dengan hover effects
- Form validation error display

#### `resources/views/admin/user/index_new.blade.php` - New
**Fitur:**
- Tabel daftar users dengan pagination
- Menampilkan foto profil di samping nama user
- Action buttons: Edit (warning color) dan Delete (danger color)
- Breadcrumb navigation
- Alert untuk success messages
- Bootstrap pagination dengan 4 links per halaman
- Responsive design untuk mobile

**Kolom Tabel:**
- # (nomor)
- Foto profil
- Nama user
- Email
- Action (Edit/Delete)

#### `resources/views/admin/pelanggan/edit_new.blade.php` - New
**Fitur:**

1. **Data Pribadi Section:**
   - First name, Last name
   - Birthday (date picker)
   - Gender (Male/Female dropdown)
   - Email
   - Phone number

2. **File Upload Section:**
   - Drag & drop area
   - Click to browse
   - Supported formats: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG
   - Max file size: 5MB

3. **File Preview (New Files):**
   - Real-time preview of selected files
   - File size display
   - Remove button for each file

4. **Existing Files:**
   - Display all previously uploaded files
   - Delete buttons with soft delete UI
   - File size display

5. **JavaScript Features:**
   - Drag & drop handling
   - File validation (type & size)
   - Real-time preview update
   - Multiple file management
   - Soft delete marking (grayed out with strikethrough)

---

## 📁 File Storage Structure

```
storage/
├── app/
│   └── public/
│       ├── avatars/              (User profile photos)
│       │   └── [timestamp]_[filename].jpg
│       └── pelanggan_files/       (Pelanggan documents)
│           └── [timestamp]_[filename].pdf
```

---

## 🚀 Cara Menggunakan

### 1. Jalankan Migrations
```bash
php artisan migrate
```

### 2. Setup Symbolic Link (jika belum ada)
```bash
php artisan storage:link
```

### 3. User Management Flow
- **List Users**: Ke `user.index` untuk melihat semua users dengan foto profil
- **Edit User**: Klik tombol edit untuk mengubah data dan foto profil
- **Upload Foto**: Drag & drop atau klik area untuk memilih foto
- **Delete User**: Klik tombol delete (akan menghapus user dan fotonya)

### 4. Pelanggan Management Flow
- **List Pelanggan**: Ke `pelanggan.index` untuk melihat semua data
- **Edit Pelanggan**: Klik tombol edit untuk mengubah data dan file
- **Upload Files**: Drag & drop multiple files atau klik area
- **Delete Files**: Klik delete button pada file existing (soft delete terlebih dahulu)
- **Save**: Submit form akan menyimpan data, file baru, dan menghapus file yang ditandai

---

## ✅ Validasi

### User Photo Upload
- Type: `image` (jpeg, png, jpg, gif)
- Max size: 2MB
- Required: No (nullable)

### Pelanggan Files Upload
- Type: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG
- Max size: 5MB per file
- Required: No (nullable)
- Multiple: Yes

### User/Pelanggan Data
- Email: Unique, required
- Name: Required, max 255 chars
- Password (User only): Min 8 chars, optional on update

---

## 🔐 Security

- File upload divalidasi berdasarkan MIME type dan ukuran
- Files disimpan di `storage/app/public` (bukan di root)
- Old files dihapus secara otomatis ketika di-update
- Symbolic link diperlukan untuk mengakses files melalui URL

---

## 📝 Route Updates

Pastikan routes sudah mendukung:
```php
Route::resource('user', UserController::class);
Route::resource('pelanggan', PelangganController::class);
```

---

## 🐛 Troubleshooting

### File tidak dapat diupload
- Pastikan folder `storage/app/public/avatars` dan `storage/app/public/pelanggan_files` writable
- Jalankan `php artisan storage:link` jika symbolic link tidak ada

### Foto/File tidak tampil
- Pastikan public disk dikonfigurasi dengan benar di `config/filesystems.php`
- Verifikasi symbolic link dengan `ls -la public/storage`

### Validation error
- Pastikan mime types didukung
- Periksa ukuran file tidak melebihi limit

---

## 📦 Dependencies
- Laravel 11 (atau sesuai project)
- Bootstrap 5 (untuk styling)
- PHP 8.1+ (untuk syntax)

---

## 🎨 UI/UX Improvements
- Circular avatar preview untuk users
- Drag & drop file upload interface
- Real-time file preview sebelum upload
- Visual feedback untuk deleted files
- Responsive design untuk semua ukuran layar
- Clear error messages dan validations

---

Generated: December 4, 2025
