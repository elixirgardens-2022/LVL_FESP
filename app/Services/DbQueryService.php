<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DbQueryService
{
    public function recordsCount(string $tbl)
    {
        return DB::table($tbl)->count();
    }
    
    public function tblRecords(string $tbl, int $offset, int $limit): Collection
    {
        return DB::table($tbl)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
    }
    
    public function tblRecord(string $tbl, array $where): array
    {
        return DB::table($tbl)
                    ->where($where['fld'], $where['val'])
                    ->get();
    }
    
    public function insertRecords(string $tbl, array $insert_data)
    {
        DB::table($tbl)->insert($insert_data);
    }
    
    public function updateRecord(string $tbl, array $where, array $update)
    {
        DB::table($tbl)
            ->where($where['fld'], $where['val'])
            ->update($update);
    }
}