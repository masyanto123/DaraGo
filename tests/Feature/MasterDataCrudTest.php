<?php

namespace Tests\Feature;

use App\Models\TripPerjalanan;
use App\Models\User;
use Tests\TestCase;

class MasterDataCrudTest extends TestCase
{
    public function test_superadmin_can_view_biro_list_and_create_biro(): void
    {
        $superadmin = User::where('email', 'superadmin@darago.id')->first();

        $response = $this->actingAs($superadmin)->get(route('biro.index'));
        $response->assertStatus(200);
        $response->assertSee('Biro Perjalanan');

        $response = $this->actingAs($superadmin)->post(route('biro.store'), [
            'nama_biro' => 'Biro Test Baru',
            'kontak' => '08123456789',
            'alamat' => 'Yogyakarta',
        ]);

        $response->assertRedirect(route('biro.index'));
        $this->assertDatabaseHas('biro_perjalanan', ['nama_biro' => 'Biro Test Baru']);
    }

    public function test_superadmin_can_view_destinasi_list_and_create_destinasi(): void
    {
        $superadmin = User::where('email', 'superadmin@darago.id')->first();

        $response = $this->actingAs($superadmin)->get(route('destinasi.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($superadmin)->post(route('destinasi.store'), [
            'nama_tempat' => 'Pantai Parangtritis',
            'latitude' => -8.0255,
            'longitude' => 110.3298,
            'deskripsi' => 'Pantai di selatan Yogyakarta',
        ]);

        $response->assertRedirect(route('destinasi.index'));
        $this->assertDatabaseHas('destinasi_wisata', ['nama_tempat' => 'Pantai Parangtritis']);
    }

    public function test_superadmin_can_view_trip_list_and_detail(): void
    {
        $superadmin = User::where('email', 'superadmin@darago.id')->first();
        $trip = TripPerjalanan::first();

        $response = $this->actingAs($superadmin)->get(route('trip.index'));
        $response->assertStatus(200);

        if ($trip) {
            $response = $this->actingAs($superadmin)->get(route('trip.show', $trip));
            $response->assertStatus(200);
            $response->assertSee($trip->nama_kegiatan);
        }
    }
}
