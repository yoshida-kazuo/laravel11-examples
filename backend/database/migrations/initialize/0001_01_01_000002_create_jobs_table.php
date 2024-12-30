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
        Schema::create(table: 'jobs', callback: function (Blueprint $table) {
            $table->id()
                ->comment(comment: 'ID');
            $table->string(column: 'queue')
                ->index()
                ->comment(comment: 'キュー');
            $table->longText(column: 'payload')
                ->comment(comment: 'ペイロード');
            $table->unsignedTinyInteger(column: 'attempts')
                ->comment(comment: '試行回数');
            $table->unsignedInteger(column: 'reserved_at')
                ->nullable()
                ->comment(comment: '予約日時');
            $table->unsignedInteger(column: 'available_at')
                ->comment(comment: '利用可能日時');
            $table->unsignedInteger(column: 'created_at')
                ->comment(comment: '作成日時');
        });

        Schema::create(table: 'job_batches', callback: function (Blueprint $table) {
            $table->string(column: 'id')
                ->primary()
                ->comment(comment: 'ID');
            $table->string(column: 'name')
                ->comment(comment: '名前');
            $table->integer(column: 'total_jobs')
                ->comment(comment: '合計ジョブ数');
            $table->integer(column: 'pending_jobs')
                ->comment(comment: '保留中ジョブ数');
            $table->integer(column: 'failed_jobs')
                ->comment(comment: '失敗ジョブ数');
            $table->longText(column: 'failed_job_ids')
                ->comment(comment: '失敗ジョブID');
            $table->mediumText(column: 'options')
                ->nullable()
                ->comment(comment: 'オプション');
            $table->integer(column: 'cancelled_at')
                ->nullable()
                ->comment(comment: 'キャンセル日時');
            $table->integer(column: 'created_at')
                ->comment(comment: '作成日時');
            $table->integer(column: 'finished_at')
                ->nullable()
                ->comment(comment: '完了日時');
        });

        Schema::create(table: 'failed_jobs', callback: function (Blueprint $table) {
            $table->id()
                ->comment(comment: 'ID');
            $table->string(column: 'uuid')
                ->unique()
                ->comment(comment: 'UUID');
            $table->text(column: 'connection')
                ->comment(comment: '接続');
            $table->text(column: 'queue')
                ->comment(comment: 'キュー');
            $table->longText(column: 'payload')
                ->comment(comment: 'ペイロード');
            $table->longText(column: 'exception')
                ->comment(comment: '例外');
            $table->timestamp(column: 'failed_at')
                ->useCurrent()
                ->comment(comment: '失敗日時');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'jobs');
        Schema::dropIfExists(table: 'job_batches');
        Schema::dropIfExists(table: 'failed_jobs');
    }
};
