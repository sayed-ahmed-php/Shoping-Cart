<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products') -> insert([
            [
                'imagePath' => 'product/call.jpg',
                'title' => 'Call of Duty',
                'description' => 'books for action and funny ................',
                'price' => 20
            ],
            [
                'imagePath' => 'product/child.jpg',
                'title' => 'Sleeping Story',
                'description' => 'books for Children ................',
                'price' => 20
            ],
            [
                'imagePath' => 'product/cooking.jpg',
                'title' => 'How to cook?',
                'description' => 'books for learning cooking ................',
                'price' => 20
            ],
            [
                'imagePath' => 'product/funny.jpg',
                'title' => 'Funny Books',
                'description' => 'books for action and funny ................',
                'price' => 20
            ],
            [
                'imagePath' => 'product/harry.jpg',
                'title' => 'Harry Potter',
                'description' => 'books for action and funny ................',
                'price' => 20
            ],
            [
                'imagePath' => 'product/sport.jpg',
                'title' => 'Sports Beauty',
                'description' => 'books to learn sports ................',
                'price' => 20
            ]
        ]);
    }
}
