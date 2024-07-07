<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $connection = "mongodb";
    protected $collection = "targets";

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
