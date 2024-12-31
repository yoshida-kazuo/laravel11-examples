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
        Schema::create(table: 'users', callback: function (Blueprint $table) {
            $table->id()
                ->comment(comment: 'ID');
            $table->unsignedInteger(column: 'role_id')
                ->default(default: 9)
                ->index()
                ->comment(comment: '権限ID');
            $table->string(column: 'name')
                ->comment(comment: '名前');
            $table->string(column: 'email')
                ->unique()
                ->comment(comment: 'メールアドレス');
            $table->timestamp(column: 'email_verified_at')
                ->nullable()
                ->comment(comment: 'メールアドレス確認日時');
            $table->string(column: 'password')
                ->comment(comment: 'パスワード');
            $table->rememberToken()
                ->comment(comment: 'ログイン維持トークン');
            $table->timestamp(column: 'login_ban_at')
                ->nullable()
                ->index()
                ->comment(comment: 'ログイン禁止日時');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create(table: 'password_reset_tokens', callback: function (Blueprint $table) {
            $table->string(column: 'email')
                ->primary()
                ->comment(comment: 'メールアドレス');
            $table->string(column: 'token')
                ->comment(comment: 'トークン');
            $table->timestamp(column: 'created_at')
                ->nullable()
                ->comment(comment: '作成日時');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string(column: 'id')
                ->primary()
                ->comment(comment: 'ID');
            $table->foreignId(column: 'user_id')
                ->nullable()
                ->index()
                ->comment(comment: 'ユーザID');
            $table->string(
                    column: 'ip_address',
                    length: 45
                )
                ->nullable()
                ->comment(comment: 'IPアドレス');
            $table->text(column: 'user_agent')
                ->nullable()
                ->comment(comment: 'ユーザエージェント');
            $table->longText(column: 'payload')
                ->comment(comment: 'ペイロード');
            $table->integer('last_activity')
                ->index()
                ->comment(comment: '最終アクティビティ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'users');
        Schema::dropIfExists(table: 'password_reset_tokens');
        Schema::dropIfExists(table: 'sessions');
    }
};
