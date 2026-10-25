<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ItemApiTest extends TestCase
{
    use RefreshDatabase;

    private array $valid = [
        'name' => 'Kopi Toraja 250g',
        'description' => 'Titipan dari Toraja',
        'price' => 90000,
        'stock' => 10,
        'category' => 'Makanan',
        'origin_city' => 'Toraja',
    ];

    public function test_login_berhasil_mengembalikan_token(): void
    {
        User::factory()->create(['email' => 'uji@titipin.test', 'password' => 'rahasia123']);

        $this->postJson('/api/login', ['email' => 'uji@titipin.test', 'password' => 'rahasia123'])
            ->assertStatus(200)
            ->assertJsonStructure(['token', 'user']);
    }

    public function test_login_password_salah_mengembalikan_401(): void
    {
        User::factory()->create(['email' => 'uji@titipin.test', 'password' => 'rahasia123']);

        $this->postJson('/api/login', ['email' => 'uji@titipin.test', 'password' => 'salah'])
            ->assertStatus(401);
    }

    public function test_endpoint_items_tanpa_token_mengembalikan_401(): void
    {
        $this->getJson('/api/items')->assertStatus(401);
    }

    public function test_tambah_barang_mengembalikan_201(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/items', $this->valid)
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'Kopi Toraja 250g');

        $this->assertDatabaseHas('items', ['name' => 'Kopi Toraja 250g', 'status' => 'available']);
    }

    public function test_tambah_barang_data_tidak_valid_mengembalikan_422(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/items', ['name' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'stock', 'category', 'origin_city']);
    }

    public function test_ubah_dan_hapus_barang(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $item = Item::create($this->valid + ['status' => 'available']);

        $this->putJson("/api/items/{$item->id}", ['price' => 95000])
            ->assertStatus(200)
            ->assertJsonPath('data.price', 95000);

        $this->deleteJson("/api/items/{$item->id}")->assertStatus(200);
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }
}
