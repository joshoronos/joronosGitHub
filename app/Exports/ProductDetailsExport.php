<?php

namespace App\Exports;

use App\Models\ProductDetails;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductDetailsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ProductDetails::all();
    }
}
