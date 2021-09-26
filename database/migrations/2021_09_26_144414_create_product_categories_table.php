<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('sync_id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('category_sync_id')->nullable()->after('type_sync_id');
        });

        $date = date('Y-m-d H:i:s');
        DB::table('product_categories')->insert([
            [
                'sync_id' => 'sushi',
                'name' => 'Суши',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'sets',
                'name' => 'Сеты',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'pizza',
                'name' => 'Пиццы',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'drinks',
                'name' => 'Напитки',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'additions',
                'name' => 'Дополнения',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'deserts',
                'name' => 'Десерты',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'salads',
                'name' => 'Салаты',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'other',
                'name' => 'Прочее',
                'created_at' => $date,
                'updated_at' => $date,
            ],
        ]);

        DB::table('products')->update(['category_sync_id' => 'other']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
}
