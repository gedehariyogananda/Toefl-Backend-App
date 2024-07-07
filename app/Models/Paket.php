<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Paket extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'packets';
    protected $fillable = [
        'name_packet',
        'tipe_test_packet',
        'no_packet',
    ];
    use HasFactory;

    public function questions()
    {
        return $this->hasMany(Question::class, 'packet_id', '_id');
    }

    // public function answers()
    // {
    //     return $this->hasMany(Answer::class, 'packet_id', 'packet_id');
    // }

}
