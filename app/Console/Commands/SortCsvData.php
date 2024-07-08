<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use SplFileObject;



class SortCsvData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:sort {file : The name of the CSV file in storage/app directory} {column : The column number (0-indexed) to sort by}';

    /**
     * The console command description.
     *
     * @var string
     */



    protected $description = 'Sort data from a CSV file based on a specific column';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fileName = $this->argument('file');
        $columnIndex = $this->argument('column');

        $filePath = storage_path('app/' . $fileName);

        if (!Storage::exists($fileName)) {
            $this->error('File not found: ' . $fileName);
            return;
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        if ($extension === 'ods') {

            $csvFilePath = $this->convertOdsToCsv($filePath);

            if (!$csvFilePath) {
                $this->error('Failed to convert ODS to CSV.');
                return;
            }

            $data = $this->readCsvFile($csvFilePath);
            unlink($csvFilePath);
        } elseif ($extension === 'csv') {
            $data = $this->readCsvFile($filePath);
        } else {
            $this->error('Unsupported file format: ' . $extension);
            return;
        }

        if (empty($data)) {
            $this->error('No data found in the file: ' . $fileName);
            return;
        }

        $this->sortCsvData($data, $columnIndex);

        $newFileName = 'sorted_' . $fileName;
        $newFilePath = storage_path('app/' . $newFileName);

        $this->writeCsvFile($newFilePath, $data);

        $this->info('Sorted data has been saved to ' . $newFileName);
    }

    private function readCsvFile($filePath)
    {
        $file = new SplFileObject($filePath, 'r');
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::READ_AHEAD);

        $data = [];
        foreach ($file as $row) {
            if (!empty($row[0])) {
                $data[] = $row;
            }
        }

        return $data;
    }


    private function sortCsvData(&$data, $columnIndex)
    {
        usort($data, function ($a, $b) use ($columnIndex) {
            return intval($a[$columnIndex]) - intval($b[$columnIndex]);
        });
    }


    private function writeCsvFile($filePath, $data)
    {
        $file = new SplFileObject($filePath, 'w');

        foreach ($data as $row) {
            $file->fputcsv($row);
        }
    }

    private function convertOdsToCsv($odsFilePath)
    {
    $csvFilePath = storage_path('app/' . uniqid('temp_', true) . '.csv');
    $command = "libreoffice --headless --convert-to csv --outdir " . storage_path('app') . " " . $odsFilePath;

    exec($command);

    $convertedFilePath = str_replace('.ods', '.csv', $odsFilePath);

    if (file_exists($convertedFilePath)) {
        return $convertedFilePath;
    }

    return null;
    }
}
