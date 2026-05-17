# SIPANDU - Main Flow Documentation

## Table of Contents
1. [User Registration Flow](#1-user-registration-flow)
2. [Login Flow](#2-login-flow)
3. [User/Parent Features](#3-userparent-features)
4. [Admin Features](#4-admin-features)

---

## 1. User Registration Flow

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│   Home Page     │────▶│  Register Page  │────▶│  Fill Form      │────▶│ Login Page      │
│   /            │     │  /register      │     │  Validation    │     │  /login         │
└─────────────────┘     └─────────────────┘     └─────────────────┘     └─────────────────┘
                                                        │
                                                        ▼
                                                ┌─────────────────┐
                                                │  Create User    │
                                                │  (role:        │
                                                │   'orang_tua') │
                                                └─────────────────┘
```

### Registration Details:
- **URL:** `/register`
- **Controller:** `AuthController@showRegister`
- **Required Fields:**
  - `name` (string, unique)
  - `phone` (string)
  - `address` (string)
  - `rt` (string)
  - `rw` (option: 1-6)
  - `password` (min 8 chars, confirmed)
- **Database:** User created with `role = 'orang_tua'`
- **Auto Email:** `user_[timestamp]@posyandu.local` (dummy)

---

## 2. Login Flow

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│   Login Page    │────▶│  Select Role   │────▶│  Validate       │
│   /login        │     │  (Orang Tua/     │     │  Credentials    │
│                 │     │   Admin)        │     │                 │
└─────────────────┘     └─────────────────┘     └─────────────────┘
                                │                            │
                ┌───────────────┴───────────────┐            │
                ▼                             ▼            ▼
        ┌─────────────────┐           ┌─────────────────┐
        │  Orang Tua      │           │  Admin          │
        │  Login         │           │  Login          │
        └────────┬────────┘           └────────┬────────┘
                 │                             │
                 ▼                             ▼
        ┌─────────────────┐           ┌─────────────────┐
        │  Dashboard      │           │  Admin          │
        │  /dashboard    │           │  Dashboard      │
        │                │           │  /admin/dashboard
        └─────────���───────┘           └─────────────────┘
```

### Login Details:

#### A. Orang Tua (Parent) Login
- **URL:** POST `/login`
- **Fields Required:**
  - `name` (User's name)
  - `rw` (RW number: 1-6)
  - `password`
- **Validation:** Checks name + rw + password match
- **Redirect:** `/dashboard`

#### B. Admin Login
- **URL:** POST `/login`
- **Fields Required:**
  - `email` (Admin email)
  - `password`
- **Validation:** Checks email and password, then verify `role === 'admin'`
- **Redirect:** `/admin/dashboard`

---

## 3. User/Parent Features

### 3.1 Dashboard (User)
- **URL:** `/dashboard` or `/dashboard/index`
- **Controller:** `DashboardController@index`
- **Features:**
  - Welcome message with user name
  - Recent schedules (latest 10)
  - Statistics: upcoming schedules, registered children, health records, RW
  - Activity reports (pregnancy checks, vitamins, immunization)
  - Quick links to children data

### 3.2 Children Management
- **URLs:**
  - List: `/children` or `/children/index`
  - Create: `/children/create`
  - Edit: `/children/{id}/edit`
- **Controller:** `ChildController`
- **CRUD Operations:**
  - `index()` - List user's children
  - `create()` - Show create form
  - `store(Request)` - Save new child
  - `edit($id)` - Show edit form
  - `update(Request, $id)` - Update child data
  - `destroy($id)` - Delete child

### 3.3 Mother Management
- **URLs:**
  - List: `/mothers`
  - Create: `/mothers/create`
  - Edit: `/mothers/{id}/edit`
- **Controller:** `MotherController`
- **CRUD Operations:** Same as children

### 3.4 KMS (Kartu Menuju Sehat)
- **URL:** `/dashboard/kms`
- **Controller:** `DashboardController@kms`
- **Features:**
  - View children health records
  - View mother pregnancy records
  - Track growth measurements

### 3.5 Information Pages
- **URLs:**
  - Combined: `/dashboard/informasi`
  - Children: `/dashboard/informasi/anak`
  - Mothers: `/dashboard/informasi/ibu`
- **Controller:** `DashboardController`
- **Features:**
  - View children data with posyandu info
  - View mothers data with posyandu info

### 3.6 Status Updates (AJAX/API)
- **URLs:**
  - `/dashboard/update-imunisasi` (POST)
  - `/dashboard/update-vitamin` (POST)
  - `/dashboard/update-tt` (POST) - Tetanus Toxoid
  - `/dashboard/update-trimester` (POST)
- **Controller:** `DashboardController`
- **Features:**
  - Update immunization status (JSON stored)
  - Update vitamin status (JSON stored)
  - Update TT status for mothers (JSON stored)
  - Update trimester status (JSON stored)

### 3.7 Kader Information
- **URL:** `/dashboard/kader`
- **Controller:** `DashboardController@kader`
- **Features:**
  - View all kader data
  - View posyandu information

### 3.8 Articles
- **URL:** `/dashboard/artikel`
- **Controller:** `DashboardController@artikel`
- **Features:**
  - View informative articles:
    - Tahapan Gizi Bayi
    - Gizi Seimbang untuk Keluarga
    - Jadwal Imunisasi Anak

---

## 4. Admin Features

### 4.1 Admin Dashboard
- **URL:** `/admin/dashboard`
- **Controller:** `AdminController@dashboard`
- **Features:**
  - Statistics overview:
    - Total children
    - Total schedules
    - Total posyandu
    - Total KMS/health records
  - Recent schedules list
  - Recent children list
  - Quick action links

### 4.2 Schedule Management (Jadwal)
- **URLs:**
  - List: `/admin/jadwal`
  - Create: POST `/admin/jadwal`
  - Update: PUT `/admin/jadwal/{schedule}`
  - Delete: DELETE `/admin/jadwal/{schedule}`
- **Controller:** `AdminController`
  - `jadwal()` - List with search
  - `jadwalStore()` - Create new
  - `jadwalUpdate()` - Update existing
  - `jadwalDestroy()` - Delete
- **Fields:**
  - `kegiatan` (activity name)
  - `tanggal` (date)
  - `lokasi` (location)
  - `deskripsi` (description)
  - `posyandu_id` (optional)

### 4.3 Information Search
- **URL:** `/admin/informasi`
- **Controller:** `AdminController@informasi`
- **Features:**
  - Search children by name/NIK
  - Search mothers by name/NIK
  - Filter by RW
  - View combined results

### 4.4 KMS Analytics
- **URL:** `/admin/kms`
- **Controller:** `AdminController@kmsAnalytics`
- **Features:**
  - Filter by posyandu
  - Statistics:
    - Total children
    - Normal weight
    - Underweight
    - Stunting
  - Growth analysis

### 4.5 Kader Management
- **URLs:**
  - List: `/admin/kader`
  - Create: POST `/admin/kader`
  - Update: PUT `/admin/kader/{kader}`
  - Update Status: PATCH `/admin/kader/{kader}/status`
  - Delete: DELETE `/admin/kader/{kader}`
- **Controller:** `AdminController`
  - `kader()` - List with search
  - `kaderStore()` - Create new
  - `kaderUpdate()` - Update
  - `kaderUpdateStatus()` - Update attendance
  - `kaderDestroy()` - Delete
- **Fields:**
  - `nama_kader` (kader name)
  - `posyandu_id` (required)
  - `alamat` (address)
  - `rw` (RW number)
  - `status_kehadiran` (hadir/tidak_hadir)

---

## Database Models Relationship

```
┌─────────────────┐       ┌─────────────────┐
│     User       │       │   Posyandu     │
│ (Orang Tua/    │──────▶│               │
│  Admin)       │       │               │
└────────┬──────┘       └────────┬──────┘
         │                        │
         │ 1:N                    │ 1:N
         ▼                        ▼
┌─────────────────┐       ┌─────────────────┐
│    Child       │       │    Kader       │
│                │◀───── │               │
└─────────────────┘       └─────────────────┘
         │
         │ 1:N
         ▼
┌─────────────────┐
│  HealthRecord  │
│    (KMS)       │
└─────────────────┘

┌─────────────────┐       ┌─────────────────┐
│    Mother      │       │   Schedule     │
│                │       │               │
└─────────────────┘       └─────────────────┘
```

### Models:
1. **User** - Parent/Admin accounts
2. **Child** - Children data (belongs to User, Posyandu)
3. **Mother** - Mothers data (belongs to User)
4. **Posyandu** - Posyandu locations
5. **Kader** - Cadre data (belongs to Posyandu)
6. **HealthRecord** - KMS records (belongs to Child)
7. **Schedule** - Activity schedules

---

## API Endpoints (Mobile App)

| Endpoint | Method | Description |
|-----------|--------|-------------|
| `/api/auth/login` | POST | API Login |
| `/api/auth/register` | POST | API Register |
| `/api/auth/me` | GET | Get Current User |
| `/api/auth/logout` | POST | Logout |
| `/api/children` | GET/POST | Children CRUD |
| `/api/children/{id}` | GET/PUT/DELETE | Child Detail |
| `/api/mothers` | GET/POST | Mothers CRUD |
| `/api/schedules` | GET/POST | Schedules CRUD |
| `/api/kms/child/{id}` | GET/POST | Child Growth |
| `/api/kms/mother/{id}` | GET | Pregnancy Records |
| `/api/analytics/posyandu/{id}` | GET | KMS Analytics |

---

## Route Summary

### Public Routes
- `GET /` - Home page (redirects to dashboard if logged in)
- `GET /login` - Login page
- `POST /login` - Login process
- `GET /register` - Registration page
- `POST /register` - Registration process

### Protected Routes (User)
- `/dashboard` - User dashboard
- `/dashboard/informasi` - Information
- `/dashboard/kms` - KMS
- `/dashboard/kader` - Kader
- `/dashboard/artikel` - Articles
- `/children/*` - Children CRUD
- `/mothers/*` - Mothers CRUD

### Admin Routes
- `/admin/dashboard` - Admin dashboard
- `/admin/jadwal` - Schedule management
- `/admin/informasi` - Search information
- `/admin/kms` - KMS analytics
- `/admin/kader` - Kader management

---

*Document Version: 1.0*
*Generated:SIPANDU Project*
