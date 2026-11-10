<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(StaffUsersSeeder::class);

        // 1. Masukkan kategori terlebih dahulu agar category_id valid
        DB::table('categories')->delete();
        DB::table('categories')->insert([
            ['id' => 1, 'nama_kategori' => 'Makanan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_kategori' => 'Snack', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama_kategori' => 'Minuman', 'created_at' => now(), 'updated_at' => now()],
        ]);


        // 2. Kosongkan tabel menus
        DB::table('menus')->delete();


        // 3. Masukkan seluruh daftar menu secara lengkap
        DB::table('menus')->insert([
            // ================= KATEGORI 1: MAKANAN =================
            // Nasi Goreng
            ['category_id' => 1, 'nama_menu' => 'Nasi Goreng Biasa', 'deskripsi' => 'Nasi goreng menu standar kafe.', 'harga' => 16000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Nasi Goreng Ayam', 'deskripsi' => 'Nasi goreng dengan suwiran daging ayam.', 'harga' => 18000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Nasi Goreng Bakso', 'deskripsi' => 'Nasi goreng dengan irisan bakso sapi.', 'harga' => 16000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Nasi Goreng Sosis', 'deskripsi' => 'Nasi goreng dengan potongan sosis pilihan.', 'harga' => 16000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Nasi Goreng Nugget', 'deskripsi' => 'Nasi goreng disajikan bersama topping nugget.', 'harga' => 16000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Nasi Goreng Teri Medan', 'deskripsi' => 'Nasi goreng gurih khas dengan teri medan.', 'harga' => 16000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Nasi Goreng Spesial', 'deskripsi' => 'Nasi goreng porsi komplit spesial.', 'harga' => 23000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],


            // Kwetiau
            ['category_id' => 1, 'nama_menu' => 'Kwetiau Goreng Biasa', 'deskripsi' => 'Kwetiau goreng nikmat standar.', 'harga' => 14000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Kwetiau Goreng Ayam', 'deskripsi' => 'Kwetiau goreng dengan tambahan ayam.', 'harga' => 15000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Kwetiau Goreng Bakso', 'deskripsi' => 'Kwetiau goreng dengan irisan bakso.', 'harga' => 14000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Kwetiau Goreng Sosis', 'deskripsi' => 'Kwetiau goreng dengan sosis.', 'harga' => 14000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Kwetiau Goreng Spesial', 'deskripsi' => 'Kwetiau goreng komplit spesial.', 'harga' => 23000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],


            // Indomie Rebus (Lengkap)
            ['category_id' => 1, 'nama_menu' => 'Indomie Rebus Biasa', 'deskripsi' => 'Indomie rebus kuah standar.', 'harga' => 10000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Indomie Rebus Telor', 'deskripsi' => 'Indomie rebus dengan tambahan telur.', 'harga' => 14000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Indomie Rebus Telor Kornet', 'deskripsi' => 'Indomie rebus dengan telur dan kornet.', 'harga' => 18000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Indomie Rebus Telor Keju', 'deskripsi' => 'Indomie rebus dengan telur dan keju parut.', 'harga' => 18000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Indomie Rebus Spesial (Interjuned)', 'deskripsi' => 'Indomie rebus porsi komplit spesial.', 'harga' => 20000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],


            // Indomie Goreng (Lengkap)
            ['category_id' => 1, 'nama_menu' => 'Indomie Goreng Biasa', 'deskripsi' => 'Indomie goreng standar.', 'harga' => 10000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Indomie Goreng Telor', 'deskripsi' => 'Indomie goreng dengan tambahan telur.', 'harga' => 14000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Indomie Goreng Telor Kornet', 'deskripsi' => 'Indomie goreng dengan telur dan kornet.', 'harga' => 18000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Indomie Goreng Telor Keju', 'deskripsi' => 'Indomie goreng dengan telur dan keju parut.', 'harga' => 18000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'nama_menu' => 'Indomie Goreng Spesial (Interjuned)', 'deskripsi' => 'Indomie goreng porsi komplit spesial.', 'harga' => 20000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],



            // ================= KATEGORI 2: SNACK =================
            ['category_id' => 2, 'nama_menu' => 'Ekstra Telor', 'deskripsi' => 'Tambahan ekstra telor.', 'harga' => 4000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'nama_menu' => 'Cireng', 'deskripsi' => 'Cireng goreng renyah bumbu rujak.', 'harga' => 13000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'nama_menu' => 'Sosis', 'deskripsi' => 'Sosis goreng/bakar.', 'harga' => 15000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'nama_menu' => 'Fish Roll', 'deskripsi' => 'Olahan ikan gulung gurih.', 'harga' => 14000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'nama_menu' => 'Mix Platter Konekty', 'deskripsi' => 'Aneka gorengan mix sosis, kentang, dll.', 'harga' => 25000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'nama_menu' => 'Tahu Baso Goreng', 'deskripsi' => 'Tahu baso digoreng renyah.', 'harga' => 14000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'nama_menu' => 'French Fries', 'deskripsi' => 'Kentang goreng gurih.', 'harga' => 14000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // Pisang Goreng (Lengkap varian)
            ['category_id' => 2, 'nama_menu' => 'Pisang Goreng Original', 'deskripsi' => 'Pisang goreng manis renyah.', 'harga' => 12000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'nama_menu' => 'Pisang Goreng Keju', 'deskripsi' => 'Pisang goreng topping keju parut.', 'harga' => 14000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'nama_menu' => 'Pisang Goreng Coklat', 'deskripsi' => 'Pisang goreng dengan lumuran selai cokelat.', 'harga' => 14000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'nama_menu' => 'Pisang Goreng Coklat Keju', 'deskripsi' => 'Pisang goreng komplit topping cokelat dan keju.', 'harga' => 16000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],

            // Roti Bakar (Lengkap varian)
            ['category_id' => 2, 'nama_menu' => 'Roti Bakar Coklat', 'deskripsi' => 'Roti bakar isi selai cokelat.', 'harga' => 13000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'nama_menu' => 'Roti Bakar Keju', 'deskripsi' => 'Roti bakar tabur keju.', 'harga' => 13000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'nama_menu' => 'Roti Bakar Coklat Keju', 'deskripsi' => 'Roti bakar dengan selai cokelat dan keju.', 'harga' => 15000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'nama_menu' => 'Roti Bakar Strawberry', 'deskripsi' => 'Roti bakar isi selai strawberry.', 'harga' => 13000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],



            // ================= KATEGORI 3: MINUMAN =================
            // Kopi & Espresso based
            ['category_id' => 3, 'nama_menu' => 'Kopi Tubruk', 'deskripsi' => 'Kopi hitam tubruk tradisional.', 'harga' => 13000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'nama_menu' => 'Americano', 'deskripsi' => 'Espresso dengan air panas.', 'harga' => 16000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'nama_menu' => 'Cappuccino', 'deskripsi' => 'Espresso, steamed milk, dan foam tebal.', 'harga' => 20000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'nama_menu' => 'Cafe Latte', 'deskripsi' => 'Espresso dengan susu segar hangat/dingin.', 'harga' => 20000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'nama_menu' => 'Es Kopi Susu Gula Aren', 'deskripsi' => 'Es kopi susu khas dengan manis gula aren.', 'harga' => 25000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // Teh & Non-Kopi
            ['category_id' => 3, 'nama_menu' => 'Es Teh Manis', 'deskripsi' => 'Es teh manis segar.', 'harga' => 6000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'nama_menu' => 'Es Teh Tawar', 'deskripsi' => 'Es teh tawar segar.', 'harga' => 5000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'nama_menu' => 'Es Lemon Tea', 'deskripsi' => 'Teh segar dengan perasan buah lemon.', 'harga' => 13000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'nama_menu' => 'Es Lychee Tea', 'deskripsi' => 'Teh buah dengan buah leci segar.', 'harga' => 16000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'nama_menu' => 'Es Temon (Telang Lemon)', 'deskripsi' => 'Minuman herbal telang lemon segar.', 'harga' => 17000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'nama_menu' => 'Matcha Latte', 'deskripsi' => 'Minuman serbuk teh hijau jepang dengan susu.', 'harga' => 22000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'nama_menu' => 'Chocolate Signature', 'deskripsi' => 'Es cokelat susu manis creamy.', 'harga' => 20000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'nama_menu' => 'Teh Pucuk', 'deskripsi' => 'Teh melati dalam botol kemasan.', 'harga' => 6000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'nama_menu' => 'Mineral Water', 'deskripsi' => 'Air mineral kemasan botol.', 'harga' => 5000, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
