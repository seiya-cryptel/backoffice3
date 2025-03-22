<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class appSetting extends Model
{
    /**
     * Attributes
     */
    protected $fillable = [
        'sys_name',
        'sys_index',
        'sys_istext',
        'sys_txtval',
        'sys_numval',
        'notes',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sys_index' => 'integer',
        'sys_istext' => 'boolean',
        'sys_numval' => 'float',
    ];

    /**
     * 変数名とインデクスで値を取得する
     */
    public static function getSetting($sys_name, $sys_index = 0)
    {
        $setting = self::where('sys_name', $sys_name)
            ->where('sys_index', $sys_index)
            ->first();
        return $setting->sys_istext ? $setting->sys_txtval : $setting->sys_numval;
    }
}
