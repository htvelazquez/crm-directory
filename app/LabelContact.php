<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabelContact extends Model
{
    protected $fillable = [
        'contact_id',
        'label_id'
    ];

    public function contact() {
        return $this->belongsTo('App\Contact');
    }

    public function label() {
        return $this->belongsTo('App\Label');
    }

}
