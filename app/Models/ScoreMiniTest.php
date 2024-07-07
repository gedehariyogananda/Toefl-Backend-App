<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class ScoreMiniTest extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'score_mini_tests';

    protected $fillable = [
        'user_id', 'packet_id', 'akurasi',
    ];
}
