# Prompt Engineering Log

**Nama:** Febriyan Maulana Ibrahim
**NIM:** 102022400310
**Service:** Account Service

---

## Prompt 1 – Menentukan Endpoint yang Sesuai dengan Proses Bisnis

### Tujuan

Menentukan endpoint yang paling sesuai untuk implementasi Federated SSO, SOAP Audit, dan RabbitMQ tanpa mengubah kontrak API yang telah dibuat pada tugas sebelumnya.

### Prompt

> Saya memiliki Account Service dengan endpoint GET /api/v1/accounts, GET /api/v1/accounts/{accountNumber}, dan POST /api/v1/accounts. Berdasarkan requirement Tugas 3 dan proses bisnis service saya, endpoint mana yang paling tepat digunakan untuk integrasi Federated SSO, SOAP, dan RabbitMQ?

### Hasil

Diputuskan menggunakan endpoint `POST /api/v1/accounts` karena endpoint tersebut merupakan titik utama pembuatan atau sinkronisasi data akun dan masih sesuai dengan kontrak API yang telah disepakati sebelumnya.

---

## Prompt 2 – Implementasi Federated SSO

### Tujuan

Memahami cara memanfaatkan JWT yang diperoleh dari layanan SSO dosen untuk kebutuhan autentikasi pada service lokal.

### Prompt

> Bagaimana cara mengintegrasikan Federated SSO pada Laravel menggunakan JWT yang dikirim melalui Authorization Bearer Token?

### Hasil

Diperoleh pemahaman bahwa JWT dapat dibaca melalui middleware, kemudian payload pengguna digunakan sebagai sumber identitas pada service lokal tanpa perlu membuat sistem login sendiri.

---

## Prompt 3 – Implementasi Middleware JWT

### Tujuan

Membuat middleware yang dapat membaca dan memvalidasi JWT sebelum request diproses oleh controller.

### Prompt

> Bagaimana cara membuat middleware Laravel untuk membaca Bearer Token JWT, mengambil payload pengguna, dan meneruskannya ke controller?

### Hasil

Dibuat middleware `VerifyDosenSso` yang membaca JWT dari header Authorization, mengambil payload pengguna, lalu menyimpan informasi tersebut pada request agar dapat digunakan oleh controller.

---

## Prompt 4 – Sinkronisasi Data Akun Lokal

### Tujuan

Menyimpan data pengguna dari JWT ke database lokal tanpa menimbulkan duplikasi data.

### Prompt

> Bagaimana cara menggunakan updateOrCreate() pada Laravel agar data akun diperbarui jika sudah ada dan dibuat jika belum ada?

### Hasil

Digunakan metode `updateOrCreate()` berdasarkan email pengguna sehingga data akun dapat diperbarui maupun dibuat secara otomatis tanpa menghasilkan data ganda.

---

## Prompt 5 – Integrasi SOAP Audit Service

### Tujuan

Mengirimkan audit log ke layanan SOAP yang disediakan oleh dosen.

### Prompt

> Bagaimana cara membuat SOAP XML Envelope dan mengirimkannya menggunakan Laravel HTTP Client?

### Hasil

Dibuat `SoapService` yang membentuk SOAP Envelope dalam format XML, mengirim request ke layanan SOAP, dan menerima Receipt Number sebagai bukti audit berhasil dicatat.

---

## Prompt 6 – Integrasi RabbitMQ Publisher

### Tujuan

Mengirimkan event ke message broker setelah proses sinkronisasi akun berhasil dilakukan.

### Prompt

> Bagaimana cara memperoleh M2M token dan mengirim event JSON ke RabbitMQ melalui endpoint publish yang disediakan?

### Hasil

Dibuat `RabbitMqService` yang mengambil M2M token terlebih dahulu, kemudian mempublish event ke RabbitMQ menggunakan payload JSON.

---

## Prompt 7 – Debugging dan Perbaikan Error

### Tujuan

Menyelesaikan beberapa kendala yang muncul selama implementasi dan pengujian.

### Prompt

> Mengapa terjadi error UNIQUE constraint failed pada tabel accounts saat melakukan sinkronisasi akun dan bagaimana cara memperbaikinya?

### Hasil

Ditemukan bahwa penyimpanan data masih menghasilkan duplikasi pada email pengguna. Implementasi kemudian diperbaiki menggunakan `updateOrCreate()` sehingga data yang sudah ada dapat diperbarui tanpa membuat record baru.

---