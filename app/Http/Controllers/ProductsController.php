<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Services\DbQueryService; // Import service class
use Exception;
use Illuminate\Support\Facades\Session;

class ProductsController extends Controller
{
    public function __construct()
    {
        // https://codeanddeploy.com/blog/laravel/laravel-session-tutorial-and-example
        if (!session('page')) {
            Session::put([
                'page' => 1,
                'limit' => 30,
                'offset' => 0,
                // 'sku_search' => null,
            ]);
        }
    }
    
    public function products(
        Request $request,
        DbQueryService $dbQueryService // Dependency inject service class into controller method
    )
    {
        // $post = request()->all();
        // $request->session()->get('limit'))
        // $request->session()->flush(); die();
        
        // Display multiple products
        if (!$request->input('sku_search')) {
            if (isset($_GET['page'])) {
                session([
                    'page'   => (int)$_GET['page'],
                    'limit'  => (int)$_GET['limit'],
                    'offset' => (int)(($_GET['page']-1) * $_GET['limit']-1),
                    
                    'msg_error' => '',
                    'msg_success' => '',
                ]);
            }
            
            // Pagination
            // Use service class (DbQueryService) methods
            session(['total_pages' => ceil($dbQueryService->recordsCount('products') / session('limit'))]);
            session(['prev_page' => session('page')-1]);
            session(['next_page' => session('page')+1]);
            session(['prev_link' => route('products') . '?page='.session('prev_page').'&limit='.session('limit')]);
            session(['next_link' => route('products') . '?page='.session('next_page').'&limit='.session('limit')]);
            session(['pageXofY' => 'Page '.session('page').' of '.session('total_pages')]);
            
            $products = $dbQueryService->tblRecords('products', session('offset'), session('limit'));
            
            $view_data = $this->makeProductsTbl($products);
            $view_name = array_keys($view_data)[0];
            
            return view($view_name, $view_data[$view_name]);
        }
        
        // Display 1 product
        else {
            $sku = request()->sku_search;
            
            // Use service class (DbQueryService) method
            $products = $dbQueryService->tblRecord('products', $where);
            
            $view_data = $this->makeProductsTbl($products);
            $view_name = array_keys($view_data)[0];
            
            return view($view_name, $view_data[$view_name]);
        }
    }
    
    private function makeProductsTbl($products, $pagi=NULL)
    {
        $tbl_body = [];
        foreach ($products as $rec) {
            $tbl_body[] = [
                'id' => $rec->id,
                'sku' => $rec->sku,
                'title' => htmlspecialchars_decode($rec->title),
                'weight' => $rec->weight,
                'length' => $rec->length,
            ];
        }
        
        return ['products' => [
                // Products table data
                'tbl_th' => ['sku','title','weight','length','actions'],
                'tbl_body' => $tbl_body,
            ]
        ];
    }
}
