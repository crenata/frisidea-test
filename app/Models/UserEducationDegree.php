<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEducationDegree extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $table = "table_user_education_degree";
}
