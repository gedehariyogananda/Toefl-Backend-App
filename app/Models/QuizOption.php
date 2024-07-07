<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;


class QuizOption extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'quiz_options';

    protected $fillable = [
        'quiz_content_id', 'options'
    ];

    public function content(){
        return $this->belongsTo(QuizContent::class,'quiz_content_id','_id');
    }
}
