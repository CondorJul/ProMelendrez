<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/***comeatdo por ricardo */
class pruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prueba')->insert([
            [
                'nombres' => 'Julio Cesar',
                'apellidos' => 'Condor Chacon',
                'dni' => '72456391',
                'telefono' => '963852741',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nombres' => 'Juan',
                'apellidos' => 'Peres',
                'dni' => '72456397',
                'telefono' => '963852741',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nombres' => 'Ricardo',
                'apellidos' => 'Solis',
                'dni' => '70606069',
                'telefono' => '963852743',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
