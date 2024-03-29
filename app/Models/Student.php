<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'family_name' , 'given_name' , 'user_id' ,'student_card'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
