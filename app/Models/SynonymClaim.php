<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;


class SynonymClaim extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'synonym_claims';
    protected $fillable = [
        'user_id', 'score', 'synonym_words'
    ];

    use HasFactory;
    
}
