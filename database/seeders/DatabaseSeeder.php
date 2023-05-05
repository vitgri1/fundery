<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Jonas',
            'email' => 'jonas@gmail.com',
            'password' => Hash::make('abc123'),
        ]);

        DB::table('users')->insert([
            'name' => 'Jonas2',
            'email' => 'jonas2@gmail.com',
            'password' => Hash::make('abc123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Jonas3',
            'email' => 'jonas3@gmail.com',
            'password' => Hash::make('abc123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Jonas4',
            'email' => 'jonas4@gmail.com',
            'password' => Hash::make('abc123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Jonas5',
            'email' => 'jonas5@gmail.com',
            'password' => Hash::make('abc123'),
        ]);

        $faker = Faker::create();

        foreach(range(1, 15) as $_) {
            DB::table('tags')->insert([
                'title' => $faker->word
            ]);
        }

        function uniqueTagsArray()
        {
            $uniqueTags = range(1,15);
            shuffle($uniqueTags);
            return array_slice($uniqueTags, 0, rand(1,6));
        }
        
        foreach(range(1, 15) as $_) {
            DB::table('ideas')->insert([
                'title' => $faker->word,
                'description' => $faker->text($maxNbChars = 200),
                'type' => rand(0,1),
                'funds' => rand(1,99999),
                // 'tag_ids' => json_encode(array_map(fn($t)=>$t = rand(1,15), range(1,rand(1,6)))),
                'tag_ids' => json_encode(uniqueTagsArray()),
                'created_at' => $faker->dateTime,
                'hearts' => json_encode([])
            ]);
        }

        foreach(range(1, 50) as $_) {
            DB::table('donations')->insert([
                'amount' => rand(1,150),
                'donator_id' => rand(1,5),
                'created_at' => $faker->dateTime,
                'idea_id' => rand(1,15),
            ]);
        }
    }
}
