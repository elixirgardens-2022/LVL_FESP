<?php

// http://localhost/LVL_FESP_elixirgardens-2022/undispatched_data/transfer_fesp_data_to_mysql.php
// http://localhost/elixirgardens-2022/LVL_FESP/undispatched/transfer_fesp_data_to_mysql.php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// set_time_limit(40);
// ini_set("memory_limit", "-1");


$db_host = 'localhost';
$db_name = 'FESP';
$db_user = 'root';
$db_pass = '';

$db_mysql = new PDO(
    "mysql:host=$db_host;dbname=$db_name",
    $db_user,
    $db_pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // required to store / display unicode characters correctly - eg. 3m²-700m² (3m\u00b2-700m\u00b2)
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ]
);
$tables = [
    'amazon',
    // 'ebay',
    // 'ebay_prosalt',
    // 'floorworld',
    // 'onbuy',
    // 'website',
];


try {
    if ($db_mysql->exec("DROP TABLE IF EXISTS lookup_title_variation_price")) {
        echo "Deleted lookup_title_variation_price table.<br>";
    }
    
    $sql ="CREATE TABLE `lookup_title_variation_price` (
        `autoInc`   int AUTO_INCREMENT,
        `platform`  char(2) NOT NULL,
        -- `int`       timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        `date`      int NOT NULL,
        `sku`       varchar(60) NOT NULL,
        `title`     varchar(255) NOT NULL,
        `variation` varchar(255) NOT NULL,
        `price`     double NOT NULL,
        PRIMARY KEY(`autoInc`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    
    echo "Create lookup_title_variation_price table.<br>";
    $db_mysql->exec($sql);
    
} catch (PDOException $e) {
    echo $e->getMessage();
}

foreach ($tables as $tbl) {
    try {
        if ($db_mysql->exec("DROP TABLE IF EXISTS {$tbl}_orders")) {
            echo "Deleted {$tbl}_orders table.<br>";
        }
        
        $data = [];
        $data[] = '`autoInc` int(8) NOT NULL AUTO_INCREMENT,';
        $data[] = '`orderId` varchar(20) UNIQUE NOT NULL,';
        $data[] = '`total` double NOT NULL,';
        $data[] = '`currency` char(3) NOT NULL,';
        $data[] = '`date` int NOT NULL,';
        // $data[] = '`date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),';
        $data[] = '`buyer` varchar(255) NOT NULL,';
        $data[] = '`phone` varchar(30) NOT NULL,';
        $data[] = '`email` varchar(50) NOT NULL,';
        if ('amazon' == $tbl) {$data[] = '`isPrime` tinyint(4) DEFAULT NULL,';}
        $data[] = '`service` varchar(14) NOT NULL,';
        $data[] = '`shippingName` varchar(255) NOT NULL,';
        $data[] = '`addressLine1` varchar(255) NOT NULL,';
        $data[] = '`addressLine2` varchar(255) NOT NULL,';
        $data[] = '`city` varchar(60) NOT NULL,';
        $data[] = '`county` varchar(60) NOT NULL,';
        $data[] = '`countryCode` char(2) NOT NULL,';
        $data[] = '`postcode` varchar(9) NOT NULL,';
        $data[] = '`message` varchar(255) NOT NULL DEFAULT "",';
        $data[] = 'PRIMARY KEY (`autoInc`)';
        $data_str = implode('', $data);
        
        $sql ="CREATE TABLE IF NOT EXISTS {$tbl}_orders($data_str) ENGINE=InnoDB DEFAULT CHARSET=utf8;;";
        
        echo "Create {$tbl}_orders table.<br>";
        $db_mysql->exec($sql);
        
        
        if ($db_mysql->exec("DROP TABLE IF EXISTS {$tbl}_items")) {
            echo "Deleted {$tbl}_items table.<br>";
        }
        
        // $db_mysql->exec("DROP TABLE IF EXISTS {$tbl}_items");
        // echo "Deleted {$tbl}_items table.<br>";
        
        $sql ="CREATE TABLE IF NOT EXISTS {$tbl}_items(
            `autoInc` int(8) NOT NULL AUTO_INCREMENT,
            `orderId` varchar(20) NOT NULL,
            `itemId` varchar(20) NOT NULL,
            `sku` varchar(60) NOT NULL,
            `qty` int(3) NOT NULL,
            `shipping` double NOT NULL,
            PRIMARY KEY (`autoInc`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        echo "Create {$tbl}_items table.<br>";
        $db_mysql->exec($sql);
        
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

/*
SFPs:               isprime| service   | courier
------------------------------------------------------
026-3017918-3529151 | true | Standard  | 1 × HI
026-4359585-7926725 | true | Standard  | SUR-H-4.99 (1)
026-6802674-1742738 |  "        "           "
026-6865410-6229961 | true | NextDay   | 1 × SFP
026-8788080-7083512 |  "        "           "
202-1205715-3647550 |  "        "           "
202-2888828-4022768 |  "        "           "
202-3683462-2709124 |  "        "           "
202-5390086-0173121 |  "        "           "
202-7025370-8281119 | true | Standard  | 1 × HI
202-7197776-8323540 | true | NextDay   | 1 × SFP
202-8724319-9135516 | true | SecondDay | 1 × SFP
203-0846425-2483545 | true | NextDay   | 1 × SFP
203-2660938-0087563 | true | Standard  | 1 × HI
203-3723909-0394707 | true | NextDay   | 1 × SFP
203-3880843-4057117 |  "        "           "
203-4463437-8115523 |  "        "           "
203-9918418-6969151 |  "        "           "
204-0257093-8893165 |  "        "           "
*/


$json_undispatched = file('json_data/json_undispatched.php', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$orderIDs = [];
foreach ($json_undispatched as $i => $json_rec) {
    $json_decode = json_decode($json_rec, true);
    
    $orderIDs[] = [
        'orderID' => $json_decode['orderID'],
        'source' => $json_decode['source'],
        'channel' => $json_decode['channel'],
        'tbl' => 'prosalt' != $json_decode['channel'] ? $json_decode['source'] : $json_decode['source'] .'_'. $json_decode['channel'],
    ];
}

$tmp = [];
foreach ($orderIDs as $rec) {
    $tbl = $rec['tbl'];
    $tmp[$tbl][] = $rec['orderID'];
}
$orderIDs = $tmp;

$where_in = [];
foreach ($orderIDs as $source => $vals) {
    $where_in[$source] = implode("','", $vals);
}

$db_sqlite = new PDO('sqlite:api_orders.db3');

$api_orders = [];
foreach ($where_in as $platform => $orderIDs) {
    $sql = "SELECT * FROM {$platform}_orders WHERE `orderId` IN ('$orderIDs')";
    
    $results = $db_sqlite->query($sql);
    $api_orders["{$platform}_orders"] = $results->fetchAll(PDO::FETCH_ASSOC);
    
    $tmp = [];
    foreach ($api_orders["{$platform}_orders"] as $i => $rec) {
        foreach ($rec as $key => $n_a) {
            $rec[$key] = str_replace("'", "\'", $rec[$key]);
            
            if ('date' == $key) {
                $rec[$key] = preg_replace('/\.\d{3}Z$/', '', $rec[$key]);
                $rec[$key] = str_replace('T', ' ', $rec[$key]);
                
                $rec[$key] = strtotime(date($rec[$key]));
            }
            if ('isprime' == $key) {
                $rec[$key] = 'false' == $rec[$key] ? '0' : '1';
            }
        }
        
        $api_orders["{$platform}_orders"][$i] = $rec;
    }
    
    $sql = "SELECT * FROM {$platform}_items WHERE `orderId` IN ('$orderIDs')";
    $results = $db_sqlite->query($sql);
    $api_orders["{$platform}_items"] = $results->fetchAll(PDO::FETCH_ASSOC);
    
    $tmp = [];
    foreach ($api_orders["{$platform}_items"] as $i => $rec) {
        foreach ($rec as $key => $n_a) {
            if (!is_null($rec[$key])) {
                $rec[$key] = str_replace("'", "\'", $rec[$key]);
            }
            
            if ('shipping' == $key) {
                $rec[$key] = '' == $rec[$key] ? '0' : $rec[$key];
            }
        }
        
        $api_orders["{$platform}_items"][$i] = $rec;
    }
}


$orders_flds = "`orderId`,`total`,`currency`,`date`,`buyer`,`phone`,`email`,`service`,`shippingName`,`addressLine1`,`addressLine2`,`city`,`county`,`countryCode`,`postcode`";
$orders_flds_a = "`orderId`,`total`,`currency`,`date`,`buyer`,`phone`,`email`,`isPrime`,`service`,`shippingName`,`addressLine1`,`addressLine2`,`city`,`county`,`countryCode`,`postcode`";

$items_flds = "`orderId`,`itemId`,`sku`,`qty`,`shipping`";


$lookup_sku_dates = [];
$lookup_sku_title_variation_price = [];
foreach ($api_orders as $tbl => $arr) {
    if ('amazon_orders' == $tbl || 'amazon_items' == $tbl) {
        if ('orders' == substr($tbl, -6)) {
            $flds = 'amazon_orders' != $tbl ? $orders_flds : $orders_flds_a;
        }
        elseif ('_items' == substr($tbl, -6)) {
            $flds = $items_flds;
        }
        
        $insert = [];
        $insert_sku_title_variation_price = [];
        foreach ($arr as $i => $recs) {
            if (!$i) {
                $insert[] = "INSERT INTO `$tbl` ($flds) VALUES ";
                $insert_sku_title_variation_price[] = "INSERT INTO `lookup_title_variation_price` (`platform`,`date`,`sku`,`title`,`variation`,`price`) VALUES ";
                
                // $flds_count = count(explode(",", $flds));
                // $data_count = count(array_values($recs));
                // echo '<pre style="background:#111; color:#b5ce28; font-size:11px;">'; print_r($flds_count .' = '. $data_count); echo '</pre>';
            }
            
            if (isset($recs['date'])) {
                $lookup_sku_dates[$recs['orderId']] = [
                    'date' => $recs['date'],
                ];
            }
            
            // echo '<pre style="background:#111; color:#b5ce28; font-size:11px;">'; print_r($recs); echo '</pre>'; die();
            
            if ('_items' == substr($tbl, -6)) {
                switch (substr($tbl, 0,-6)) {
                    case 'amazon':
                        $platform = 'am';
                        break;
                    case 'ebay':
                        $platform = 'ee';
                        break;
                    case 'ebay_prosalt':
                        $platform = 'ep';
                        break;
                    case 'floorworld':
                        $platform = 'ef';
                        break;
                    case 'onbuy':
                        $platform = 'on';
                        break;
                    case 'website':
                        $platform = 'we';
                        break;
                    default:
                        $platform = 'XX';
                        break;
                }
                
                // $lookup_sku_dates[$recs['orderId']]['sku'] = $recs['sku'];
                
                
                // $lookup_sku_title_variation_price = [
                //     'amPlayground-Sand_25_3'           => '4b097a074fdac0a4758187c95efed33164ffefe945b8ea113888b4abe8b35ac1',
                //     'amDead_Sea_Table_(Bath)-20kg-tub' => '97137a12fbb5092b3c0f0ac5ca91204f685c58feb5b0d80caa7e7cc7681b28b6',
                //     'amsplitcane_600_10'               => '9568f0388e7a396019ebe6913ba3c4c60d31dad7633db6d96ebb44709cb1fb47',
                //     'am9N-OXS8-O0LD'                   => 'b1f06e9b3e5afdeeba2402ca6220bb944e2478fbbd4b1e46c3136c4925581312',
                // ]
                
                $ts = $lookup_sku_dates[$recs['orderId']]['date'];
                
                $price = $recs['price'] / $recs['qty'];
                $tvp = $recs['title'].$recs['variations'].$price;
                if (!isset($lookup_sku_title_variation_price[$platform.$recs['sku']])) {
                    $lookup_sku_title_variation_price[$platform.$recs['sku']] = hash("sha256", $tvp);
                    $insert_sku_title_variation_price[] = "('$platform','$ts','{$recs['sku']}','{$recs['title']}','{$recs['variations']}','$price'),";
                }
                elseif (hash("sha256", $tvp) != $lookup_sku_title_variation_price[$platform.$recs['sku']]) {
                    $insert_sku_title_variation_price[] = "('$platform','$ts','{$recs['sku']}','{$recs['title']}','{$recs['variations']}','$price'),";
                }
                
                unset($recs['title']);
                unset($recs['variations']);
                unset($recs['price']);
            }
            
            $insert[] = "('".implode("','", array_values($recs))."'),";
        }
        
        $sql_insert_sku_title_variation_price = substr(implode("", $insert_sku_title_variation_price), 0,-1).';';
        $sql_insert = substr(implode("", $insert), 0,-1).';';
        
        // $sql_insert_sku_title_variation_price = str_replace(") VALUES (", ") VALUES\n(", $sql_insert_sku_title_variation_price);
        // $sql_insert_sku_title_variation_price = str_replace("),(", "),\n(", $sql_insert_sku_title_variation_price);
        // echo '<pre style="background:#111; color:#b5ce28; font-size:11px;">'; print_r($sql_insert_sku_title_variation_price); echo '</pre>';
        
        // $sql_insert = str_replace(") VALUES (", ") VALUES\n(", $sql_insert);
        // $sql_insert = str_replace("),(", "),\n(", $sql_insert);
        // echo '<pre style="background:#111; color:#b5ce28; font-size:11px;">'; print_r($sql_insert); echo '</pre>';
        
        // TRUNCATE TABLE `lookup_title_variation_price`;
        // TRUNCATE TABLE `amazon_items`;
        
        if ('_items' == substr($tbl, -6)) {
            $db_mysql->query($sql_insert_sku_title_variation_price);
        }
        
        $db_mysql->query($sql_insert);
    }
}

// echo '<pre style="background:#111; color:#b5ce28; font-size:11px;">'; print_r($lookup_sku_title_variation_price); echo '</pre>';