<?php

namespace Tests\Feature;

use App\Models\Karyawan;
use App\Models\PoBus;
use App\Models\SupirPo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KaryawanPoRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_po_account_is_linked_to_po_without_a_driver_record(): void
    {
        $admin = User::factory()->create(['role' => 'superadmin']);
        $po = PoBus::create(['nama_po' => 'PO Nusantara']);

        $response = $this->actingAs($admin)->post(route('karyawan.store'), [
            'name' => 'Admin PO',
            'email' => 'admin.po@example.test',
            'password' => 'password',
            'kategori_personil' => 'driver_po',
            'po_id' => $po->id,
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('karyawan.index'));

        $employee = User::where('email', 'admin.po@example.test')->firstOrFail();
        $this->assertDatabaseHas('karyawan', [
            'user_id' => $employee->id,
            'po_id' => $po->id,
            'supir_po_id' => null,
            'posisi' => 'Admin PO',
        ]);
        $this->assertDatabaseCount('supir_po', 0);
    }

    public function test_po_user_must_select_a_driver_role(): void
    {
        $admin = User::factory()->create(['role' => 'superadmin']);
        $po = PoBus::create(['nama_po' => 'PO Nusantara']);

        $response = $this->actingAs($admin)->from(route('karyawan.create'))->post(route('karyawan.store'), [
            'name' => 'Supir PO',
            'email' => 'supir.po@example.test',
            'password' => 'password',
            'kategori_personil' => 'driver_po',
            'po_id' => $po->id,
            'role' => 'user',
        ]);

        $response->assertRedirect(route('karyawan.create'));
        $response->assertSessionHasErrors('peran_driver');
        $this->assertDatabaseMissing('users', ['email' => 'supir.po@example.test']);
        $this->assertDatabaseCount('supir_po', 0);
    }

    public function test_po_user_is_saved_with_the_selected_driver_role(): void
    {
        $admin = User::factory()->create(['role' => 'superadmin']);
        $po = PoBus::create(['nama_po' => 'PO Nusantara']);

        $response = $this->actingAs($admin)->post(route('karyawan.store'), [
            'name' => 'Supir PO',
            'email' => 'supir.po@example.test',
            'password' => 'password',
            'kategori_personil' => 'driver_po',
            'po_id' => $po->id,
            'peran_driver' => 'Co-Supir',
            'role' => 'user',
        ]);

        $response->assertRedirect(route('karyawan.index'));

        $employee = User::where('email', 'supir.po@example.test')->firstOrFail();
        $supir = SupirPo::where('nama_supir', 'Supir PO')->firstOrFail();
        $this->assertDatabaseHas('supir_po', [
            'po_id' => $po->id,
            'nama_supir' => 'Supir PO',
            'peran' => 'Co-Supir',
        ]);
        $this->assertDatabaseHas('karyawan', [
            'user_id' => $employee->id,
            'po_id' => $po->id,
            'supir_po_id' => $supir->id,
            'posisi' => 'Co-Supir',
        ]);
    }

    public function test_updating_po_driver_to_admin_removes_driver_record(): void
    {
        $admin = User::factory()->create(['role' => 'superadmin']);
        $po = PoBus::create(['nama_po' => 'PO Nusantara']);
        $driver = User::factory()->create(['role' => 'user']);
        $supir = SupirPo::create([
            'po_id' => $po->id,
            'nama_supir' => $driver->name,
            'peran' => 'Supir Utama',
        ]);
        $employee = Karyawan::create([
            'user_id' => $driver->id,
            'po_id' => $po->id,
            'supir_po_id' => $supir->id,
            'posisi' => 'Supir Utama',
        ]);

        $response = $this->actingAs($admin)->put(route('karyawan.update', $employee), [
            'name' => 'Supir PO',
            'email' => $driver->email,
            'kategori_personil' => 'driver_po',
            'po_id' => $po->id,
            'peran_driver' => 'Co-Supir',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('karyawan.index'));
        $this->assertDatabaseMissing('supir_po', ['id' => $supir->id]);
        $this->assertDatabaseHas('karyawan', [
            'id' => $employee->id,
            'po_id' => $po->id,
            'supir_po_id' => null,
            'posisi' => 'Admin PO',
        ]);
    }
}
