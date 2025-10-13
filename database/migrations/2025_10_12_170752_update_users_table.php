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
        Schema::table("users", function (Blueprint $table) {
            $table->string("password")->nullable()->change();
            $table->string("phone")->after('name')->nullable();
            $table->string('otp')->after('phone')->nullable();
            $table->boolean('status')->after('otp')->default(false);
            $table->string("name")->nullable()->change();
            //this is the new updates

            $table->string("address")->after("otp")->nullable();
            $table->string("city")->after("otp")->nullable();
            $table->string('state')->after("city")->nullable();
            $table->string("pincode")->after("city")->nullable();
            $table->string("landmark")->after("pincode")->nullable();
            


          

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {

            $table->string("password")->nullable(false)->change();
            $table->dropColumn('phone');
            $table->dropColumn('otp');
            $table->dropColumn('status');
             $table->string("name")->nullable(false)->change();
        });
    }
};
