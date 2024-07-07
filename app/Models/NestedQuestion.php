<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class NestedQuestion extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'nested_questions';
    protected $fillable = [
        'question_nested', 'packet_id'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'nested_question_id', '_id');
    }

    public function nesteds()
    {
        return $this->hasMany(Nested::class, 'nested_question_id', '_id');
    }
}
