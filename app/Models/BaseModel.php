<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function createdAt()
    {
        return date('d/m/Y', strtotime($this->created_at));
    }

    public function createdDatetime()
    {
        return date('d/m/Y H:i:s', strtotime($this->created_at));
    }

    public function displayDate($datetime)
    {
        return date('Y-m-d', strtotime($datetime));
    }

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

}