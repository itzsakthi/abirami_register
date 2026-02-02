<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class member extends Model
{
    use HasFactory;
    protected $fillable= ['name','email','phone','address','slug','company_name','image','address_2','landline','dob','remarks','profile_text'];

}
