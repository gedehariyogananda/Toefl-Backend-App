<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class UserScorer extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'user_scorers';
    protected $fillable = [
        'user_id', 'packet_id', 'akurasi', 'level_profiency', 'score_toefl', 'score_listening', 'score_structure', 'score_reading'
    ];

    use HasFactory;
}
