<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class SettingSeeder extends Seeder
{

    public function run()
    {

        DB::table('settings')->insert([
            'key' => 'footer_contact_title_1',
            'value' => 'Título ejemplo 1',
        ]);

        DB::table('settings')->insert([
            'key' => 'footer_contact_title_2',
            'value' => 'Título ejemplo 2',
        ]);

        DB::table('settings')->insert([
            'key' => 'general_company_name',
            'value' => 'Fake co'
        ]);

        DB::table('settings')->insert([
            'key' => 'general_email',
            'value' => 'generaltest@mail.com'
        ]);

        DB::table('settings')->insert([
            'key' => 'general_footer_phone_1',
            'value' => '+123 456 7897'
        ]);

        DB::table('settings')->insert([
            'key' => 'general_footer_phone_2',
            'value' => '+786 987 1567'
        ]);

        DB::table('settings')->insert([
            'key' => 'general_phone',
            'value' => '+456 454 9877'
        ]);

        DB::table('settings')->insert([
            'key' => 'header_title',
            'value' => 'Título header'
        ]);

        DB::table('settings')->insert([
            'key' => 'header_text',
            'value' => 'Reina en mi espíritu una alegría admirable, muy parecida a las dulces alboradas.'
        ]);

        DB::table('settings')->insert([
            'key' => 'content_title',
            'value' => 'Título ejemplo contenido'
        ]);
        DB::table('settings')->insert([
            'key' => 'gallery_content_title',
            'value' => 'Título ejemplo galería'
        ]);
    }
}
