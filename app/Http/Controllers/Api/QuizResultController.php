<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\GameAnswer;
use App\Models\GameClaim;
use App\Models\QuizAnswer;
use App\Models\QuizAnswerKey;
use App\Models\QuizClaim;
use Exception;
use Illuminate\Http\Request;

class QuizResultController extends Controller
{
    public function store(Request $request){
        try{
            $request->validate([
                'claim_id' => 'required',
                'is_game' => 'required'
            ]);
            
            $answerTrue = 0;

            if($request->is_game){
                $claim = GameAnswer::where('game_claim_id',$request->claim_id)->get();
                
            }else{
                $claim = QuizAnswer::where('quiz_claim_id',$request->claim_id)->get();
            }

            foreach($claim as $c){
                if($c->quiz_option_id == QuizAnswerKey::where('quiz_content_id', $c->quiz_content_id)->first()->quiz_option_id){
                    $answerTrue++;
                }
            }
            
            $len = count($claim);
            $scoreSum = $claim->sum('score');

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $len,
                    'benar' => $answerTrue,
                    'score' => $scoreSum,
                ]
            ]);

        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'data' => [
                    'total' => 1,
                    'benar' => 1,
                    'score' => 0,
                ]
            ]);
        }
    }
}
