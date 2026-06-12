# Analisis Implementasi Tugas 3

## Gambaran proses bisnis service

Service Kelola Akun digunakan untuk menyimpan data akun pengguna yang nantinya dapat digunakan oleh service lain dalam sistem. Pada service ini terdapat endpoint `POST /api/v1/accounts` yang digunakan untuk menambahkan atau memperbarui data akun pengguna pada database lokal.

Pada Tugas 3, proses tersebut dikaitkan dengan layanan SSO yang disediakan dosen. Jadi ketika pengguna sudah berhasil login melalui SSO, data yang ada pada token dapat langsung digunakan untuk membuat atau memperbarui akun pada service saya.

---

## Alasan menggunakan Federated SSO pada endpoint POST /api/v1/accounts

Saya memilih endpoint `POST /api/v1/accounts` karena endpoint ini memang digunakan untuk menyimpan data akun pengguna. Oleh karena itu, endpoint ini menjadi tempat yang paling cocok untuk memanfaatkan data pengguna yang diperoleh dari SSO.

Saat request diterima, service akan membaca JWT yang dikirim pada header Authorization. Dari token tersebut saya mengambil informasi seperti nama, email, dan NIM pengguna. Data tersebut kemudian digunakan untuk membuat akun baru atau memperbarui akun yang sudah ada di database.

Dengan cara ini saya tidak perlu membuat fitur login sendiri karena proses autentikasi sudah ditangani oleh layanan SSO pusat.

---

## Alasan mengirim data ke SOAP

Setelah data akun berhasil diproses, saya mengirimkan informasi tersebut ke layanan SOAP sebagai audit log.

Alasan saya melakukan hal ini adalah karena proses pembuatan atau sinkronisasi akun termasuk aktivitas yang cukup penting pada service. Dengan adanya audit log, aktivitas yang terjadi dapat dicatat dan dilihat kembali jika suatu saat diperlukan.

Pada saat pengujian, layanan SOAP berhasil mengembalikan Receipt Number yang menunjukkan bahwa data audit telah berhasil diterima dan dicatat.

---

## Alasan mengirim event ke RabbitMQ

Selain mencatat aktivitas ke SOAP, service juga mengirimkan event ke RabbitMQ setiap kali proses sinkronisasi akun berhasil dilakukan.

Tujuannya agar service lain dapat mengetahui adanya perubahan data akun tanpa harus terus meminta data ke service saya. Dengan adanya event tersebut, informasi dapat langsung diteruskan melalui message broker sehingga komunikasi antar service menjadi lebih efisien.

Pada implementasi ini event dikirim setelah akun berhasil dibuat atau diperbarui pada database.