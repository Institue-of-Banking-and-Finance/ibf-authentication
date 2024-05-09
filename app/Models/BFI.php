<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BFI extends Model
{
    use HasFactory;
    protected $table = 'bfis';
    protected $fillable = ['name' , 'type'];

    /**
     * Get all of the comments for the BFI
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'bfi_id', 'id');
    }
}
