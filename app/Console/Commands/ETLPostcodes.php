<?php

namespace App\Console\Commands;

use App\Models\Postcode;
use Illuminate\Console\Command;
use League\Csv\Exception as LeagueCSVException;
use League\Csv\Reader;

class ETLPostcodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:etlpostcodes {filePath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract, transform and load postcodes from the CSV file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('filePath');

        if (! file_exists($filePath)) {
            $this->error('The file does not exist.');
            return 1;
        }

        try {
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0);
        } catch (LeagueCSVException $e) {
            $this->error('An error occurred while reading the CSV file.');
            return 1;
        }

        foreach ($csv->getRecords() as $record) {
            Postcode::updateOrCreate(
                ['postcode' => $record['pcd']],
                [
                    'latitude' => $record['lat'],
                    'longitude' => $record['long'],
                ]
            );
            $this->info('Postcode ' . $record['pcd'] . ' has been imported.');
        }
    }
}
