<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Quiz extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'quizs';

    protected $fillable = [
        'quiz_name','quiz_type_id','order'
    ];
    use HasFactory;

    public function type(){
        return $this->belongsTo(QuizType::class,'quiz_type_id','_id');
    }

    public function questions(){
        return $this->hasMany(QuizQuestion::class,'quiz_id','_id');
    }
    
    public function quiz_claim(){
        return $this->hasMany(QuizClaim::class,'quiz_id','_id');
    }
}
