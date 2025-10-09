<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("adminusers",function(Blueprint $table){
              $table->string("image")->nullable()->after("password");
              $table->string("name")->nullable()->after("image");
              $table->string("number")->nullable()->after("image");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("adminusers",function(Blueprint $table){
            $table->dropColumn('image');
            $table->dropColumn('name');
            $table->dropColumn('number');

        });
    }
};
