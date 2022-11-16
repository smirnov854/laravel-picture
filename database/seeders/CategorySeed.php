<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('picture_categories')->insert([
            'name' => 'Металлическая'
        ]);

        DB::table('picture_categories')->insert([
            'name' => 'Диаметр'
        ]);
        DB::table('picture_categories')->insert([
            'name' => 'Расстояние'
        ]);
        DB::table('picture_categories')->insert([
            'name' => 'Треугольная'
        ]);
        DB::table('picture_categories')->insert([
            'name' => 'Квадратная'
        ]);
        DB::table('picture_categories')->insert([
            'name' => 'Круглая'
        ]);
        DB::table('picture_categories')->insert([
            'name' => 'Овальная'
        ]);
        DB::table('picture_categories')->insert([
            'name' => 'Чугунная'
        ]);
        DB::table('picture_categories')->insert([
            'name' => 'Деревенная'
        ]);
        DB::table('picture_categories')->insert([
            'name' => 'Высота'
        ]);
    }
}

/*








 Высота
*/
