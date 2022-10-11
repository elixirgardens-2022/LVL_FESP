<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use  App\Services\DbQueryService; // Import service class
use Response;
use File;

class CsvController extends Controller
{
    public function exportCsv()
    {
        $products = Product::get();
        // $products = Product::limit(10)->get();
        
        $headers = ['Content-Type' => 'text/csv'];
        
        // Create 'public/files/' directory if not exist.
        if (!File::exists(public_path().'/files')) {
            File::makeDirectory(public_path() . '/files');
        }
        
        $filename =  public_path('files/products.csv');
        $handle = fopen($filename, 'w');

        fwrite($handle, "\"sku\",\"title\",\"weight\",\"length\",\"updated_at\"\n");
        foreach ($products as $rec) {
            fwrite($handle, '"'.$rec->sku.'"'.','.'"'.$rec->title.'"'.','.'"'.$rec->weight.'"'.','.'"'.$rec->length.'"'.','.'"'.$rec->updated_at.'"'."\n");
        }
        fclose($handle);

        $name = 'products_export('.date('Y-m-d').').csv';

        return Response::download($filename, $name, $headers);
    }
    
    public function importCsv(
        Request $request,
        DbQueryService $serviceDbQuery // Dependency inject service class
    )
    {
        // Create symbolic link first
        // $ php artisan storage:link
        
        $request->file('csv')->storeAs('files', 'csv_file');

        $file =  storage_path('app/files/csv_file');

        $csv_arr = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $insert_data = [];
        foreach ($csv_arr as $i => $row) {
            $row = substr($row, 1, -1);

            $csv_flds = explode('","', $row);

            if (!$i) {
                if ('sku' != $csv_flds[0] || 'title' != $csv_flds[1] || 'weight' != $csv_flds[2] || 'length' != $csv_flds[3] || 4 != count($csv_flds)) {
                    session([
                        'msg_error' => "CSV headings can only be 'sku', 'title', 'weight' and 'length'",
                        'msg_success' => '',
                    ]);
                    
                    return redirect()->route('products');
                }
            }
            else {
                $insert_data[] = [
                    'sku'    => $csv_flds[0],
                    'title'  => $csv_flds[1],
                    'weight' => $csv_flds[2],
                    'length' => $csv_flds[3],
                    'updated_at' => NULL,
                ];
            }
        }

        $serviceDbQuery->insertRecords('products', $insert_data);

        session([
            'msg_error' => '',
            'msg_success' => 'CSV Imported',
        ]);

        return redirect()->route('products');
    }
}
