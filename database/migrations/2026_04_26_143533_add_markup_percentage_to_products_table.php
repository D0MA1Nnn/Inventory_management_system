<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('cost_price', 10, 2)->nullable()->after('price');
            $table->decimal('markup_percentage', 5, 2)->default(0)->after('cost_price');
        });

        // Update existing products: set cost_price = price, markup = 0
        DB::table('products')->update([
            'cost_price' => DB::raw('price'),
            'markup_percentage' => 0
        ]);
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['cost_price', 'markup_percentage']);
        });
    }
};