<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    protected $table = 'paymenthistory';
    protected $fillable= ['yelam_id','amount'];

}
