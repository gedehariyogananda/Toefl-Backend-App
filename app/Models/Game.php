<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Game extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'games';

    protected $fillable = [
        'level','title','description'
    ];
    use HasFactory;

    public function game_list(){
        return $this->hasMany(GameSet::class,'game_id','_id');
    }
}
