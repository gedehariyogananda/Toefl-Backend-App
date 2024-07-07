<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Exception;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(){
        
        try{
            $quizs = Quiz::with('type','questions.content.options','questions.content.answer_key.option')->get();
            
            return response()->json(['data' => $quizs]);
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    

    public function store(Request $request){
        try{
            $validated = $request->validate([
                'name' => 'required',
                'type' => 'required',
                // 'questions' => 'required',
                'questions.*' => 'required',
                // 'questions.*.options' => 'required',
            ]);

    
        }catch(Exception $e){

            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $quiz = Quiz::with('type','questions.content.options','questions.content.answer_key.option')->find($id);

            return response()->json([
                'data' => $quiz
            ]);

        }catch(Exception $e){
            return response()->json([
                'data' => 'lol'
            ]); 
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
