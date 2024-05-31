<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stocks',
        'user_id'
    ];

    protected $guarded = ['id'];

    protected $table = 'products';

    public function user(){
        return $this->belongsTo(User::class);
    }
}
