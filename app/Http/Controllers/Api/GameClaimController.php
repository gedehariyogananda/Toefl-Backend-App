<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GameClaim;
use App\Models\GameSet;
use App\Models\Quiz;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameClaimController extends Controller
{
    /**
     * Display a listing of the resource. History
     */
    public function index()
    {
        try{
            $user = auth()->user();
            $user_games = GameClaim::with('user','game_set.quiz','game_set.game')->where('user_id', $user->_id)->get();

            return response()->json(['data' => $user_games]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'data' => null]);
        }
    }

    /**
     * Show the form for creating a new resource. Check is there any Claim sudah ada, optional :D
     */
    public function create()
    {
        try{
            $user = auth()->user();
            $user_games = GameClaim::with('user','game_set.quiz','game_set.game')->where('user_id', $user->_id)->get();
            if(empty($user_games)){
                return response()->json(['success' => true, 'data' => true]);   
            }
            return response()->json(['success'=> true, 'data' => false]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'data' => false]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'game_set_id' => 'required',
            ]);
            

            $user = auth()->user();
            $game_set = GameSet::find($request->game_set_id);
            if (!$game_set) {
                return response()->json(['success' => false, 'data' => null, 'message' => 'Game set not found'], 404);
            }

            $quiz = Quiz::with('type', 'questions.content.options', 'questions.content.answer_key.option')
                        ->find($game_set->quiz_id);

            $exist_claim = GameClaim::with('game_answer')->where('game_set_id',$request->game_set_id)->where('user_id',$user->_id)->where('is_completed',false)->first();
            
            if(!$exist_claim){      
                // DB::beginTransaction(); the hell mongo ribet
                
                $user_game = new GameClaim();
                
                $user_game->user_id = $user->id;
                $user_game->game_set_id = $request->game_set_id;
                $user_game->is_completed = false;
                $user_game->save();
                // DB::commit();
            }
        
            
            
            return response()->json([
                'success' => true,
                'data' => [
                    'claimId' => !$exist_claim ? $user_game->_id : $exist_claim->_id,
                    'user_answer' => !$exist_claim ? [] : ($exist_claim->game_answer ?? []),
                    'quiz' => $quiz,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'data' => null, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = auth()->user();
            $game_set = GameSet::find($id);
            
            $game_claims = GameClaim::with('game_answer')->where('game_set_id',$id)->where('user_id',$user->_id)->where('is_completed',true)->get();
            
            $claim_id = '';
            $maxScore = 0;

            $quiz = Quiz::with('type', 'questions.content.options', 'questions.content.answer_key.option')
                        ->find($game_set->quiz_id);
            

            foreach ($game_claims as $game_claim) {
                $totalScore = 0;
                foreach ($game_claim->game_answer as $game_answer) {
                    $totalScore += $game_answer->score;
                }

                if ($totalScore > $maxScore) {
                    $maxScore = $totalScore;
                    $claim_id = $game_claim->_id; 
                }
            }

            $highestQuizClaim = GameClaim::with('game_answer')->find($claim_id);
        
            
            
            return response()->json([
                'success' => true,
                'data' => [
                    'claimId' => $claim_id,
                    'user_answer' => $highestQuizClaim->game_answer,
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
        try{
            $user = auth()->user();
            $user_games = GameClaim::with('user','game_set.quiz','game_set.game')->where('user_id',$user->_id)->where('_id',$id)->first();
                
           
            return response()->json(['success' => true, 'data' => $user_games]);   
        }catch(Exception $e){
            return response()->json(['success' => false, 'data' => []]);
        }
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
