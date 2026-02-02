<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yelam extends Model
{
    use HasFactory;
    protected $fillable= ['pulliid','yelamporul','value','name','nameguest','whatsappno','native','whatsappnoguest','nativeguest','pulliidref'];

}
