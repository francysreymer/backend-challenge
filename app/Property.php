<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = ['owner_email', 'address', 'number', 'complement', 'neighborhood', 'city', 'state', 'active'];
    
    public function contract()
    {
        return $this->hasOne('App\Contract');
    }
}
