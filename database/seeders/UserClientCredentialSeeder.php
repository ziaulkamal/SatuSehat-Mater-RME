<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserClientCredential;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class UserClientCredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path ke file CSV
        $csvPath = database_path('seeders/data/sample-data.csv');

        // Baca file CSV
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0); // Asumsikan baris pertama adalah header

        // Dapatkan semua record dari CSV
        $records = $csv->getRecords();

        // Matikan query log untuk meningkatkan performa saat seeding
        DB::disableQueryLog();

        // Masukkan data ke database
        foreach ($records as $record) {
            UserClientCredential::create([
                'organisasi_id' => $record['organisasi_id'],
                'client_id' => $record['client_id'],
                'secret_id' => $record['secret_id'],
            ]);
        }
    }
}
