<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;


class QuizType extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'quiz_types';

    protected $fillable = [
        'name', 'desc'
    ];

    public function quiz(){
        return $this->hasMany(Quiz::class,'quiz_type_id','_id');
    }

    

}
