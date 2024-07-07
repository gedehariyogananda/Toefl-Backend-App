<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class LevelProficiencyUser extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $table = 'level_proficiency_users';
    protected $fillable = [
        'user_id', 'packet_id', 'level_proficiency', 'score'
    ];
}
