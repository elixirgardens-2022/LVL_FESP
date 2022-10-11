# TASKS

## Database query code currently exists in 2 separate files

The code in the following 2 files, probably all needs moving to QueryProductsDbService.php:

- App\Services\QueryProductsDbService.php
- App\Http\Controllers\AjaxController.php


## Separation of code [COMPLETE]

- Javascript needs to be in separate files - not hardcoded into blade views.
- The pagination function should not be returning HTML. The HTML should be in the blade file (or a blade component called by a view). The HTML should be empty, using {{ }} to populate.
- The above also applies to the main table.
- DB::table('products') in the AjaxController should be moved to a service class. [stackoverflow](https://stackoverflow.com/questions/52336179/creating-laravel-service-class)


## Datatables

- Started exploring the documentation: https://datatables.net/examples/index
- Useful Laravel Dail video: https://youtu.be/1wgLY-V69MM

Adam advised looking into these (various plugins) to reduce the code I write for things like table sort, search etc.