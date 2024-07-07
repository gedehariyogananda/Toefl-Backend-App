<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Packet;
use App\Models\Paket;
use App\Models\Question;
use App\Models\ScoreMiniTest;
use App\Models\ToeflScore;
use App\Models\UserAnswer;
use App\Models\UserScorer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    public function submitPacket(Request $request, $idPacket)
    {
        $validate = Validator::make($request->all(), [
            'answers.*.question_id' => 'required|string',
            'answers.*.bookmark' => 'required|boolean',
            'answers.*.answer_user' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 400);
        }

        try {
            $prosentase = 0;
            foreach ($request->answers as $answer) {
                $userAnswer = UserAnswer::create([
                    'packet_id' => $idPacket,
                    'user_id' => auth()->user()->_id,
                    'question_id' => $answer['question_id'],
                    'bookmark' => $answer['bookmark'],
                    'answer_user' => $answer['answer_user'],
                    'correct' => false,
                ]);

                $question = Question::where('packet_id', $idPacket)
                    ->where('_id', $answer['question_id'])
                    ->first();

                if ($question && $question->key_question == $answer['answer_user']) {
                    $userAnswer->correct = true;
                    $userAnswer->save();
                }
            }

            $totalQuestion = Question::where('packet_id', $idPacket)->count();
            $totalCorrect = UserAnswer::where('packet_id', $idPacket)
                ->where('user_id', auth()->user()->_id)
                ->where('correct', true)
                ->count();

            $prosentase = round(($totalCorrect / $totalQuestion) * 100);

            $initCorrect = UserAnswer::where('packet_id', $idPacket)
                ->where('user_id', auth()->user()->_id)
                ->where('correct', true)
                ->get();

            // foreach dataUserInit, cari question yang sesuai lalu cari correct yang benar dari variabel TotalCorect doatas per type soal nya masukkan ke dalam var
            $correctQuestionListening = 0;
            $correctQuestionStructure = 0;
            $correctQuestionReading = 0;

            foreach ($initCorrect as $item) {
                $question = Question::where('_id', $item->question_id)->first();
                if ($question->type_question == 'Listening' && $item->correct == true) {
                    $correctQuestionListening++;
                } elseif ($question->type_question == 'Structure And Written Expression' && $item->correct == true) {
                    $correctQuestionStructure++;
                } elseif ($question->type_question == 'Reading' && $item->correct == true) {
                    $correctQuestionReading++;
                }
            }

            // cek di table toefl_scores masing2 correct question dari reading listening dan structur dicek di table toefl_scores, di column jumlah_benar masing2 di cek masng2 benarnya di column listening untuk listening, lalu strukctur untuk structur dan reading juga. tampikan value yang match di table toefl_scores
            $hasilToeflInitListening = ToeflScore::where('jumlah_benar', $correctQuestionListening)->first();
            $hasilToeflInitStructure = ToeflScore::where('jumlah_benar', $correctQuestionStructure)->first();
            $hasilToeflInitReading = ToeflScore::where('jumlah_benar', $correctQuestionReading)->first();

            $initHasilPure = ($hasilToeflInitListening['listening'] + $hasilToeflInitStructure['structure'] + $hasilToeflInitReading['reading']);
            $hasilKali = $initHasilPure * 10;
            $hasilAkhir = round($hasilKali / 3);

            $hasilSatuanListening = $hasilToeflInitListening['listening'];
            $hasilSatuanStructure = $hasilToeflInitStructure['structure'];
            $hasilSatuanReading = $hasilToeflInitReading['reading'];

            $hasilSatuanListeningKali = $hasilSatuanListening * 10;
            $hasilSatuanStructureKali = $hasilSatuanStructure * 10;
            $hasilSatuanReadingKali = $hasilSatuanReading * 10;

            $hasilAkhirSatuanListening = round($hasilSatuanListeningKali / 3);
            $hasilAkhirSatuanStructure = round($hasilSatuanStructureKali / 3);
            $hasilAkhirSatuanReading = round($hasilSatuanReadingKali / 3);


            $level_profiency = "";
            if ($hasilAkhir < 200) {
                $level_profiency = "ELEMENTARY";
            } elseif ($hasilAkhir >= 200 && $hasilAkhir <= 350) {
                $level_profiency = "PRE-INTERMEDIATE";
            } elseif ($hasilAkhir >= 351 && $hasilAkhir <= 425) {
                $level_profiency = "INTERMEDIATE";
            } elseif ($hasilAkhir >= 426 && $hasilAkhir <= 500) {
                $level_profiency = "PRE-ADVANCED";
            } elseif ($hasilAkhir >= 501 && $hasilAkhir <= 550) {
                $level_profiency = "ADVANCED";
            } elseif ($hasilAkhir > 550) {
                $level_profiency = "SPECIAL ADVANCED";
            }

            $getPacket = Paket::where('_id', $idPacket)->first();

            if ($getPacket['tipe_test_packet'] == "Mini Test") {
                ScoreMiniTest::create([
                     'packet_id' => $idPacket,
                    'user_id' => auth()->user()->_id,
                    'akurasi' => $prosentase,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Answers submitted successfully.',
                    'data' => [
                        'akurasi' => $prosentase,
                        'total_question' => $totalQuestion,
                        'correct_question' => $totalCorrect,
                    ],
                ]);
            }

            UserScorer::create([
                'packet_id' => $idPacket,
                'user_id' => auth()->user()->_id,
                'akurasi' => $prosentase,
                'level_profiency' => $level_profiency,
                'score_toefl' => $hasilAkhir,
                'score_listening' => $hasilAkhirSatuanListening,
                'score_structure' => $hasilAkhirSatuanStructure,
                'score_reading' => $hasilAkhirSatuanReading,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Answers submitted successfully.',
                'data' => [
                    'akurasi' => $prosentase,
                    'correct_question_listening' => $correctQuestionListening,
                    'correct_question_structure' => $correctQuestionStructure,
                    'correct_question_reading' => $correctQuestionReading,
                    'hasil' => $hasilAkhir,
                    'level_profiency' => $level_profiency,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getScoreSubmit($idPacket)
    {
        $userTarget = User::with('target')->where('_id', auth()->user()->_id)->first();
        $getPacket = Paket::where('_id', $idPacket)->first();

        if ($getPacket['tipe_test_packet'] == "Mini Test") {
            $getScore = ScoreMiniTest::where('packet_id', $idPacket)
                ->where('user_id', auth()->user()->_id)
                ->first();
        } else {
            $getScore = UserScorer::where('packet_id', $idPacket)
                ->where('user_id', auth()->user()->_id)
                ->first();
        }

        $countAnsweredUser = UserAnswer::where('user_id', auth()->user()->_id)
            ->where('answer_user', '!=', '-')
            ->where('packet_id', $idPacket)
            ->count();

        $correctQuestion = UserAnswer::where('user_id', auth()->user()->_id)
            ->where('packet_id', $idPacket)
            ->where('correct', true)
            ->count();

        $totalSoal = Question::where('packet_id', $idPacket)->count();

        $jumlahSoalListeningTypeA = Question::where('packet_id', $idPacket)
            ->where('type_question', 'Listening')
            ->where('part_question', 'A')
            ->count();

        $jumlahSoalListeningTypeB = Question::where('packet_id', $idPacket)
            ->where('type_question', 'Listening')
            ->where('part_question', 'B')
            ->count();

        $jumlahSoalListeningTypeC = Question::where('packet_id', $idPacket)
            ->where('type_question', 'Listening')
            ->where('part_question', 'C')
            ->count();

        $jumlahSoalListeningAll = Question::where('packet_id', $idPacket)
            ->where('type_question', 'Listening')
            ->count();

        // ------------------------------------------------- //

        $jumlahSoalStructureTypeA = Question::where('packet_id', $idPacket)
            ->where('type_question', 'Structure And Written Expression')
            ->where('part_question', 'A')
            ->count();

        $jumlahSoalStructureTypeB = Question::where('packet_id', $idPacket)
            ->where('type_question', 'Structure And Written Expression')
            ->where('part_question', 'B')
            ->count();

        $jumlahSoalStructureAll = Question::where('packet_id', $idPacket)
            ->where('type_question', 'Structure And Written Expression')
            ->count();

        // ------------------------------------------------- //

        $jumlahSoalReading = Question::where('packet_id', $idPacket)
            ->where('type_question', 'Reading')
            ->count();

        // ------------------------------------------------- //

        $initQuestionPackerCorrect = UserAnswer::where('user_id', auth()->user()->_id)
            ->where('packet_id', $idPacket)
            ->where('correct', true)
            ->get();

        $correctQuestionListeningTypeA = 0;
        $correctQuestionListeningTypeB = 0;
        $correctQuestionListeningTypeC = 0;
        $correctQuestionStructureTypeA = 0;
        $correctQuestionStructureTypeB = 0;
        $correctQuestionReading = 0;

        foreach ($initQuestionPackerCorrect as $item) {
            $question = Question::where('_id', $item->question_id)->first();
            if ($question->type_question == 'Listening') {
                if ($question->part_question == 'A') {
                    $correctQuestionListeningTypeA++;
                } elseif ($question->part_question == 'B') {
                    $correctQuestionListeningTypeB++;
                } elseif ($question->part_question == 'C') {
                    $correctQuestionListeningTypeC++;
                }
            } elseif ($question->type_question == 'Structure And Written Expression') {
                if ($question->part_question == 'A') {
                    $correctQuestionStructureTypeA++;
                } elseif ($question->part_question == 'B') {
                    $correctQuestionStructureTypeB++;
                }
            } elseif ($question->type_question == 'Reading') {
                $correctQuestionReading++;
            }
        }

        $jumlahCorrectSoalListeningAll = $correctQuestionListeningTypeA + $correctQuestionListeningTypeB + $correctQuestionListeningTypeC;
        $jumlahCorrectSoalStructureAll = $correctQuestionStructureTypeA + $correctQuestionStructureTypeB;
        $jumlahCorrectSoalReading = $correctQuestionReading;

        $accuracyListeningTypeA = round(($correctQuestionListeningTypeA / $jumlahSoalListeningTypeA) * 100);
        $accuracyListeningTypeB = round(($correctQuestionListeningTypeB / $jumlahSoalListeningTypeB) * 100);
        $accuracyListeningTypeC = round(($correctQuestionListeningTypeC / $jumlahSoalListeningTypeC) * 100);

        $accuracyStructureTypeA = round(($correctQuestionStructureTypeA / $jumlahSoalStructureTypeA) * 100);
        $accuracyStructureTypeB = round(($correctQuestionStructureTypeB / $jumlahSoalStructureTypeB) * 100);

        $accuracyReading = round(($correctQuestionReading / $jumlahSoalReading) * 100);


        return response()->json([
            'success' => true,
            'message' => 'Data Score User Has Successfully Fetched.',
            'data' => [
                'id' => $idPacket,
                'score' => $getScore->akurasi,
                'score_toefl' => $getScore->score_toefl ? $getScore->score_toefl : null,
                'target_user' => $userTarget->target ? $userTarget->target->score_target : null,
                'answered_question' => $countAnsweredUser,
                'correct_question_all' => $correctQuestion,
                'total_question_all' => $totalSoal,

                'correct_question_listening_all' => $jumlahCorrectSoalListeningAll,
                'total_question_listening_all' => $jumlahSoalListeningAll,
                'listening_part_a_correct' => $correctQuestionListeningTypeA,
                'total_question_listening_part_a' => $jumlahSoalListeningTypeA,
                'accuracy_listening_part_a' => $accuracyListeningTypeA,

                'correct_question_listening_part_b' => $correctQuestionListeningTypeB,
                'total_question_listening_part_b' => $jumlahSoalListeningTypeB,
                'accuracy_listening_part_b' => $accuracyListeningTypeB,

                'correct_question_listening_part_c' => $correctQuestionListeningTypeC,
                'total_question_listening_part_c' => $jumlahSoalListeningTypeC,
                'accuracy_listening_part_c' => $accuracyListeningTypeC,

                'correct_question_structure_all' => $jumlahCorrectSoalStructureAll,
                'total_question_structure_all' => $jumlahSoalStructureAll,
                'correct_question_structure_part_a' => $correctQuestionStructureTypeA,
                'total_question_structure_part_a' => $jumlahSoalStructureTypeA,
                'accuracy_structure_part_a' => $accuracyStructureTypeA,

                'correct_question_structure_part_b' => $correctQuestionStructureTypeB,
                'total_question_structure_part_b' => $jumlahSoalStructureTypeB,
                'accuracy_structure_part_b' => $accuracyStructureTypeB,

                'correct_question_reading' => $jumlahCorrectSoalReading,
                'total_question_reading' => $jumlahSoalReading,
                'accuracy_reading' => $accuracyReading,
            ],
        ]);
    }

    public function retakeTest(Request $request, $idPacket)
    {
        try {
            $userLog = auth()->user()->_id;

            $validate = Validator::make($request->all(), [
                'answers.*.question_id' => 'required|string',
                'answers.*.bookmark' => 'required|boolean',
                'answers.*.answer_user' => 'required|string',
            ]);

            if ($validate->fails()) {
                return response()->json(['error' => $validate->errors()], 400);
            }

            // drop all data dulu baru insert ulang sesuai request 
            UserAnswer::where('user_id', $userLog)
                ->where('packet_id', $idPacket)
                ->delete();

            UserScorer::where('user_id', $userLog)
                ->where('packet_id', $idPacket)
                ->delete();

            $prosentase = 0;
            foreach ($request->answers as $answer) {
                $userAnswer = UserAnswer::create([
                    'packet_id' => $idPacket,
                    'user_id' => $userLog,
                    'question_id' => $answer['question_id'],
                    'bookmark' => $answer['bookmark'],
                    'answer_user' => $answer['answer_user'],
                    'correct' => false,
                ]);

                $question = Question::where('packet_id', $idPacket)
                    ->where('_id', $answer['question_id'])
                    ->first();

                if ($question && $question->key_question == $answer['answer_user']) {
                    $userAnswer->correct = true;
                    $userAnswer->save();
                }
            }

            $totalQuestion = Question::where('packet_id', $idPacket)->count();
            $totalCorrect = UserAnswer::where('packet_id', $idPacket)
                ->where('user_id', $userLog)
                ->where('correct', true)
                ->count();

            $prosentase = round(($totalCorrect / $totalQuestion) * 100);

            $initCorrect = UserAnswer::where('packet_id', $idPacket)
                ->where('user_id', auth()->user()->_id)
                ->where('correct', true)
                ->get();

            // foreach dataUserInit, cari question yang sesuai lalu cari correct yang benar dari variabel TotalCorect doatas per type soal nya masukkan ke dalam var
            $correctQuestionListening = 0;
            $correctQuestionStructure = 0;
            $correctQuestionReading = 0;

            foreach ($initCorrect as $item) {
                $question = Question::where('_id', $item->question_id)->first();
                if ($question->type_question == 'Listening' && $item->correct == true) {
                    $correctQuestionListening++;
                } elseif ($question->type_question == 'Structure And Written Expression' && $item->correct == true) {
                    $correctQuestionStructure++;
                } elseif ($question->type_question == 'Reading' && $item->correct == true) {
                    $correctQuestionReading++;
                }
            }

            // cek di table toefl_scores masing2 correct question dari reading listening dan structur dicek di table toefl_scores, di column jumlah_benar masing2 di cek masng2 benarnya di column listening untuk listening, lalu strukctur untuk structur dan reading juga. tampikan value yang match di table toefl_scores
            $hasilToeflInitListening = ToeflScore::where('jumlah_benar', $correctQuestionListening)->first();
            $hasilToeflInitStructure = ToeflScore::where('jumlah_benar', $correctQuestionStructure)->first();
            $hasilToeflInitReading = ToeflScore::where('jumlah_benar', $correctQuestionReading)->first();

            $initHasilPure = ($hasilToeflInitListening['listening'] + $hasilToeflInitStructure['structure'] + $hasilToeflInitReading['reading']);
            $hasilKali = $initHasilPure * 10;
            $hasilAkhir = round($hasilKali / 3);

            $hasilSatuanListening = $hasilToeflInitListening['listening'];
            $hasilSatuanStructure = $hasilToeflInitStructure['structure'];
            $hasilSatuanReading = $hasilToeflInitReading['reading'];

            $hasilSatuanListeningKali = $hasilSatuanListening * 10;
            $hasilSatuanStructureKali = $hasilSatuanStructure * 10;
            $hasilSatuanReadingKali = $hasilSatuanReading * 10;

            $hasilAkhirSatuanListening = round($hasilSatuanListeningKali / 3);
            $hasilAkhirSatuanStructure = round($hasilSatuanStructureKali / 3);
            $hasilAkhirSatuanReading = round($hasilSatuanReadingKali / 3);


            $level_profiency = "";
            if ($hasilAkhir < 200) {
                $level_profiency = "ELEMENTARY";
            } elseif ($hasilAkhir >= 200 && $hasilAkhir <= 350) {
                $level_profiency = "PRE-INTERMEDIATE";
            } elseif ($hasilAkhir >= 351 && $hasilAkhir <= 425) {
                $level_profiency = "INTERMEDIATE";
            } elseif ($hasilAkhir >= 426 && $hasilAkhir <= 500) {
                $level_profiency = "PRE-ADVANCED";
            } elseif ($hasilAkhir >= 501 && $hasilAkhir <= 550) {
                $level_profiency = "ADVANCED";
            } elseif ($hasilAkhir > 550) {
                $level_profiency = "SPECIAL ADVANCED";
            }

            UserScorer::create([
                'packet_id' => $idPacket,
                'user_id' => auth()->user()->_id,
                'akurasi' => $prosentase,
                'level_profiency' => $level_profiency,
                'score_toefl' => $hasilAkhir,
                'score_listening' => $hasilAkhirSatuanListening,
                'score_structure' => $hasilAkhirSatuanStructure,
                'score_reading' => $hasilAkhirSatuanReading,
            ]);



            return response()->json([
                'success' => true,
                'message' => 'Answers retake submitted successfully.',
                'data' => [
                    'akurasi' => $prosentase,
                    'correct_question_listening' => $correctQuestionListening,
                    'correct_question_structure' => $correctQuestionStructure,
                    'correct_question_reading' => $correctQuestionReading,
                    'hasil' => $hasilAkhir,
                    'level_profiency' => $level_profiency,
                    'score_toefl' => $hasilAkhir,
                    'score_listening' => $hasilAkhirSatuanListening,
                    'score_structure' => $hasilAkhirSatuanStructure,
                    'score_reading' => $hasilAkhirSatuanReading,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

   public function answerUsers($idPacket)
    {
        $userAnswers = UserAnswer::with('question.nesteds', 'question.multipleChoices')->where('packet_id', $idPacket)->where('user_id', auth()->user()->_id)->get();

        $mappedUserAnswers = $userAnswers->map(function ($userAnswer) {
            return [
                'nama_packet' => $userAnswer->packet['name_packet'],
                'question_id' => $userAnswer->question->_id,
                'question' => $userAnswer->question->question,
                'key_question' => $userAnswer->question->key_question,
                'correct' => $userAnswer->correct,
                'answer_user' => $userAnswer->answer_user,
                'type_question' => $userAnswer->question->type_question,
                'bookmark' => $userAnswer->bookmark,
                'nested_question_id' => $userAnswer->question->nesteds[0]->nestedQuestion->_id ?? null,
                'nested_question' => $userAnswer->question->nesteds[0]->nestedQuestion->question_nested ?? null,
                'multiple_choices' => $userAnswer->question->multipleChoices->map(function ($choice) {
                    return [
                        'id' => $choice->_id,
                        'choice' => $choice->choice,
                    ];
                })->all(),


            ];
        })->all();

        return response()->json([
            'success' => true,
            'message' => 'Data Question Packet fetched successfully',
            'data' => $mappedUserAnswers,
        ]);
    }
}
