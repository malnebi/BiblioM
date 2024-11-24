<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tag;
use App\Models\Book;
use App\Models\Library;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Sequence;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // \App\Models\User::factory()->create([
            //     'name' => 'Test User',
            //     'email' => 'test@example.com',
            // ]);
            
        User::factory(1)->create();
        Library::factory(3)->create();
        $tags = Tag::factory(3)->create();  // creatig collection of 3 tags
        Book::factory(5)->hasAttached($tags)->create( new Sequence([
            'featured' => false,
//            'schedule' => 'Full Time'
        ],[ 
            'featured' => true, 
  //          'schedule' => 'Part Time'
        ])); // attaching tags to those 10 books


//      Client::factory(3)->create();


    }

    

}
