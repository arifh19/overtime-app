<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departemen;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departemen = new Departemen();
        $departemen->name = "Pimpinan";
        $departemen->biaya_lembur = 350000;
        $departemen->save();

        $departemen = new Departemen();
        $departemen->name = "Graphic";
        $departemen->biaya_lembur = 350000;
        $departemen->save();
        
        $departemen = new Departemen();
        $departemen->name = "Printing";
        $departemen->biaya_lembur = 350000;
        $departemen->save();

        $departemen = new Departemen();
        $departemen->name = "Laminasi";
        $departemen->biaya_lembur = 350000;
        $departemen->save();

        $departemen = new Departemen();
        $departemen->name = "Bagmaking";
        $departemen->biaya_lembur = 350000;
        $departemen->save();
    }
}
