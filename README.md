# 📌 Corkboard

Corkboard is a fullstack web application that combines a **Laravel 11 API backend** and a **Quasar (Vue 3) frontend**. It features secure token-based authentication using Laravel Passport and a clean UI with Quasar and Tailwind CSS.

---

## 🧱 Project Structure

corkboard/
├── corkboard-be/       # Laravel Backend
│   └── .env
├── corkboard-fe/       # Quasar Frontend
│   └── .env
├── .gitignore
└── README.md

---

## 🏗️ Tech Stack

### 🖥 Backend – Laravel 11
- Laravel Passport (OAuth2)
- REST API
- MySQL/PostgreSQL
- Artisan CLI, Migrations, Seeders

### 💻 Frontend – Quasar Framework (Vue 3)
- Vue Router
- Axios for API calls
- Tailwind CSS
- Composition API

---

## ⚙️ Setup Instructions

### 🧩 1. Backend – Laravel (corkboard-be)

```bash
cd corkboard-be
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan passport:install
php artisan serve
```

Make sure you set up your `.env` database configuration before running migrations.

---

### 🧩 2. Frontend – Quasar (corkboard-fe)

```bash
cd corkboard-fe
npm install
quasar dev
```

Update `axios` base URL in your frontend (if needed) to point to your Laravel API:
```js
baseURL: 'http://localhost:8000/api'
```

---

## 🔐 Authentication

- Laravel Passport provides token-based API authentication.
- Quasar frontend stores token in `localStorage`.
- Axios interceptor handles 401 Unauthorized errors by redirecting to `/login`.

---

## ✅ Features

- Login & Logout (secure token-based)
- Blog listing and details
- Protected frontend routes
- Elegant Quasar UI components
- Laravel-powered API with auth

---

## 📦 Git Setup

To initialize the project in Git:

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/your-username/your-repo.git
git push -u origin main
```

---

## 📄 License

This project is open-source and free to use. Customize it as needed!

---
