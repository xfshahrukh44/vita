<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Excel;

class ProductExport implements FromArray, WithHeadings
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
            'Category',
            'Brand',
            'Article',
            'Unit',
            'Gender',
            'Purchase Price',
            'Consumer Selling Price',
            'Retailer Selling Price',
            'Quantity in Hand',
            'Cost Value',
            'Sales Value',
            'Minimum Ordering Quanitity'
        ];
    }

    public function array(): array
    {
        return $this->main_array;
    }
}
