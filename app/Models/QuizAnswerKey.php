<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;


class QuizAnswerKey extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'quiz_answer_keys';

    protected $fillable = [
        'quiz_content_id','quiz_option_id', 'explanation'
    ];

    public function content(){
        return $this->belongsTo(QuizContent::class,'quiz_content_id','_id');
    }

    public function option(){
        return $this->belongsTo(QuizOption::class,'quiz_option_id','_id');
    }
}
