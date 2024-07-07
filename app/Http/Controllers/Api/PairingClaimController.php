<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Synonym;
use App\Models\SynonymClaim;
use App\Models\Word;
use Exception;
use Illuminate\Http\Request;

class PairingClaimController extends Controller
{

    public function store(Request $request){
        try{
            $request->validate([
                'synonym_words' => 'array|required',
                'score' => 'required',
            ]);

            $user = auth()->user();
            $synonymClaim = new SynonymClaim();


            $synonymClaim->synonym_words = $request->synonym_words;
            $synonymClaim->score = $request->score;
            $synonymClaim->user_id = $user->_id;

            $synonymClaim->save();

            return response()->json([
                'success'=> true,
                'data' => true
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'data'=> false
            ]);
        }
    }
}
