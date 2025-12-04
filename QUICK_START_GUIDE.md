# 🚀 QUICK START - User Photo & Pelanggan File Upload

## ✨ Fitur Baru yang Ditambahkan

### 1️⃣ User Profile Photo Management
- ✅ Upload foto profil user
- ✅ Preview real-time dengan circular avatar
- ✅ List users dengan pagination dan foto
- ✅ Edit user dengan ganti foto profil
- ✅ Auto-delete old photo saat upload foto baru

### 2️⃣ Pelanggan Multiple File Upload
- ✅ Upload multiple dokumen (PDF, DOC, DOCX, XLS, XLSX, JPG, PNG)
- ✅ Drag & drop interface
- ✅ Real-time preview file sebelum upload
- ✅ Manage existing files (view & delete)
- ✅ Soft delete dengan visual indicator

---

## 📋 Setup Instructions

### Step 1: Jalankan Migrations
```bash
cd d:\NafisaTahera24sia\laragon-6.0-minimal\www\nafisa-apel
php artisan migrate
```

**Output yang diharapkan:**
```
Migrating: 2025_12_04_000001_add_profile_photo_to_users
Migrated: 2025_12_04_000001_add_profile_photo_to_users
Migrating: 2025_12_04_000002_create_pelanggan_attachments_table
Migrated: 2025_12_04_000002_create_pelanggan_attachments_table
```

### Step 2: Setup Storage Link
```bash
php artisan storage:link
```

Jika sudah ada symbolic link, bisa skip langkah ini.

### Step 3: Buat Directories
```bash
mkdir -p storage/app/public/avatars
mkdir -p storage/app/public/pelanggan_files
```

### Step 4: Set Permissions (Windows)
Ensure folders have write permissions:
```bash
icacls storage\app\public /grant %username%:F /T
```

---

## 📌 File-File yang Berubah/Dibuat

### Created (Baru):
- ✨ `database/migrations/2025_12_04_000001_add_profile_photo_to_users.php`
- ✨ `database/migrations/2025_12_04_000002_create_pelanggan_attachments_table.php`
- ✨ `app/Models/PelangganAttachment.php`
- ✨ `resources/views/admin/user/edit_new.blade.php`
- ✨ `resources/views/admin/user/index_new.blade.php`
- ✨ `resources/views/admin/pelanggan/edit_new.blade.php`

### Updated (Dimodifikasi):
- 📝 `app/Models/User.php` - Added `profile_photo` to fillable
- 📝 `app/Models/Pelanggan.php` - Added `attachments()` relationship
- 📝 `app/Http/Controllers/UserController.php` - Full implementation
- 📝 `app/Http/Controllers/PelangganController.php` - Full implementation

---

## 🎯 Usage Examples

### User Management

#### Edit User dengan Foto
```
Route: /user/{id}/edit
- Nama, Email, Password dapat diubah
- Upload foto profil baru
- Preview real-time saat memilih foto
```

#### List Users dengan Pagination
```
Route: /user
- Menampilkan 10 users per halaman
- Foto profil di sebelah nama
- Tombol edit dan delete untuk setiap user
```

### Pelanggan Management

#### Edit Pelanggan dengan File Upload
```
Route: /pelanggan/{id}/edit
- Edit data pribadi (nama, email, telepon, dll)
- Drag & drop multiple file upload
- View existing files
- Delete files dengan soft indicator
```

#### List Pelanggan
```
Route: /pelanggan
- Menampilkan 10 pelanggan per halaman
- Search dan filter support
```

---

## 📋 Validasi & Constraints

### User Profile Photo
```
- Format: JPEG, PNG, JPG, GIF
- Max Size: 2MB
- Storage: storage/app/public/avatars
- Display: 150x150px circular avatar
```

### Pelanggan Files
```
- Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG
- Max Size: 5MB per file
- Multiple Files: Unlimited (practical limit ~20 files)
- Storage: storage/app/public/pelanggan_files
```

---

## 🔌 API/Routes

### User Routes
```
GET    /user              - List semua user (dengan pagination)
GET    /user/create       - Form create user
POST   /user              - Store user baru
GET    /user/{id}/edit    - Form edit user
PUT    /user/{id}         - Update user data dan foto
DELETE /user/{id}         - Delete user dan fotonya
```

