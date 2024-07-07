<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameAnswer;
use App\Models\GameClaim;
use App\Models\GameSet;
use App\Models\Quiz;
use App\Models\QuizAnswerKey;
use App\Models\QuizContent;
use App\Models\QuizQuestion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class GameAnswerController extends Controller
{
    public function store(Request $request)
    {
        try{
            $user = auth()->user();
            $request->validate([
                'quiz_option_id' => 'required',
                'quiz_content_id' => 'required',
                'game_claim_id' => 'required',
            ]);
            
            $game_claim = GameClaim::find($request->game_claim_id);
            $quiz = GameSet::find($game_claim->game_set_id);

            $score = 0;

            $key = QuizAnswerKey::where('quiz_content_id',$request->quiz_content_id)->first();

            $attempt = GameClaim::where('game_set_id', $quiz->_id)->where('user_id',$user->_id)->get();

            if($key->quiz_option_id == $request->quiz_option_id && count($attempt) > 0){
                $score = $score + 10 * 1 / count($attempt);
            }

            $user_answer = new GameAnswer();

            $user_answer->quiz_option_id = $request->quiz_option_id;
            $user_answer->game_claim_id = $request->game_claim_id;
            $user_answer->quiz_content_id = $request->quiz_content_id;
            $user_answer->user_id = $user->_id;
            $user_answer->score = $score;
            $user_answer->created_at = Carbon::now();

            $user_answer->save();

            if($this->isComplete($quiz->quiz_id, $game_claim->_id)){

                $game_claim->is_completed = true;
                $game_claim->save();
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

    function isComplete($quizId, $game_claim_id) {
        $quiz = Quiz::with('questions.content')->find($quizId);
        $claim = GameAnswer::where('game_claim_id',$game_claim_id)->get();

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
    
}
