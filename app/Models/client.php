<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class client extends Model
{
    /**
     * Relationships
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clientGroup()
    {
        return $this->belongsTo('App\Models\clientGroup', 'client_group_id', 'id');
    }
    public function clientPersons()
    {
        return $this->hasMany('App\Models\clientPerson', 'client_id', 'id');
    }
    public function contracts()
    {
        return $this->hasMany('App\Models\contract', 'client_id', 'id');
    }
    public function estimates()
    {
        return $this->hasMany('App\Models\estimate', 'client_id', 'id');
    }
    public function bills()
    {
        return $this->hasMany('App\Models\bill', 'client_id', 'id');
    }
    
    /**
     * Attributes
     * 
     * @var array
     */
    protected $fillable = [
        'cl_mstno',
        'cl_full_name',
        'cl_short_name',
        'cl_kana_name',
        'client_group_id',
        'cl_zip',
        'cl_addr1',
        'cl_addr2',
        'cl_tel',
        'cl_fax',
        'notes',
        'isvalid',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'client_group_id' => 'integer',
        'isvalid' => 'boolean',
    ];
}
