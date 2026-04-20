<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('categories', 'fields_schema')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->json('fields_schema')->nullable()->after('image');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('categories', 'fields_schema')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('fields_schema');
            });
        }
    }
};