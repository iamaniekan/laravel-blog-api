# Laravel Blog API

This is a Laravel-powered REST API backend for a blogging platform. It supports authentication via Laravel Sanctum, role-based authorization, post and category management, and a fully testable API layer. It is meant to be consumed by a separate frontend (like React, Vue, or plain JS).

---

## 🧰 Features

- User registration & login via **Sanctum token-based authentication**
- Role-based access: Admins, Authors, and Readers
- Create, update, and delete blog posts (authors only)
- Commenting system (readers & authors)
- Categories & tags for organizing content
- Post status (draft/published) toggle
- Rich content support via WYSIWYG editors (if integrated)
- API-first architecture (no Blade rendering)
- Comprehensive feature and unit tests

---

## 🏗 Project Setup

### 1. Clone the Repository
```bash
git clone https://github.com/YOUR-USERNAME/YOUR-REPO.git
cd YOUR-REPO
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Set Up Environment
```bash
cp .env.example .env
php artisan key:generate
```

Update your database credentials in `.env`:
```dotenv
# Use sqlite
DB_CONNECTION=sqlite

# Use MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_pass
```

### 4. Migrattion
```bash
php artisan migrate
```
---

## 🔐 Authentication (Sanctum Token-Based)

### Register
`POST /api/register`
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "secret",
  "password_confirmation": "secret"
}
```
Returns: user info + API token

### Login
`POST /api/login`
```json
{
  "email": "john@example.com",
  "password": "secret"
}
```
Returns:
```json
{
  "token": "YOUR_SANCTUM_TOKEN",
  "user": { ... }
}
```

### Logout
`POST /api/logout`
> Requires `Authorization: Bearer TOKEN`

---

## 🔒 Protected Routes
You must pass this header to all protected endpoints:
```
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

---

## 📚 API Routes Overview

### Auth
- `POST /api/register`
- `POST /api/login`
- `POST /api/logout` (auth required)
- `GET /api/user` (auth required)

### Posts
- `GET /api/posts` — list all published posts
- `GET /api/posts/{slug}` — view a single post
- `POST /api/posts` — create post (auth required)
- `PUT /api/posts/{slug}` — update post (auth required)
- `DELETE /api/posts/{slug}` — delete post (auth required)

### Categories
- `GET /api/categories`
- `GET /api/categories/{slug}`

---

## 🧪 Running Tests
```bash
php artisan test
```
Feature tests and unit tests are located in `tests/Feature` and `tests/Unit` respectively. All major endpoints are covered.

---

## 🧪 Run Local Server
```bash
php artisan serve
```
The local server will start and can be reached at `http://127.0.0.1:8000`.
---

## 🖼 Frontend Integration Tips
- The backend uses **token-based auth** — store token in localStorage or memory.
- Use Axios or Fetch to attach the token as:
```http
Authorization: Bearer TOKEN_HERE
```
- Fetch posts using: `GET /api/posts`
- Fetch a single post: `GET /api/posts/{slug}`

---

## 🙋 Author
Built with ❤️ by Aniekan Akpan.


