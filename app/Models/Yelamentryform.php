<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yelamentryform extends Model
{
    use HasFactory;
    protected $fillable= ['yelamtype','receipt_id','credit','pulliid','yelamporul','value','name','nameguest','whatsappno','native','bookid','hardcopy','payment','reference','remark','whatsappnoguest','nativeguest'];
   
    protected $attributes = [
        'bookid' => 0,
    ];
}