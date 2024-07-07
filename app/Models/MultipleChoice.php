<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class MultipleChoice extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'multiple_choices';
    protected $fillable = [
        'question_id', 'choice'
    ];

    use HasFactory;

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', '_id');
    }

    
}
