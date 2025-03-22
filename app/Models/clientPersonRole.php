<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class clientPersonRole extends Model
{
    /**
     * Relationships
     */
    public function clientPerson()
    {
        return $this->belongTo('App\Models\clientPerson', 'client_person_id', 'id');
    }
    public function personRole()
    {
        return $this->belongTo('App\Models\PersonRole', 'person_role_id', 'id');
    }
    /**
     * Attributes
     */
    protected $fillable = [
        'client_person_id',
        'person_role_id',
        'post_to',
        'notes',
        'isvalid',
    ];
    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'client_person_id' => 'integer',
        'person_role_id' => 'integer',
        'post_to' => 'boolean',
        'isvalid' => 'boolean',
    ];
}
