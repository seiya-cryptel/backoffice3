<?php

namespace App\Traits;

/**
 * Trait Utilities
 * @package App\Traits
 * 共通汎用処理
 */
trait Utilities
{
    /**
     * 編集済み数値文字列を数値に変換する
     * @param string $value
     * @return float|null
     */
    public function UtlStr2Number($value, $nullValue = null)
    {
        // 全角文字を半角に変換
        $value = trim(mb_convert_kana($value, 'as'));   // 英数字と記号、空白を半角に変換
        // マイナス値の検知
        $sign = substr($value, 0, 1) === '-' ? -1 : 1;
        // 数字とピリオド以外の文字を削除
        $value = preg_replace('/[^0-9.]/', '', $value);
        // 最初のピリオドのみ有効
        $parts = explode('.', $value);
        $value = empty($parts[1]) ? $parts[0] : ($parts[0] . '.' . $parts[1]);
        return $value === '' ? $nullValue : ($value * $sign);
    }

    /**
     * 数値を小数部を考慮して、編集済み数値文字列に変換する
     * @param float $value
     * @param int $decimal  小数点以下編集桁数
     * @return string
     */
    public function UtlNumber2Str($value, $decimal = 0)
    {
        if (is_null($value)) {
            return '';
        }
        $decimal = fmod($value, 1) ? $decimal : 0;
        return number_format($value, $decimal);
    }
}