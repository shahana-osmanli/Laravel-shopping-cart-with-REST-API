<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class File extends Model
{
    protected $table = 'files'; 

    protected $fillable = [ 
        'product_id',
        'url',
    ];

    public $timestamps = false;
    
}
