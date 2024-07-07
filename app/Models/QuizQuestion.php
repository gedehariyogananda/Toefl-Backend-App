<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;


class QuizQuestion extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'quiz_questions';

    protected $fillable = [
        'quiz_id','question'
    ];

    public function quiz(){
        return $this->belongsTo(Quiz::class,'quiz_id','_id');
    }

    public function content(){
        return $this->hasMany(QuizContent::class, 'quiz_question_id','_id');
    }
    
}
