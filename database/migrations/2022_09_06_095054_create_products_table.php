<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
$ php artisan migrate


# Display the SQL statements that will be executed by the migrations without actually running them.
$ php artisan migrate --pretend
*/

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            // $table->id();
            // $table->charset = 'utf8mb4'; //default
            // $table->collation = 'utf8mb4_unicode_ci'; //default
            
            $table->id();
            $table->char('sku', 30)->unique();
            $table->string('title');
            $table->double('weight');
            $table->double('length');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            // $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
