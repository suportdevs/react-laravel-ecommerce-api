<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "products";
    
    protected $guarded = ['id'];

    public function scopeFilter($query, $filters) {
        $query->when($filters->category ?? false, function ($query, $category) {
            $categoryId = Category::where('slug', $category)->first()->id ?? NULL;
            $query->whereJsonContains('categories', $categoryId);
        });
    }

}
