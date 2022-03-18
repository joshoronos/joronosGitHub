<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class ProductExport implements FromCollection,WithHeadings,WithMultipleSheets,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        
        $product = DB::table('products')
            ->join('product_details', 'products.brand_id', '=', 'product_details.id')
            ->select('products.name', 'products.quantity', 'product_details.brandName', 'products.created_at')
            ->get();

        \Log::info($product);

        return $product;
    }

    public function title(): string
    {
        return 'Product';
    }

    public function headings(): array
        {
            
            return [
                'Product Name',
                'Quantity',
                'Brand Name',
                'Date Created',
            ];
        }
    
        public function sheets(): array 
    {   
        return [
            new ProductExport(['Product']),
            new ProductDetailsExport(['Details']),
           
        ];
    }
    
}
