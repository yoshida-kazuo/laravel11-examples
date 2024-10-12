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
        Schema::create(table: 'cache', callback: function (Blueprint $table) {
            $table->string(column: 'key')
                ->primary()
                ->comment(comment: 'キー');
            $table->mediumText(column: 'value')
                ->comment(comment: '値');
            $table->integer(column: 'expiration')
                ->comment(comment: '有効期限');
        });

        Schema::create(table: 'cache_locks', callback: function (Blueprint $table) {
            $table->string(column: 'key')
                ->primary()
                ->comment(comment: 'キー');
            $table->string(column: 'owner')
                ->comment(comment: '所有者');
            $table->integer(column: 'expiration')
                ->comment(comment: '有効期限');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'cache');
        Schema::dropIfExists(table: 'cache_locks');
    }
};
