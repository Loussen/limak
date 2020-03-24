<?php

namespace App\Http\Controllers\Front\Panel;

use App\Http\Controllers\Controller;
use App\Models\Questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class QuestionsdController extends Controller
{
    public function getDefaultQuestions(Request $request)
    {
//        $data=DB::table('questions')->select('id','value','answer')
//            ->where('status','1')
//            ->where('locale',Lang::getLocale())
//            ->orderBy('id','asc')->get();

        $lang = $request->post("lang");
        $step = $request->post("step");

        $data = DB::table('questions as q')
            ->select('qt.value','qt.answer','qt.questions_id','q.id')
            ->leftJoin('questions_translates as qt','q.id','=','qt.questions_id')
            ->where('q.status','1')
            ->where('q.step',$step)
            ->where('qt.locale',$lang)
            ->orderBy('q.id', 'ASC')
            ->get();

//        $data = Questions::with('translates')->where('status','1')->where('locale','=','ru')->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function getDefaultQuestion(Request $request)
    {
//        $data=DB::table('questions')->select('id','value','answer')
//            ->where('status','1')
//            ->where('id',$id)
//            ->get();

        $lang = $request->post("lang");
        $step = $request->post("step");
        $id = $request->post("id");

        $data = DB::table('questions as q')
            ->select('qt.value','qt.answer','qt.questions_id')
            ->leftJoin('questions_translates as qt','q.id','=','qt.questions_id')
            ->where('q.status','1')
            ->where('q.step',$step)
            ->where('q.id',$id)
            ->where('qt.locale',$lang)
            ->orderBy('q.id', 'ASC')
            ->first();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function getMaxStepQuestions()
    {
        $stepDesc = Questions::orderBy('step', 'desc')->first(); // gets the whole row
        $maxStep = $stepDesc->step;

        return response()->json([
            'data' => $maxStep,
        ]);
    }
}