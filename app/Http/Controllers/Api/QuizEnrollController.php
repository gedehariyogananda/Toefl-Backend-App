<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizClaim;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizEnrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{

            $request->validate([
                'quiz_id' => 'required'
            ]);
            $user_id = auth()->user()->_id;

            $quiz = Quiz::with('type','questions.content.options','questions.content.answer_key.option')->find($request->quiz_id);
            
            if (!$quiz) {
                return response()->json(['success' => false, 'data' => null, 'message' => 'Quiz not found'], 404);
            }

            $exist_claim = QuizClaim::with('quiz_answer')->where('quiz_id',$request->quiz_id)->where('is_completed',false)->first();

            if(!$exist_claim){

                // DB::beginTransaction();
                $claim_quiz = new QuizClaim();
                
                $claim_quiz->quiz_id = $request->quiz_id;
                $claim_quiz->quiz_content_id = $request->quiz_content_id;
                $claim_quiz->user_id = $user_id;
                $claim_quiz->is_completed = false;
                
                $claim_quiz->save();
                // DB::commit();
            }


            return response()->json([
                'success' => true,
                'data'=> [
                    'claimId' => !$exist_claim ? $claim_quiz->_id : $exist_claim->_id,
                    'user_answer' => !$exist_claim ? [] : ($exist_claim->quiz_answer ?? []),
                    'quiz' => $quiz
                ]
            ]);
           }catch(Exception $e){
            return response()->json(['success' => false, 'data' => $e->getMessage()]);
           }
           
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = auth()->user();

            $quiz_claims = QuizClaim::with('quiz_answer')->where('quiz_id',$id)->where('user_id',$user->_id)->where('is_completed',true)->get();
            
            $claim_id = '';
            $maxScore = 0;

            $quiz = Quiz::with('type', 'questions.content.options', 'questions.content.answer_key.option')
                        ->find($id);
            

            foreach ($quiz_claims as $quiz_claim) {
                $totalScore = 0;
                foreach ($quiz_claim->quiz_answer as $quiz_answer) {
                    $totalScore += $quiz_answer->score;
                }

                if ($totalScore > $maxScore) {
                    $maxScore = $totalScore;
                    $claim_id = $quiz_claim->_id; 
                }
            }

            $highestQuizClaim = QuizClaim::with('quiz_answer')->find($claim_id);
        
            
            
            return response()->json([
                'success' => true,
                'data' => [
                    'claimId' => $claim_id,
                    'user_answer' => $highestQuizClaim->quiz_answer,
                    'quiz' => $quiz,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'data' => null, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
