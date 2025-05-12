<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('templates')->insert([
            [
                'nama_template' => 'Template Surat Cuti',
                'kategori' => 'Cuti',
                'file_path' => 'templates/surat_cuti.pdf'
            ]
        ]);
    }
}
