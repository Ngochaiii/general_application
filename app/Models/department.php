<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    use HasFactory;

    protected $table = "department";
    protected $fillable = [
        'name',
        'uid',
        'parent_id',
        'manager_by',
        'manager_name',
        'level',
        'contact',
        'description',
        'image',
        'active',
        'created_by',
    ];

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }
}
