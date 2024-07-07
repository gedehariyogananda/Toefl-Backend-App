<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;


class Word extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'word_lists';
    protected $fillable = [
        'Answer', "Meaning", "Examples/0",
        "Examples/1",
        "Examples/2",
        "Examples/3",
        "Examples/4",
        "Examples/5",
        "Examples/6",
        "Examples/7",
        "Examples/8",
        "Examples/9"
    ];

    use HasFactory;


    
}
