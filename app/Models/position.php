<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class position extends Model
{
    use HasFactory;
    protected $table = "position";
    protected $fillable = [
        'name',
        'department_id',
        'parent_id',
        'manager_by',
        'position_rank',
        'level',
        'description',
        'image',
        'active',
        'total_employee',
        'total_candidate',
        'recruiting',
        'created_by',
    ];

    public function parent()
    {
        return $this->belongsTo(Position::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Position::class, 'parent_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class , 'manager_by', 'id');
    }
}
