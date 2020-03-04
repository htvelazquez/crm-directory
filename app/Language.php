<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name',
        'label',
        'iso2code',
        'created_at',
        'updated_at'
    ];

    public function metadata() {
        return $this->belongsTo('App\LanguageMetadata');
    }

}
