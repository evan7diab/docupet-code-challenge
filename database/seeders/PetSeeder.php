<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dog = Type::firstOrCreate(['name' => 'Dog']);
        $cat = Type::firstOrCreate(['name' => 'Cat']);

        $labrador = Breed::where('name', 'Labrador Retriever')->first();
        $golden = Breed::where('name', 'Golden Retriever')->first();
        $pitBull = Breed::where('name', 'Pit Bull Terrier')->first();
        $beagle = Breed::where('name', 'Beagle')->first();
        $siamese = Breed::where('name', 'Siamese')->first();
        $persian = Breed::where('name', 'Persian')->first();

        $pets = [
            [
                'type_id' => $dog->id,
                'name' => 'Buddy',
                'sex' => 'male',
                'breed_id' => $labrador?->id,
                'breed_text' => null,
                'breed_unknown' => false,
                'dob' => Carbon::now()->subYears(3),
                'approx_age_years' => null,
                'is_dangerous' => false,
            ],
            [
                'type_id' => $dog->id,
                'name' => 'Max',
                'sex' => 'male',
                'breed_id' => $golden?->id,
                'breed_text' => null,
                'breed_unknown' => false,
                'dob' => null,
                'approx_age_years' => 5,
                'is_dangerous' => false,
            ],
            [
                'type_id' => $dog->id,
                'name' => 'Rocky',
                'sex' => 'male',
                'breed_id' => $pitBull?->id,
                'breed_text' => null,
                'breed_unknown' => false,
                'dob' => Carbon::now()->subYears(2),
                'approx_age_years' => null,
                'is_dangerous' => true,
            ],
            [
                'type_id' => $dog->id,
                'name' => 'Snoopy',
                'sex' => 'male',
                'breed_id' => $beagle?->id,
                'breed_text' => null,
                'breed_unknown' => false,
                'dob' => Carbon::now()->subMonths(18),
                'approx_age_years' => null,
                'is_dangerous' => false,
            ],
            [
                'type_id' => $dog->id,
                'name' => 'Lady',
                'sex' => 'female',
                'breed_id' => null,
                'breed_text' => 'Labrador and Shepherd',
                'breed_unknown' => false,
                'dob' => Carbon::now()->subYears(4),
                'approx_age_years' => null,
                'is_dangerous' => false,
            ],
            [
                'type_id' => $dog->id,
                'name' => 'Shadow',
                'sex' => 'male',
                'breed_id' => null,
                'breed_text' => null,
                'breed_unknown' => true,
                'dob' => null,
                'approx_age_years' => 2,
                'is_dangerous' => false,
            ],
            [
                'type_id' => $cat->id,
                'name' => 'Whiskers',
                'sex' => 'male',
                'breed_id' => $siamese?->id,
                'breed_text' => null,
                'breed_unknown' => false,
                'dob' => Carbon::now()->subYears(1),
                'approx_age_years' => null,
                'is_dangerous' => false,
            ],
            [
                'type_id' => $cat->id,
                'name' => 'Luna',
                'sex' => 'female',
                'breed_id' => $persian?->id,
                'breed_text' => null,
                'breed_unknown' => false,
                'dob' => Carbon::now()->subMonths(8),
                'approx_age_years' => null,
                'is_dangerous' => false,
            ],
            [
                'type_id' => $cat->id,
                'name' => 'Mittens',
                'sex' => 'female',
                'breed_id' => null,
                'breed_text' => null,
                'breed_unknown' => true,
                'dob' => null,
                'approx_age_years' => 3,
                'is_dangerous' => false,
            ],
        ];

        foreach ($pets as $petData) {
            Pet::updateOrCreate(
                [
                    'name' => $petData['name'],
                    'type_id' => $petData['type_id'],
                ],
                $petData
            );
        }
    }
}
