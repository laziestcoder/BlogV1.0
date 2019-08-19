<?php

use App\ModelList;
use Illuminate\Database\Seeder;

class ModelListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModelList::truncate();
        ModelList::insert([
            [
                'model_name' => 'User',
                'table_name' => 'users',
            ],
            [
                'model_name' => 'Post',
                'table_name' => 'posts',
            ],
        ]);
    }
}
