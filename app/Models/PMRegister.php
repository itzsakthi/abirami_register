<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pmregister extends Model
{
    use HasFactory;
    protected $fillable= ['pmid','name','spousename','whatsappnumber','spousenumber','familynickname','address','remark','reference','native'];

}
