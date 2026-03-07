# 📋 Task Management System (Laravel 11 + Vue 3)

Aplikasi manajemen tugas (Task Management) sederhana dengan fitur papan Kanban (To Do, In Progress, Done) dan sistem kategori. Project ini menggunakan Laravel sebagai REST API dan Vue 3 sebagai Frontend (masih tahap project belum jadi sempurna).

## 🚀 Fitur Utama
- **Dashboard Project**: Ringkasan daftar project yang sedang dikerjakan.
- **Kanban Board**: Visualisasi task berdasarkan status (Todo, In Progress, Done).
- **Task Management**: Create, Read, Update, dan Delete (CRUD) task secara real-time.
- **Dynamic Categories**: Pengelompokan task berdasarkan kategori (Work, Personal, Urgent, dll).
- **Responsive Design**: Tampilan bersih dan modern menggunakan Tailwind CSS.

---

## 🛠️ Stack Teknologi
- **Backend**: Laravel 11 (REST API)
- **Frontend**: Vue 3 (Composition API) + Vite
- **Styling**: Tailwind CSS
- **Database**: MySQL
- **API Tool**: Thunder Client / Postman

---

## 📦 Panduan Instalasi

### 1. Setup Backend (Laravel)

# Clone repository
git clone [https://github.com/username/nama-repo.git](https://github.com/username/nama-repo.git)
cd nama-repo

# Install dependencies backend
composer install

# Salin file environment
cp .env.example .env

# Generate app key
php artisan key:generate

# Jalankan migrasi dan isi data awal (User & Categories)
php artisan migrate --seed

# Install dependencies frontend
npm install

# Jalankan server development Vue
npm run dev

#jalankan server laravel
php artisan serve


## 📖 API Documentation

Dokumentasi ini menjelaskan endpoint API yang digunakan untuk sistem **Kanban Task Management**.

---

## 🔐 Authentication

Semua request **kecuali Login** wajib menyertakan header berikut untuk autentikasi.

```
Authorization: Bearer {token_anda}
Accept: application/json
```

---

## 🔑 Authentication Endpoints

Digunakan untuk manajemen sesi pengguna.

| Method | Endpoint | Deskripsi |
|------|------|------|
| POST | `/api/login` | Mendapatkan token akses (Input: email, password) |
| POST | `/api/logout` | Menghapus token aktif dan mengakhiri sesi |

### Login Request Example

```json
{
  "email": "user@example.com",
  "password": "password"
}
```

### Login Response Example

```json
{
  "token": "1|asdkjasdhjkasdhkjasdh"
}
```

---

## 📂 Projects & Categories

Digunakan untuk mengambil data referensi project dan kategori.

| Method | Endpoint | Deskripsi |
|------|------|------|
| GET | `/api/projects` | Mengambil semua daftar project |
| GET | `/api/projects/{id}` | Detail project beserta daftar task di dalamnya |
| GET | `/api/categories` | Mengambil daftar kategori untuk dropdown task |

---

## 📝 Tasks (Kanban Board)

Endpoint utama untuk mengelola tugas pada papan **Kanban**.

| Method | Endpoint | Deskripsi |
|------|------|------|
| POST | `/api/tasks` | Membuat task baru |
| PUT | `/api/tasks/{id}` | Update judul, deskripsi, atau status task |
| DELETE | `/api/tasks/{id}` | Menghapus task secara permanen |

---

## 📦 Request Body (Create / Update Task)

Gunakan format JSON berikut saat melakukan **POST** atau **PUT** request.

```json
{
  "title": "Nama Task Baru",
  "description": "Detail deskripsi pekerjaan",
  "status": "todo",
  "due_date": "2024-12-31",
  "category_id": 1,
  "project_id": 1
}
```

---

## 📌 Status Task

Nilai status yang diterima oleh sistem:

| Status | Deskripsi |
|------|------|
| `todo` | Task belum dikerjakan |
| `in_progress` | Task sedang dikerjakan |
| `done` | Task sudah selesai |

---

## 🛠 Example Headers (Postman / HTTP Client)

```
Authorization: Bearer your_token_here
Accept: application/json
Content-Type: application/json
```

---

## 📬 Example Create Task Request

```
POST /api/tasks
```

```json
{
  "title": "Membuat UI Kanban",
  "description": "Membuat tampilan board menggunakan Vue",
  "status": "todo",
  "due_date": "2024-12-31",
  "category_id": 2,
  "project_id": 1
}
```