<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("products", function (Blueprint $table) {
            // Ensure the column type matches users.id
            $table->unsignedBigInteger('user_id')->nullable()->change();
;

            // Add foreign key
            $table->foreign('user_id')
                  ->references('id')
                  ->on('adminusers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table("products", function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
};
