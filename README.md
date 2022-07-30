# Virtual Internship Experience (Investree) - Fullstack - Ignasius Ferry Priguna

Membangun blog sederhana menggunakan Laravel

## Set Up
1. Clone repo ini
2. Lakukan instalasi dengan composer
    ```
    composer install
    ```
3. Buat basis data baru
4. Buat file .env dengan isi sesuai dengan .env.example dan sesuaikan isinya dengan detail basis data yang telah dibuat
5. Tambahkan teks berikut ke file .env yang telah dibuat
    ```
    API_URL="${APP_URL}/api/V1/"
    ```
6. Jalankan kode berikut di terminal
    ```
    php artisan migrate:fresh
    php artisan passport:install
    ```
    
    atau jalankan kode berikut jika ingin menjalankan seeder
    ```
    php artisan migrate:fresh --seed
    php artisan passport:install
    ```
    
