<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\Type;
use Illuminate\Database\Seeder;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dog = Type::firstOrCreate(['name' => 'Dog']);
        $cat = Type::firstOrCreate(['name' => 'Cat']);

        $dogBreeds = [
            ['name' => 'Labrador Retriever', 'is_dangerous' => false],
            ['name' => 'Golden Retriever', 'is_dangerous' => false],
            ['name' => 'German Shepherd', 'is_dangerous' => true],
            ['name' => 'Pit Bull Terrier', 'is_dangerous' => true],
            ['name' => 'Rottweiler', 'is_dangerous' => true],
            ['name' => 'Beagle', 'is_dangerous' => false],
            ['name' => 'Bulldog', 'is_dangerous' => false],
            ['name' => 'Poodle', 'is_dangerous' => false],
            ['name' => 'Doberman Pinscher', 'is_dangerous' => true],
            ['name' => 'Corgi', 'is_dangerous' => false],
        ];

        $catBreeds = [
            ['name' => 'Siamese', 'is_dangerous' => false],
            ['name' => 'Persian', 'is_dangerous' => false],
            ['name' => 'Maine Coon', 'is_dangerous' => false],
            ['name' => 'Bengal', 'is_dangerous' => false],
            ['name' => 'British Shorthair', 'is_dangerous' => false],
            ['name' => 'Sphynx', 'is_dangerous' => false],
            ['name' => 'Ragdoll', 'is_dangerous' => false],
            ['name' => 'Scottish Fold', 'is_dangerous' => false],
        ];

        foreach ($dogBreeds as $breed) {
            Breed::firstOrCreate(
                ['name' => $breed['name']],
                ['type_id' => $dog->id, 'is_dangerous' => $breed['is_dangerous']]
            );
        }

        foreach ($catBreeds as $breed) {
            Breed::firstOrCreate(
                ['name' => $breed['name']],
                ['type_id' => $cat->id, 'is_dangerous' => $breed['is_dangerous']]
            );
        }
    }
}
