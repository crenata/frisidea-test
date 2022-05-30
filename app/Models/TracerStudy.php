<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerStudy extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "table_user_tracer_study";

    protected $fillable = [
        'school_id', 'name', 'description', 'target_start', 'target_end', 'publication_start', 'publication_end'
    ];
}
