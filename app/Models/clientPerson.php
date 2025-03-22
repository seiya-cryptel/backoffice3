<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class clientPerson extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_persons'; // ここにベーステーブル名を指定
    
    /**
     * Relationships
     */
    public function client()
    {
        return $this->belongsTo('App\Models\client', 'client_id', 'id');
    }
    public function clientPersonRoles()
    {
        return $this->hasMany('App\Models\clientPersonRole', 'client_person_id', 'id');
    }
    
    /**
     * Attributes
     */
    protected $fillable = [
        'client_id',
        'psn_order',
        'psn_name',
        'psn_email',
        'psn_zip',
        'psn_addr1',
        'psn_addr2',
        'psn_tel',
        'psn_fax',
        'psn_branch',
        'psn_title',
        'psn_keisho',
        'notes',
        'isvalid',
    ];
    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'client_id' => 'integer',
        'psn_order' => 'integer',
        'isvalid' => 'boolean',
    ];
}
