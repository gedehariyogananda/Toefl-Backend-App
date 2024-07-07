<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class GameSet extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'game_sets';

    protected $fillable = [
        'quiz_id','game_id'
    ];
    use HasFactory;

    public function quiz(){
        return $this->belongsTo(Quiz::class,'quiz_id','_id');
    }
    
    public function game(){
        return $this->belongsTo(Game::class,'game_id','_id');
    }

    public function game_claim(){
        return $this->hasMany(GameClaim::class,'game_set_id', '_id');
    }
}
