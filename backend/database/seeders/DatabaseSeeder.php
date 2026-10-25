<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@titipin.test'],
            ['name' => 'Admin TitipIn', 'password' => 'password123']
        );

        $rows = [
            ['Matcha Kit Kat Kyoto (isi 12)', 'Oleh-oleh khas Kyoto, rasa matcha.', 185000, 20, 'Makanan', 'Kyoto', 'available'],
            ['Tokyo Banana Original (8 pcs)', 'Cake pisang lembut khas Tokyo.', 120000, 15, 'Makanan', 'Tokyo', 'available'],
            ['Hada Labo Gokujyun Lotion', 'Lotion pelembap, dibeli langsung di Osaka.', 135000, 8, 'Kecantikan', 'Osaka', 'available'],
            ['Sepatu Onitsuka Tiger Mexico 66', 'Ukuran 41-43, original.', 1450000, 3, 'Fashion', 'Tokyo', 'available'],
            ['Kopi Gayo Premium 250g', 'Titipan dari Takengon, Aceh.', 95000, 30, 'Makanan', 'Takengon', 'available'],
            ['Batik Tulis Pekalongan', 'Batik tulis halus, bahan katun primisima.', 450000, 5, 'Fashion', 'Pekalongan', 'available'],
            ['Bakpia Pathok 25 (isi 20)', 'Oleh-oleh Jogja, fresh dari toko.', 55000, 50, 'Makanan', 'Yogyakarta', 'available'],
            ['Pempek Palembang Frozen 1kg', 'Pempek kapal selam + kuah cuko.', 140000, 12, 'Makanan', 'Palembang', 'available'],
            ['Tas Longchamp Le Pliage', 'Dibeli di Changi Airport, Singapore.', 1650000, 2, 'Fashion', 'Singapore', 'available'],
            ['Pocky Strawberry Korea (10 box)', 'Edisi Korea, bonus kemasan.', 98000, 25, 'Makanan', 'Seoul', 'available'],
            ['Dodol Garut Premium 500g', 'Aneka rasa, kemasan rapi.', 45000, 40, 'Makanan', 'Garut', 'available'],
            ['Jam Casio Vintage A168', 'Titipan dari Hong Kong.', 520000, 0, 'Aksesoris', 'Hong Kong', 'sold'],
        ];

        foreach ($rows as $i => [$name, $desc, $price, $stock, $cat, $city, $status]) {
            Item::create([
                'name' => $name, 'description' => $desc, 'price' => $price,
                'stock' => $stock, 'category' => $cat, 'origin_city' => $city,
                'status' => $status,
                'image_url' => 'https://picsum.photos/seed/titipin'.($i + 1).'/400/400',
            ]);
        }
    }
}
