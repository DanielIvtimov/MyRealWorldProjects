<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'image',
        'showHome',
    ];

    public function sub_category(){
        return $this->hasMany(SubCategory::class);
    }
}


