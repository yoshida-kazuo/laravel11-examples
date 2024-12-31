<?php

namespace App\Models\Trait;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

trait Timezone
{

    /**
     * フォーマットされたタイムゾーンを返す
     *
     * @param mixed $time 日時情報（タイムゾーンを変換する値）
     * @return String|null タイムゾーンが変換され、指定フォーマットで返される日時文字列。nullが渡された場合はnullを返す。
     */
    protected function formatToTimezone(mixed $time): String|null
    {
        return ! is_null($time) ? Carbon::parse($time)
            ->setTimezone(config('app.timezone_view'))
            ->format('Y/m/d H:i:s') : null;
    }

    /**
     * 指定されたカラムに対して共通のタイムゾーンフォーマット処理を適用する
     *
     * @return Attribute LaravelのAttributeオブジェクトを返す
     */
    protected function handleTimezoneColumn(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->formatToTimezone($value)
        );
    }

    /**
     * created_atカラムをタイムゾーンに基づいてフォーマットする
     *
     * @return Attribute
     */
    protected function createdAt(): Attribute
    {
        return $this->handleTimezoneColumn();
    }

    /**
     * updated_atカラムをタイムゾーンに基づいてフォーマットする
     *
     * @return Attribute
     */
    protected function updatedAt(): Attribute
    {
        return $this->handleTimezoneColumn();
    }

    /**
     * deleted_atカラムをタイムゾーンに基づいてフォーマットする
     *
     * @return Attribute
     */
    protected function deletedAt(): Attribute
    {
        return $this->handleTimezoneColumn();
    }

    /**
     * email_verified_atカラムをタイムゾーンに基づいてフォーマットする
     *
     * @return Attribute
     */
    protected function emailVerifiedAt(): Attribute
    {
        return $this->handleTimezoneColumn();
    }

    /**
     * last_used_atカラムをタイムゾーンに基づいてフォーマットする
     *
     * @return Attribute
     */
    protected function lastUsedAt(): Attribute
    {
        return $this->handleTimezoneColumn();
    }

    /**
     * expires_atカラムをタイムゾーンに基づいてフォーマットする
     *
     * @return Attribute
     */
    protected function expiresAt(): Attribute
    {
        return $this->handleTimezoneColumn();
    }

    /**
     * failed_atカラムをタイムゾーンに基づいてフォーマットする
     *
     * @return Attribute
     */
    protected function failedAt(): Attribute
    {
        return $this->handleTimezoneColumn();
    }

    /**
     * login_ban_atカラムをタイムゾーンに基づいてフォーマットする
     *
     * @return Attribute
     */
    protected function loginBanAt(): Attribute
    {
        return $this->handleTimezoneColumn();
    }

}
