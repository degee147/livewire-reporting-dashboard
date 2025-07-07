<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Drop old category column
            if (Schema::hasColumn('transactions', 'category')) {
                $table->dropColumn('category');
            }
            // Add foreign key category_id
            $table->foreignId('category_id')
                ->after('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            // Optional: Add index explicitly
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropIndex(['category_id']);
            $table->dropColumn('category_id');

            // Restore original column if needed
            $table->string('category')->nullable();
        });
    }
};
