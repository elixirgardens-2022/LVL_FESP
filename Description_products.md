## Products Description

```
Displays all the Elixir products. Defaults to 30 records per page (can be change in the pagination bar).

Displays 4 data columns (sku, title, weight, length).

Existing products can be edited and new products added via a modal popup. Multiple products can be added
as a batch process via CSV import. The import procedure checks that the first line of the CSV contains all
4 column titles (sku, title, weight, length) and no more.

An error message displays above the products table if all 4 columns are not found. A success message is
displayed if CSV import is successful.

Products table data (products@FESP) can be exported as CSV.
```

<details>
<summary>Laravel Files</summary>

```
➤ routes/web.php

➤ app/Http/Controllers/AjaxController.php
➤ app/Http/Controllers/ArtisanCallsController.php
➤ app/Http/Controllers/CsvController.php
➤ app/Http/Controllers/ProductsController.php
➤ app/Http/Controllers/.php
➤ app/Http/Controllers/.php
➤ app/Http/Controllers/.php
➤ app/Http/Controllers/.php

➤ app/Models/Product.php

➤ app/Services/DbQueryService.php

➤ database/migrations/2022_09_06_095054_create_products_table.php
➤ database/migrations/2022_09_14_075915_create_sessions_table.php
➤ database/seeders/DatabaseSeeder.php

➤ public/css/form_style.css
➤ public/css/modal.css
➤ public/css/style.css
➤ public/files/products.csv
➤ public/js/ajax.js
➤ public/js/clean_url.js
➤ public/js/jquery-1.12.4.min.js
➤ public/js/modal_add_edit.js
➤ public/js/tbl_sort.js
➤ public/js/update_tr_cells.js

➤ resources/views/includes/modal.blade.php
➤ resources/views/includes/pagination.blade.php
➤ resources/views/layout.blade.php
➤ resources/views/products.blade.php

➤ storage/debugbar/
```
</details>

### The web routes file has 5 routes:

```
1. products
2. ajax/insert
3. exportCsv
4. importCsv
5. artisan/run_migrate_fresh__seed
```

## products
'products' links to the products method (ProductsController). The default pagination values are saved as session variables, and the first 30 records in the products table data (products@FESP) are retrieved via the 'tblRecords' method in the 'DbQueryService' service class (DbQueryService accessed via dependency injection). The returned results are then passed to the private makeProductsTbl() method. This returns the formatted data
that gets passed to the 'products.blade.php' view.

'products.blade.php' extends the 'layout.blade.php' view. It uses 3 @section methods to insert the page title,
the main page content and the header bar content into the 'layout.blade.php' view.

The 'layout.blade.php' view uses @include to add the 'includes/pagination.blade.php' code to the header bar.

The 'products.blade.php' view also uses an @include to add the modal popup (includes/modal.blade.php).
It also passes a second parameter: an array to set the modal width and fields required.

## ajax/modifyDb
'ajax/modifyDb' links to the modifyDb method (AjaxController). This is the PHP file that the 'public/js/ajax.js' file makes an AJAX call to.

The modifyDb method either updates an existing record in the 'products' table or inserts a new record into the 'products' table.

The table and data to insert/update is passed to the insertRecords/updateRecord method DbQueryService class. In the case of the updateRecord method, WHERE clause data is also passed to identify which record to update.

The 'products' table will only be modified if one or more fields change when a record is being updated. When a record is being inserted, the table will only modified if all 4 fields have been entered.

## exportCsv / importCsv
'exportCsv' / 'importCsv' link to the exportCsv / importCsv methods (CsvController).

The exportCsv method uses the get() method to retrieve all records from the products table - via the 'Product' model.

A 'public/files/products.csv' is created and opened. The contents of the 'products' table is then written to the file:

```
"sku","title","weight","length","updated_at"
"AGSDH76","12 Cell Bedding Plant Pack Tray Inserts for Half Size Seed Trays x 100","2.6","0.26","2022-09-22 15:23:04"
"JKAHS94","200 x 12 Cell Bedding Plant Pack Tray Inserts for Half Size Seed Trays","5.2","0.26","2022-09-22 15:23:04"
etc.
```

The 'Response::download' method is used to download the CSV file. The file is named 'products_export(YYYY-MM-DD).csv'.

The importCsv method saves the selected File Upload CSV file to 'storage/app/files/', as 'csv_file'. Note: This only works if you've run $ php artisan storage:link beforehand (Artisan::call('storage:link') can be run from web.php if you need to set-up on a shared hosting platform with no command-line access). 

The contents of the 'csv_file' file are assigned to the '$csv_arr' array. The first line of the array is checked: Must only contain 4 fields. The 4 fields must be 'sku', 'title', 'weight' & 'length'. If incorrect, an error message is saved to a session array ('msg_error' => "CSV headings can only be 'sku', 'title', 'weight' and 'length'"). This message appears above the table when redirected back to the page.

If correct, every record is pushed to a multidimensional array ($insert_data). This is then passed to the 'insertRecords' method in the 'DbQueryService' service class, along with the table name (products). A success message is saved to a session array ('msg_success' => 'CSV Imported'), which appears above the table when redirected back to the page.

## Service Class: app/Services/DbQueryService.php
This database service class (5 methods) is used by several controllers (AjaxController, CsvController, ProductsController) to query the database.

method | info
---|---
recordsCount() | Returns the total number of records for a given table.
tblRecords() | Returns the records for a given table, offset and limit.
tblRecord() | Returns a record for a given table, using the WHERE clause supplied.
insertRecords() | Inserts multiple records into the table supplied.
updateRecord() | Updates a record in the table supplied, using the WHERE clause supplied.

The idea is to have all db query functions in one class.



---

### Hints / Tips

```
➤ Use clear docblock descriptions.
➤ Check code is clear of all debug/unnecessary comments (comments that just repeat method names are unnecessary). 
➤ Delete all unused files.
➤ Using private methods that only have one or two lines of code, or only get called once, are often preferable.
  They help to make code more readable.
```


<details>
<summary>NOTES</summary>

```
::create() uses eloquent and is slow. If you're inserting 100 records, it can only do it by running 100 separate queries.
::insert() can insert the 100 records in 1 query.

insert() doesn't use eloquent, so 'created_at' and 'updated_at' need to be added manually, if required:

'created_at' => now()->toDateTimeString(),
'updated_at' => now()->toDateTimeString(),


$chunks = array_chunk($data, 5000);
foreach ($chunks as $chunk) {
    Product::insert($chunk);
}

*** Check out eager loading and n+1 problem in Laravel Docs. ***

Install laravel-debugbar (set .env/APP_DEBUG=false to disable):
PROJECT_DIR$ composer require barryvdh/laravel-debugbar --dev
```
</details>


<details>
<summary>Laravel Tutorials</summary>

➤ [Dependency Injection, Services and Static methods](https://blog.quickadminpanel.com/laravel-when-to-use-dependency-injection-services-and-static-methods/)

➤ [Laravel CSV Import: Check Duplicates with Fewer DB Queries](https://youtu.be/nGcW4jR9vLg)

➤ [Laravel Excel Export/Import Large Files: Bus Batch and Livewire](https://youtu.be/v8eSGeszu4U)

➤ [Seeding 50k DB Rows in Laravel: Create, Insert or Chunk?](https://youtu.be/sAjLREMr-9k)

➤ [4 Packages You Need in ANY Laravel Project](https://youtu.be/UORMYT8Fs9Y)

➤ [Laravel 4 Beginners](https://laraveldaily.teachable.com/p/laravel-for-beginners-your-first-project)

</details>
