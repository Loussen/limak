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
        $p_id = $request->post("p_id");

        $data = DB::table('questions as q')
            ->select('qt.value','qt.answer','qt.questions_id','q.id')
            ->leftJoin('questions_translates as qt','q.id','=','qt.questions_id')
            ->where('q.status','1')
            ->where('q.step',$step)
            ->where('q.p_id',$p_id)
            ->where('qt.locale',$lang)
            ->orderBy('qt.ordering', 'ASC')
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

        $checkChildQuestions = DB::table('questions as q')
            ->select('id')
            ->where('q.status','1')
            ->where('q.p_id',$id)
            ->first();

        $data = DB::table('questions as q')
            ->select('qt.value','qt.answer','qt.questions_id','q.p_id')
            ->leftJoin('questions_translates as qt','q.id','=','qt.questions_id')
            ->where('q.status','1')
            ->where('q.step',$step)
            ->where('q.id',$id)
            ->where('qt.locale',$lang)
            ->orderBy('q.id', 'ASC')
            ->first();

        return response()->json([
            'data' => $data,
            'checkChild' => $checkChildQuestions
        ]);
    }

    public function getMaxStepQuestions()
    {
        $stepDesc = Questions::where('p_id',0)->orderBy('step', 'desc')->first(); // gets the whole row
        $maxStep = $stepDesc->step;

        return response()->json([
            'data' => $maxStep,
        ]);
    }
}