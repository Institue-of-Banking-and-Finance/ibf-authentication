<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Kitar\Dynamodb\Model\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'title' , 'price' , 'description' ,
    ];

}
