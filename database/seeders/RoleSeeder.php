<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'owner',
                'description' => 'Pemilik bengkel dengan hak akses penuh.',
            ],
            [
                'name' => 'kasir',
                'description' => 'Mengelola POS dan transaksi penjualan.',
            ],
            [
                'name' => 'gudang',
                'description' => 'Mengelola inventaris dan pergerakan stok.',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}