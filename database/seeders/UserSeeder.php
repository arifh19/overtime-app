<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pengawas = new Role();
        $pengawas->name = "Pengawas";
        $pengawas->save();

        $kepdep = new Role();
        $kepdep->name = "Kepala departemen";
        $kepdep->save();

        $keppab = new Role();
        $keppab->name = "Kepala pabrik";
        $keppab->save();

        //Add user for graphic department
        $user = new User();
        $user->name = 'Pengawas Graphic';
        $user->username = 'pengawas1';
        $user->password = bcrypt('rahasia');
        $user->departemen_id = 2; //Graphic
        $user->save();
        $user->attachRole($pengawas);

        $user = new User();
        $user->name = 'Kepala departemen Graphic';
        $user->username = 'kepdep1';
        $user->password = bcrypt('rahasia');
        $user->departemen_id = 2; //Graphic
        $user->save();
        $user->attachRole($kepdep);

        //Add user for printing department
        $user = new User();
        $user->name = 'Pengawas Printing';
        $user->username = 'pengawas2';
        $user->password = bcrypt('rahasia');
        $user->departemen_id = 3; //printing
        $user->save();
        $user->attachRole($pengawas);

        $user = new User();
        $user->name = 'Kepala departemen Printing';
        $user->username = 'kepdep2';
        $user->password = bcrypt('rahasia');
        $user->departemen_id = 3; //printing
        $user->save();
        $user->attachRole($kepdep);

        //add user for head factory
        $user = new User();
        $user->name = 'Kepala pabrik';
        $user->username = 'keppab';
        $user->password = bcrypt('rahasia');
        $user->departemen_id = 1; //pimpinan
        $user->save();
        $user->attachRole($keppab);
    }
}
