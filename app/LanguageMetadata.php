<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LanguageMetadata extends Model
{
    protected $fillable = [
        'name',
        'family',
        'iso2code',
        'icon'
    ];

}
