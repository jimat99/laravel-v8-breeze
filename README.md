# 🛡️ Laravel Admin Panel with Role-Based Auth & Audit Logs

A production-style Admin Dashboard built with **Laravel 8 + Breeze**, featuring role-based authorization, audit logging, advanced filtering, and styled Excel exports.

---

## 🚀 Features

### 🔐 Authentication (Laravel Breeze)
- Login / Register
- Password hashing
- Session-based authentication
- Clean Blade scaffolding

---

### 👥 Role-Based Authorization
- `admin` and `user` roles
- Middleware-based route protection
- Policy & gate-based authorization
- Non-admin users blocked from admin routes
- Admin-only features (Users & Audit Logs)

---

### 🧑‍💼 User Management (Admin)
- User listing
- Create user
- Edit user
- Soft delete user
- Restore user
- SweetAlert2 confirmation modals
- Success & error alerts
- Role assignment during registration

---

### 📜 Audit Logging System

Tracks important actions such as:
- User creation
- User update
- User deletion
- User restoration
- (Optional) Data exports

Each audit log contains:
- Admin name
- Action performed
- Target user name
- Target user email
- Timestamp

---

### 🔎 Advanced Audit Log Filtering

Audit logs support:

- 📅 Date range filter (`from` / `to`)
- 🔍 Single search input that searches:
  - Admin name
  - Action
  - Target user name
  - Target user email

Clean relational query filtering using `whereHas()`.

---

### 📤 Export System

#### CSV Export
- Streams large datasets safely
- Respects active filters

#### Excel Export (XLSX)

Built using **Laravel Excel (Maatwebsite)**.

Includes:
- Filter-aware exports
- Bold header row
- Centered header text
- Yellow header background
- Auto-width columns
- Frozen header row

---

### 🎨 UI / UX Enhancements

- SweetAlert2 (CDN)
- Confirmation modal before:
  - Delete user
  - Restore user
- Loading state to prevent double submission
- Clean admin layout

---

## 🏗️ Tech Stack

- Laravel 8
- Laravel Breeze
- Blade Templates
- SweetAlert2 (CDN)
- Laravel Excel (Maatwebsite)
- MySQL
- Soft Deletes
- Eloquent Relationships

---

## 🧠 Architecture Overview

### Authentication Flow

Blade Form
→ Auth Routes
→ Auth Controllers (Breeze)
→ User Model
→ Session

### Authorization Flow

Route Middleware
→ Role Check
→ Policy / Gate
→ Controller

### Audit Logging

Admin Action
→ AuditLog::create()
→ Stored with admin_id + target_id
→ Viewable & Filterable
→ Exportable

---

## 📂 Project Structure Highlights

```
app/
├── Models/
│ ├── User.php
│ └── AuditLog.php
│
├── Exports/
│ └── AuditLogsExport.php
│
├── Http/
│ ├── Controllers/
│ │ ├── Admin/
│ │ └── Auth/
│ └── Middleware/
│
resources/views/
├── admin/
│ ├── users/
│ └── audit/
```

---

## ⚙️ Installation

```bash
git clone https://github.com/yourusername/your-repo.git
cd your-repo

composer install
cp .env.example .env
php artisan key:generate

# configure DB in .env

php artisan migrate
php artisan db:seed # optional

php artisan serve
```

---

## 🧪 Admin Access

To create an admin user manually:

```php
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin',
]);
```

---

## 🔒 Security Considerations

- Role-based route protection

- Policy-based authorization

- Soft deletes (no permanent removal)

- Confirmation modals for destructive actions

- Audit trail for sensitive operations

- Filtered exports to avoid accidental data leaks

---

## 📈 What This Project Demonstrates

- Clean Laravel architecture

- Real-world admin panel structure

- Secure role-based authorization

- Relational query filtering

- Professional export handling

- UX-aware backend engineering

- Audit-first mindset

---

## 🛠️ Future Improvements (Roadmap)

- Queue-based async exports

- Permission-based export restriction

- Export audit trail logging

- Dashboard analytics

- Activity heatmap

- API version

---

## 📄 License

- MIT

---

## 💬 Why This Project Matters

This project is not just CRUD.

It demonstrates:

- Authorization awareness

- Data integrity practices

- Secure export handling

- UX-conscious admin tooling

- Scalable backend structure