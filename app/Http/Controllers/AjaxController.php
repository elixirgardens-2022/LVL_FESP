<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Services\DbQueryService; // Import service class

class AjaxController extends Controller
{
    public function modifyDb(
        Request $request,
        DbQueryService $dbQueryService // Dependency inject service class into controller method
    )
    {
        $tbl = 'products';
        $return = NULL;
        
        // Only update table if 1 or more fields have changed
        if ('none' != $request->id &&
            ($request->input_sku != $request->input_sku_orig ||
            $request->input_title != $request->input_title_orig ||
            $request->input_weight != $request->input_weight_orig ||
            $request->input_length != $request->input_length_orig)
        ) {
            $where = [
                'fld' => 'id',
                'val' => $request->id,
            ];
            $update_data = [
                'sku'    => $request->input_sku,
                'title'  => $request->input_title,
                'weight' => $request->input_weight,
                'length' => $request->input_length,
                'updated_at' => NULL,
            ];
            
            $dbQueryService->updateRecord($tbl, $where, $update_data);
            
            $return = $request->post();
        }
        // Only insert table record if all fileds have been entered
        elseif ('none' == $request->id &&
            ('' != $request->input_sku &&
            '' != $request->input_title &&
            '' != $request->input_weight &&
            '' != $request->input_length)
        ) {
            $insert_data = [
                'sku'    => $request->input_sku,
                'title'  => $request->input_title,
                'weight' => $request->input_weight,
                'length' => $request->input_length,
                'updated_at' => NULL,
            ];
            
            $dbQueryService->insertRecords($tbl, $insert_data);
            
            $return = $request->post();
        }
        
        if ($return) {echo json_encode($return);}
        else {echo NULL;}
    }
}
