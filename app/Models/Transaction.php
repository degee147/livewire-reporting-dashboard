<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'date',
        'description',
        'status',
    ];

    /**
     * A transaction belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A transaction belongs to a category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
