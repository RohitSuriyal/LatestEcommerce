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
        Schema::table('adminusers', function (Blueprint $table) {

            $table->string("email_verified_at")->after('name')->nullable();
            $table->boolean("status")->after('email_verified_at')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("adminusers", function (Blueprint $table) {
           $table->dropColumn('email_verified_at')->nullable(false);
           $table->dropColumn('status');

        });
    }
};
