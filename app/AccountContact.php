<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountContact extends Model
{
    protected $fillable = [
        'account_id',
        'contact_id',
        'comments',
        'updated_by',
        'created_by',
        'created_at',
        'updated_at'
    ];

    public function contact() {
        return $this->belongsTo('App\Contact');
    }

    public function account() {
        return $this->belongsTo('App\Account');
    }

    public function createdBy() {
        return $this->belongsTo('App\User');
    }

    public function updatedBy() {
        return $this->belongsTo('App\User');
    }
}
