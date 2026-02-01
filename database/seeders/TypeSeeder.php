<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Dog', 'Cat', 'Rabbit', 'Bird', 'Hamster'];

        foreach ($types as $name) {
            Type::firstOrCreate(['name' => $name]);
        }
    }
}
