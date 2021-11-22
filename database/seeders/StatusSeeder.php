<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addStatus = new Status();
        $addStatus->status = "Diajukan";
        $addStatus->save();

        // $addStatus = new Status();
        // $addStatus->status = "Diterima oleh kadep";
        // $addStatus->save();

        $addStatus = new Status();
        $addStatus->status = "Diterima oleh factory head";
        $addStatus->save();

        $addStatus = new Status();
        $addStatus->status = "Ditolak";
        $addStatus->save();
    }
}
