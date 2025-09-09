<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Analog Clock',
            'Digital Clock',
            'DP Clock',
            'Neon Clock',
            'Stars Clock',
            'Emoji Clock',
        ];

        foreach ($categories as $cat) {
            $slug = lcfirst(str_replace(' ', '', ucwords($cat)));
            // Example: "Analog Clock" -> "AnalogClock" -> "analogClock"

            Category::updateOrCreate(
                ['name' => $cat],
                ['slug' => $slug]
            );
        }
    }
}
