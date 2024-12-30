<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(table: 'activities', callback: function (Blueprint $table) {
            $table->id()
                ->comment(comment: 'ID');
            $table->unsignedBigInteger(column: 'user_id')
                ->nullable()
                ->comment(comment: 'ユーザID')
                ->index();
            $table->string(column: 'type', length: 16)
                ->default(value: 'info')
                ->comment(comment: '操作タイプ [info, create, update, delete, login, logout, error, warning]')
                ->index();
            $table->string(column: 'message', length: 2000)
                ->comment(comment: '操作');
            $table->timestamp(column: 'created_at')
                ->useCurrent()
                ->comment(comment: '操作日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(table: 'activities');
    }
};
