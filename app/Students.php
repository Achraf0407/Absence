<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Tables\app\Traits\TableCache;

class Students extends Model
{
    use TableCache;
    
    protected $fillable = [
        'firstname', 'lastname', 'subject' ,'prof'
    ];
    
}
