<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Typeymntprofs;

class TyppymentProfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Typeymntprofs::truncate();

        $typpeyments = [
            ['type' => 'pourcentage'],
            ['type' => 'mensuelle'],
            ['type' => 'heures'],
        ];

        foreach ($typpeyments as $typpeyment) {
            Typeymntprofs::create($typpeyment);
        }
    }
}
