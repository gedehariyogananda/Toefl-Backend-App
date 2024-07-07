<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizTypeController extends Controller
{
    public function index(){
        try{
            $quizTypes = QuizType::with('quiz')->get();

            $counts = $quizTypes->map(function ($quizType) {
                return [
                    'quiz_type_id' => $quizType->_id,
                    'quiz_type_name' => $quizType->name,
                    'quiz_count' => $quizType->quiz->count(),
                ];
            });


            return response()->json([
                'success' => true,
                'data' => $counts
            ]);
            
        }catch(Exception $e){
            return response()->json([
                'success' => true,
                'data' => $e->getMessage()
            ]);
        }
    }

    public function show(String $type_id){
        try{
            $user = auth()->user();
            $quizs = Quiz::with([
                'type',
                'quiz_claim' => function($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orderBy('is_completed', 'asc');
                }
            ])->where('quiz_type_id',$type_id)->get();
    
            return response()->json([
                'success' => true,
                'data' => $quizs
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'data' => $e->getMessage()
            ]);
        }
    }
}
