<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class UserAnswer extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'user_answers';
    protected $fillable = [
        'user_id', 'packet_id', 'question_id', 'bookmark', 'answer_user', 'correct'
    ];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function packet()
    {
        return $this->belongsTo(Paket::class, 'packet_id', '_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', '_id');
    }

    public function answer()
    {
        return $this->hasMany(Answer::class, 'question_id', 'question_id');
    }
}
