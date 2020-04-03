<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable = [
        'name',
        'account_id',
        'color',
        'created_at',
        'updated_at'
    ];

    public function account() {
        return $this->belongsTo('App\Account');
    }

}
