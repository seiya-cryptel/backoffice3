<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class personRole extends Model
{
    /**
     * Relationships
     */
    public function services()
    {
        return $this->hasMany('App\Models\service', 'person_role_id', 'id');
    }
    public function bills()
    {
        return $this->hasMany('App\Models\bill', 'person_role_id', 'id');
    }
    public function clientPersonRoles()
    {
        return $this->hasMany('App\Models\clientPersonRole', 'person_role_id', 'id');
    }
    public function contractDetails()
    {
        return $this->hasMany('App\Models\contractDetail', 'person_role_id', 'id');
    }   
    public function estimateDetails()
    {
        return $this->hasMany('App\Models\estimateDetail', 'person_role_id', 'id');
    }
    public function billDetails()
    {
        return $this->hasMany('App\Models\billDetail', 'person_role_id', 'id');
    }
    
    /**
     * Attributes
     */
    protected $fillable = [
        'role_cd',
        'role_name',
        'notes',
        'isvalid',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'isvalid' => 'boolean',
    ];
}
