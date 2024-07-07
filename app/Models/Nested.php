<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Nested extends Model
{

    protected $connection = 'mongodb';
    protected $collection = 'nesteds';

    protected $fillable = [
        "question_id",
        "nested_question_id"
    ];


    use HasFactory;

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', '_id');
    }

    public function nestedQuestion()
    {
        return $this->belongsTo(NestedQuestion::class, 'nested_question_id', '_id');
    }
}
