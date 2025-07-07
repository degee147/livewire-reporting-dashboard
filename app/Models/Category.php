<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * A category has many transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}
