<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScrambledClaim;
use App\Models\Word;
use Exception;
use Illuminate\Http\Request;

class ScrambledWordController extends Controller
{
    public function index(){
        
        try{
            $word = Word::take(10)->skip(rand(0,12000))->get();

            return response()->json([
                'success'=> true,
                'data' => $word
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'data'=> false
            ]);   
        }
        
        
    }

    public function store(Request $request){
        try{
            $request->validate([
                'word_id' => 'required',
                'is_true' => 'required',
            ]);

            $user = auth()->user();
            $scrambledClaim = new ScrambledClaim();


            $scrambledClaim->word_id = $request->word_id;
            $scrambledClaim->is_true = $request->is_true;
            $scrambledClaim->user_id = $user->_id;

            $scrambledClaim->save();

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
