<?php

  

namespace Database\Seeders;

  

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

use App\Models\User;

  

class CreateUsersSeeder extends Seeder

{

    /**

     * Run the database seeds.

     *

     * @return void

     */

    public function run(): void

    {

        $users = [

            [

               'name'=>'Admin User',

               'email'=>'admin@rendy.com',

               'type'=>1,

               'password'=> bcrypt('admin'),

            ],

            [

               'name'=>'Cheff',

               'email'=>'cheff@rendy.com',

               'type'=> 2,

               'password'=> bcrypt('cheff'),

            ],

            [

               'name'=>'User',

               'email'=>'user@rendy.com',

               'type'=>0,

               'password'=> bcrypt('user'),

            ],

        ];

    

        foreach ($users as $key => $user) {

            User::create($user);

        }

    }

}