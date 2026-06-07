# Online Store API

## Setup

1. Clone repo dan install dependencies:
   composer install

2. Copy file environment:
   cp .env.example .env

3. Sesuaikan konfigurasi database di .env, lalu jalankan migrasi dan seeder:
   php artisan migrate --seed

## Menjalankan API

php artisan serve

## Test Race Condition

Pastikan server sudah berjalan, lalu di terminal lain:

php artisan test --filter=RaceConditionTest

## Task 2 - Hidden Item

php task2/hiddenItem.php
