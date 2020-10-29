<?php

namespace App\Database\Seeds;

use CodeIgniter\I18n\Time;

class OrangSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        // $data = [
        //     [
        //         'nama'          => 'Fikri Ahmad Fauzi',
        //         'alamat'        => 'Jl.Bandung no 40',
        //         'created_at'    => time::now(),
        //         'updated_at'    => time::now()
        //     ],
        //     [
        //         'nama'          => 'Rizki Ahmad Fauzi',
        //         'alamat'        => 'Jl.Bandung no 40',
        //         'created_at'    => time::now(),
        //         'updated_at'    => time::now()
        //     ],
        //     [
        //         'nama'          => 'Adit Ahmad Fauzi',
        //         'alamat'        => 'Jl.Bandung no 40',
        //         'created_at'    => time::now(),
        //         'updated_at'    => time::now()
        //     ]

        // ];

        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 20; $i++) {
            $data = [
                'nama'          => $faker->name(),
                'alamat'        => $faker->address(),
                'created_at'    => Time::createFromTimestamp($faker->unixTime()),
                'updated_at'    => time::now()
            ];
            $this->db->table('orang')->insert($data);
        }

        // Simple Queries
        // $this->db->query(
        //     "INSERT INTO orang (nama, alamat,created_at,updated_at) VALUES(:nama:, :alamat:, :created_at:, :updated_at:)",
        //     $data
        // );

        // Using Query Builder
    }
}
