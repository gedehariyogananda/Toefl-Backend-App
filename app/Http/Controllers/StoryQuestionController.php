<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StoryQuestionController extends Controller
{
    public function index()
    {
        return view('nestedquestion.index');
    }
}
