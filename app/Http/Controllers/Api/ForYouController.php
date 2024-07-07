<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Exception;
use Illuminate\Http\Request;

class ForYouController extends Controller
{
    public function index(){
        

        try {
            $allQuizzes = Quiz::with('type')->get();
            $quizs = [];
        
            $randomQuiz = $allQuizzes->random();
            $quizs[] = $randomQuiz;
        
            $types = ['Vocabulary', 'Grammar', 'Reading', 'Listening'];
        
            function getRandomQuizByType($type) {
                $quizzesByType = Quiz::with('type')->whereHas('type', function($q) use ($type) {
                    $q->where('name', $type);
                })->get();
        
                if ($quizzesByType->isNotEmpty()) {
                    return $quizzesByType->random();
                }
        
                return null;
            }
        
            foreach ($types as $type) {
                $quizByType = getRandomQuizByType($type);
                if ($quizByType) {
                    $quizs[] = $quizByType;
                }
            }
        
            return response()->json([
                'success' => true,
                'data' => $quizs
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => $e->getMessage()
            ]);
        }
        
    }
}
