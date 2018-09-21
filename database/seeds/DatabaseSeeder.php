<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (Schema::hasTable('users')) {
            $this->call('UsersTableSeeder');
            $this->command->info('Users table seeded!');
        }else{
            $this->command->info('Users table not found!');
        }

        if (Schema::hasTable('films')) {
        	$this->call('FilmsTableSeeder');
			$this->command->info('Films table seeded!');
        }else{
        	$this->command->info('Films table not found!');
        }

        if (Schema::hasTable('comments')) {
            $this->call('CommentsTableSeeder');
            $this->command->info('Comments table seeded!');
        }else{
            $this->command->info('Comments table not found!');
        }
    }
}

class UsersTableSeeder extends Seeder {

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('users')->truncate();

        DB::table('users')->insert([['role_id' => 1,'name' => 'Admin', 'email' => 'admin@gmail.com', 'password' => '$2y$10$zr9ULBCYLySmEDoPgSM2gOuy/x3EMeZ7/5G0BQKfUtQous4sFEISe', 'remember_token' => ''],['role_id' => 2,'name' => 'Raghbendra Nayak', 'email' => 'user@gmail.com', 'password' => '$2y$10$zr9ULBCYLySmEDoPgSM2gOuy/x3EMeZ7/5G0BQKfUtQous4sFEISe', 'remember_token' => '']]);
    }
}

class FilmsTableSeeder extends Seeder {

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('films')->truncate();

        DB::table('films')->insert([['name' => 'Stree', 'slug' => 'stree', 'description' => 'Horror Comedy', 'release_date' => '2018-08-30', 'rating' => 4, 'ticket_price'=> 250, 'photo' => 'stree.jpg', 'country' => 'india', 'genre' => 'action,comedy'],['name' => 'Manmarziyaan', 'slug' => 'manmarziyaan', 'description' => 'Romantic', 'release_date' => '2018-09-14', 'rating' => 4, 'ticket_price'=> 200, 'photo' => 'manmarziyaan.jpg','country' => 'india', 'genre' => 'comedy,horror'],['name' => 'The Predator', 'slug' => 'the-predator', 'description' => 'Horror', 'release_date' => '2018-09-13', 'rating' => 3, 'ticket_price'=> 150, 'photo' => 'predator.jpg','country' => 'india', 'genre' => 'comedy,horror']]);
    }
}

class CommentsTableSeeder extends Seeder {

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('comments')->truncate();

        DB::table('comments')->insert([['user_id' => 2, 'film_id' => 1, 'comments' => 'Nice Movie'],['user_id' => 2, 'film_id' => 2, 'comments' => 'Awesome Movie'],['user_id' => 2, 'film_id' => 3, 'comments' => 'Timepass Movie']]);
    }
}