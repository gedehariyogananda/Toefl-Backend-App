<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAnswerKey;
use App\Models\QuizClaim;
use App\Models\QuizOption;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class QuizAnswerController extends Controller
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
            $user = auth()->user();
            $request->validate([
                'quiz_option_id' => 'required',
                'quiz_content_id' => 'required',
                'quiz_claim_id' => 'required',
            ]);
            

            $quiz_claim = QuizClaim::find($request->quiz_claim_id);
            $score = 0;

            $key = QuizAnswerKey::where('quiz_content_id',$request->quiz_content_id)->first();

            $attempt = QuizClaim::where('quiz_id',$quiz_claim->quiz_id)->where('user_id',$user->_id)->get();
            if($key->quiz_option_id == $request->quiz_option_id && count($attempt) > 0){
                $score = $score + 10 * 1 / count($attempt);
            }

            $user_answer = new QuizAnswer();

            $user_answer->quiz_option_id = $request->quiz_option_id;
            $user_answer->quiz_claim_id = $request->quiz_claim_id;
            $user_answer->quiz_content_id = $request->quiz_content_id;
            $user_answer->user_id = $user->_id;
            $user_answer->score = $score;
            $user_answer->created_at = Carbon::now();

            $user_answer->save();

            if($this->isComplete($quiz_claim->quiz_id, $quiz_claim->_id)){
                $quiz_claim->is_completed = true;
                $quiz_claim->save();
            }

            return response()->json([
                'success' => true,
                'data' => true
            ]);

        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'data' => false
            ]);
        }
    }

    function isComplete($quizId, $quiz_claim_id) {
        $quiz = Quiz::with('questions.content')->find($quizId);
        $claim = QuizAnswer::where('quiz_claim_id',$quiz_claim_id)->get();

        if (!$quiz) {
            echo "Quiz not found.";
            return;
        }
        $total = 0;
        foreach($quiz->questions as $q){
            $total = $total + count($q->content);
        }
        
        return $total == count($claim);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