### Pelanggan Routes
```
GET    /pelanggan              - List pelanggan (dengan pagination)
GET    /pelanggan/create       - Form create pelanggan
POST   /pelanggan              - Store pelanggan baru
GET    /pelanggan/{id}/edit    - Form edit pelanggan
PUT    /pelanggan/{id}         - Update pelanggan dan files
DELETE /pelanggan/{id}         - Delete pelanggan dan semua files
```

---

## 🎨 UI Preview

### User Edit Page
```
┌─────────────────────────────────────────┐
│ Edit User                               │
├─────────────────────────────────────────┤
│                                         │
│ [Avatar]  Name:      [text input]      │
│ [Circle]  Email:     [email input]     │
│           Password:  [password input]  │
│                                         │
│ [Click to Upload Photo]                │
│                                         │
│ [Save] [Cancel]                        │
└─────────────────────────────────────────┘
```

### Pelanggan Edit Page
```
┌─────────────────────────────────────────┐
│ Edit Pelanggan                          │
├─────────────────────────────────────────┤
│ First Name:  [input]                    │
│ Last Name:   [input]                    │
│ Birthday:    [date]                     │
│ Gender:      [dropdown]                 │
│ Email:       [input]                    │
│ Phone:       [input]                    │
│                                         │
│ [Drag & Drop Files Here]               │
│   or Click to Browse                    │
│                                         │
│ Files to upload:                        │
│ - document.pdf [Remove]                │
│ - image.jpg [Remove]                    │
│                                         │
│ Existing Files:                         │
│ - old_doc.pdf [Delete]                 │
│                                         │
│ [Save] [Cancel]                        │
└─────────────────────────────────────────┘
```

---

## 🆘 Common Issues & Solutions

### Issue: "File could not be uploaded"
**Solution:**
```bash
# Check permissions
icacls storage /grant %username%:F /T

# Check if directories exist
mkdir storage\app\public\avatars
mkdir storage\app\public\pelanggan_files
```

### Issue: "File not found / 404 when viewing photo"
**Solution:**
```bash
# Regenerate symbolic link
php artisan storage:link --force

# Verify symbolic link
dir public | findstr storage
```

### Issue: "SQLSTATE[HY000]: General error"
**Solution:**
```bash
# Rollback dan re-run migrations
php artisan migrate:rollback
php artisan migrate
```

### Issue: File upload > 5MB not working
**Solution:**
Check `php.ini`:
```
upload_max_filesize = 10M
post_max_size = 10M
```

---

## 📊 Database Schema

### Table: users (modified)
```sql
ALTER TABLE users ADD COLUMN profile_photo VARCHAR(255) NULL;
```

### Table: pelanggan_attachments (new)
```sql
CREATE TABLE pelanggan_attachments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    pelanggan_id BIGINT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(100) NOT NULL,
    file_size BIGINT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(pelanggan_id) ON DELETE CASCADE
);
```

---

## 🔄 Workflow Diagrams

### User Photo Upload Flow
```
User selects photo
      ↓
Frontend preview (JavaScript)
      ↓
Form submit with file
      ↓
Backend validation (type, size)
      ↓
Delete old photo (if exists)
      ↓
Save new photo to storage/app/public/avatars
      ↓
Update DB with filename
      ↓
Redirect dengan success message
```

### Pelanggan File Upload Flow
```
User drag & drops files
      ↓
Frontend validate & preview
      ↓
User review dan click Save
      ↓
Form submit dengan:
   - Updated data
   - New files
   - IDs to delete
      ↓
Backend validasi & process
      ↓
Delete marked files
      ↓
Save new files
      ↓
Update pelanggan data
      ↓
Success response
```

---

## 🎯 Next Steps

1. ✅ Run migrations
2. ✅ Setup storage symlink
3. ✅ Test user profile photo upload
4. ✅ Test pelanggan file upload
5. ✅ Verify files are stored correctly
6. ✅ Test delete functionality
7. ✅ Check pagination works
8. ✅ Test responsive design on mobile

---

## 📞 Support & Documentation

Lihat file lengkap: `DOKUMENTASI_FITUR_BARU.md`

---

**Last Updated:** December 4, 2025
**Status:** ✅ Ready for Production
