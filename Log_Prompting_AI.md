# AI Prompt Log – Tugas 2 Build Your Service

**Nama:** Febriyan  
**Service:** Account Service  
**Framework:** Laravel 11  
**Topik:** REST API, Swagger/OpenAPI, GraphQL, Docker, API Security  

---

# 1. Inisialisasi Service dan Arsitektur REST API

## Prompt
Rancang struktur REST API untuk service Kelola Akun/User pada studi kasus fintech digital loan berbasis Laravel dengan pendekatan enterprise integration.

## Hasil
Menentukan struktur endpoint:
- Collection Endpoint
- Resource Endpoint
- Action Endpoint

Endpoint yang digunakan:
```http
GET /api/v1/accounts
GET /api/v1/accounts/{accountNumber}
POST /api/v1/accounts
```

---

# 2. Desain Skema Database

## Prompt
Buatkan desain tabel accounts untuk kebutuhan account service pada sistem digital banking.

## Hasil
Membuat struktur tabel:
- id
- account_number
- name
- email
- balance
- status
- timestamps

Migration berhasil dijalankan menggunakan Laravel Migration.

---

# 3. Implementasi Routing dan Controller

## Prompt
Implementasikan routing REST API Laravel 11 beserta controller untuk kebutuhan CRUD dasar account service.

## Hasil
Berhasil:
- membuat routes/api.php
- registrasi route API pada bootstrap/app.php
- implementasi controller:
  - index()
  - show()
  - store()

---

# 4. Standardisasi JSON Response

## Prompt
Buatkan standard JSON response untuk kebutuhan integrasi antar service.

## Hasil
Menggunakan format response:
```json
{
  "status": "success",
  "message": "Success message",
  "data": [],
  "meta": {
    "service_name": "Account-Service",
    "api_version": "v1"
  }
}
```

Tujuan:
- konsistensi response
- mempermudah integrasi service
- mempermudah debugging

---

# 5. Implementasi API Key Security

## Prompt
Implementasikan middleware API Key pada Laravel untuk membatasi akses endpoint service.

## Hasil
Membuat:
```php
ApiKeyMiddleware
```

Menggunakan validasi header:
```http
X-IAE-KEY
```

Endpoint hanya dapat diakses apabila request membawa API Key yang valid.

---

# 6. Validasi dan Pengujian Endpoint

## Prompt
Lakukan pengujian endpoint REST API menggunakan Postman beserta validasi status code.

## Hasil
Berhasil melakukan:
- GET Collection
- GET Resource
- POST Action

Status code yang berhasil divalidasi:
- 200 OK
- 201 Created
- 401 Unauthorized

---

# 7. Implementasi Swagger/OpenAPI

## Prompt
Implementasikan dokumentasi REST API menggunakan Swagger/OpenAPI pada Laravel 11.

## Hasil
Berhasil:
- install package L5 Swagger
- generate dokumentasi API
- menambahkan anotasi endpoint
- publish konfigurasi Swagger

Swagger UI berhasil diakses pada:
```text
/api/documentation
```

---

# 8. Troubleshooting OpenAPI Annotation

## Prompt
Analisis dan perbaiki error OpenAPI annotation:
```text
Required @OA\Info() not found
```

## Hasil
Berhasil:
- membuat file Swagger.php
- menambahkan OpenAPI Info
- melakukan regenerate documentation
- memperbaiki duplicate annotation dan path scanning

---

# 9. Dokumentasi Endpoint Swagger

## Prompt
Tambahkan dokumentasi OpenAPI untuk endpoint GET dan POST account service.

## Hasil
Berhasil mendokumentasikan:
- GET /accounts
- GET /accounts/{accountNumber}
- POST /accounts

Termasuk:
- parameter
- request body
- response schema

---

# 10. Implementasi GraphQL

## Prompt
Implementasikan GraphQL pada Laravel untuk account service menggunakan query account collection.

## Hasil
Berhasil:
- install package GraphQL
- membuat GraphQL Query
- membuat GraphQL Type
- registrasi schema GraphQL

Endpoint GraphQL:
```http
POST /graphql
```

---

# 11. Implementasi Query GraphQL

## Prompt
Buatkan query GraphQL untuk mengambil data account dengan fleksibilitas field selection.

## Hasil
Berhasil membuat query:
```graphql
query {
  accounts {
    id
    account_number
    name
    email
    balance
    status
  }
}
```

Client dapat memilih field sesuai kebutuhan.

---

# 12. Pengujian GraphQL

## Prompt
Lakukan pengujian GraphQL endpoint menggunakan Postman.

## Hasil
Berhasil melakukan query GraphQL menggunakan body:
```json
{
  "query": "query { accounts { id account_number name email balance status } }"
}
```

Response berhasil mengembalikan data account dari database.

---

# 13. Implementasi Docker

## Prompt
Lakukan containerization Laravel account service menggunakan Docker dan Docker Compose.

## Hasil
Berhasil membuat:
- Dockerfile
- docker-compose.yml

Container berhasil menjalankan Laravel service menggunakan:
```bash
docker compose up --build
```

---

# 14. Troubleshooting Docker Environment

## Prompt
Analisis dan perbaiki masalah Docker daemon dan container execution pada Windows environment.

## Hasil
Berhasil:
- mengaktifkan Docker Desktop
- menjalankan Docker Engine
- melakukan rebuild container
- memastikan Laravel berjalan di dalam container

---

# 15. Final Validation

## Prompt
Lakukan validasi akhir terhadap seluruh requirement tugas Build Your Service.

## Hasil
Seluruh requirement berhasil dipenuhi:
- REST API berjalan
- Swagger/OpenAPI berjalan
- GraphQL berjalan
- API Key Security berjalan
- Docker berjalan
- JSON response standard berjalan
- Endpoint berhasil diuji menggunakan Postman

---

# Kesimpulan

Berhasil membangun mini enterprise service berbasis Laravel dengan implementasi:
- REST API
- OpenAPI/Swagger Documentation
- GraphQL Query Service
- API Key Authentication
- Docker Containerization
- Enterprise JSON Response Standard

Service berhasil dijalankan, diuji, dan didokumentasikan sesuai requirement Enterprise Application Integration.