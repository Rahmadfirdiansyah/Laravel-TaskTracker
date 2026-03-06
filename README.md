# 📋 Task Management System (Laravel 11 + Vue 3)

Aplikasi manajemen tugas (Task Management) sederhana dengan fitur papan Kanban (To Do, In Progress, Done) dan sistem kategori. Project ini menggunakan Laravel sebagai REST API dan Vue 3 sebagai Frontend.

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
```bash
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

# jalankan server laravel
php artisan serve

