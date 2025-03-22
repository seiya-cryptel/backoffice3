<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class service extends Model
{
    /**
     * Relationships
     */
    public function personRole()
    {
        return $this->belongsTo('App\Models\personRole', 'person_role_id', 'id');
    }
    public function contractDtails()
    {
        return $this->hasMany('App\Models\contractDtails', 'svc_cd', 'svc_cd');
    }
    public function estimteDtails()
    {
        return $this->hasMany('App\Models\estimteDtails', 'svc_cd', 'svc_cd');
    }
    public function billDtails()
    {
        return $this->hasMany('App\Models\billDtails', 'svc_cd', 'svc_cd');
    }

    /**
     * Attributes
     */
    protected $fillable = [
        'svc_cd',
        'svc_name',
        'person_role_id',
        'svc_title_cover',
        'svc_title_bill',
        'svc_unit_price',
        'svc_quantity',
        'svc_unit',
        'svc_tax_type',
        'svc_acc_item',
        'notes',
        'isvalid',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'person_role_id' => 'integer',
        'svc_unit_price' => 'decimal:4',
        'svc_quantity' => 'decimal:4',
        'svc_tax_type' => 'integer',
        'isvalid' => 'boolean',
    ];
}
