<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            1 => 'https://picsum.photos/200/200?random=1', // Phones & Tablets
            2 => 'https://picsum.photos/200/200?random=2', // Laptops & Computers
            3 => 'https://picsum.photos/200/200?random=3', // Audio & Headphones
            4 => 'https://picsum.photos/200/200?random=4', // Cameras & Photography
            5 => 'https://picsum.photos/200/200?random=5', // Gaming & Consoles
            6 => 'https://picsum.photos/200/200?random=6', // Smart Home
            7 => 'https://picsum.photos/200/200?random=7', // Wearables
            8 => 'https://picsum.photos/200/200?random=8', // Accessories
            9 => 'https://picsum.photos/200/200?random=9', // LAPTOP
        ];

        foreach ($categories as $id => $imageUrl) {
            \App\Models\Category::where('id', $id)->update(['image' => $imageUrl]);
        }
    }
}
