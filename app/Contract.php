<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = ['property_id', 'kind_of_person', 'document', 'contractor_email', 'contractor_full_name'];

    public function property()
    {
        return $this->belongsTo('App\Property');
    }
}
