<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('staff')) {
            Schema::create('staff', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('role')->default('staff');
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->string('position')->nullable();
                $table->decimal('salary', 10, 2)->nullable();
                $table->date('hire_date')->nullable();
                $table->string('status')->default('active');
                $table->string('profile_image')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('staff');
    }
};