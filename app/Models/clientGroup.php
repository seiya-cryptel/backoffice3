<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class clientGroup extends Model
{
    /**
     * Relationships
     */
    public function clients()
    {
        return $this->hasMany('App\Models\client', 'client_group_id', 'id');
    }
    
    /**
     * Attributes
     */
    protected $fillable = [
        'cl_group_cd',
        'cl_group_name',
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
