<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;


class QuizContent extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'quiz_contents';

    protected $fillable = [
        'quiz_question_id', 'content'
    ];

    public function question(){
        return $this->belongsTo(QuizQuestion::class,'quiz_question_id','_id');
    }

    
    public function options(){
        return $this->hasMany(QuizOption::class, 'quiz_content_id','_id');
    }

    public function answer_key(){
        return $this->hasOne(QuizAnswerKey::class,'quiz_content_id','_id');
    }
}
