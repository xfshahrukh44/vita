<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Excel;

class ExpensesExport implements FromArray, WithHeadings
{
    use Exportable;

    private $writerType = \Maatwebsite\Excel\Excel::CSV;

    protected $main_array;

    public function __construct(array $main_array)
    {
        $this->main_array = $main_array;
    }

    public function headings(): array
    {
        return [
            'Transaction Date',
            'Amount',
            'Details',
        ];
    }

    public function array(): array
    {
        return $this->main_array;
    }
}
