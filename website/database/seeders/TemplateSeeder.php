<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        Template::create([
            'nama_template' => 'Surat Keterangan Aktif',
            'kategori' => 'Keterangan',
            'file_path' => 'templates/surat_keterangan_aktif.docx',
        ]);

        Template::create([
            'nama_template' => 'Surat Cuti',
            'kategori' => 'Cuti',
            'file_path' => 'templates/surat_cuti.docx',
        ]);
    }
}
