<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('name');
            $table->string('model_number')->nullable()->after('brand');
            $table->string('architecture_socket')->nullable()->after('model_number');
            $table->string('core_configuration')->nullable()->after('architecture_socket');
            $table->text('performance')->nullable()->after('core_configuration');
            $table->string('integrated_graphics')->nullable()->after('performance');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'brand',
                'model_number',
                'architecture_socket',
                'core_configuration',
                'performance',
                'integrated_graphics'
            ]);
        });
    }
};