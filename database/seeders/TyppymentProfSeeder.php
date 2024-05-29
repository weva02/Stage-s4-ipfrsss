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

        $typeymntprofs = [
            ['type' => 'pourcentage'],
            ['type' => 'mensuelle'],
            ['type' => 'heures'],
        ];

        // foreach ($typeymntprof as $typeymntprof_id) {
        //     Typeymntprofs::create($typeymntprof);
        // }
        foreach ($typeymntprofs as $key => $value) {
            Typeymntprofs::create($value);
        }
    }
}
