<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Importable;

class RasioImport implements WithMultipleSheets
{
    use Importable;

    public array $errors   = [];
    public int $imported   = 0;
    public int $skipped    = 0;

    protected string $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function sheets(): array
    {
        $spreadsheet = IOFactory::load($this->filePath);
        $sheetCount  = $spreadsheet->getSheetCount();

        $sheets = [];

        for ($i = 0; $i < $sheetCount; $i++) {
            $sheets[$i] = new RasioSheetImport($this);
        }

        return $sheets;
    }
}