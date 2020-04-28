<?php

namespace App\Http\Controllers\Front\Panel;

use App\Http\Controllers\Controller;
use App\Models\Questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class QuestionsdddController extends Controller
{
    public function getDefaultQuestions(Request $request)
    {
        $lang = $request->post("lang");
        $step = $request->post("step");
        $id = $request->post("id");

        $checkChildQuestions = $checkOther = null;
        if($id > 0)
        {
            $checkChildQuestions = DB::table('questions as q')
                ->select('id')
                ->where('q.status','1')
                ->where('q.p_id',$id)
                ->first();

            if($checkChildQuestions !== null)
            {
                $data = DB::table('questions as q')
                    ->select('qt.value as result','qt.answer','qt.questions_id','q.id','titles.name_'.$lang.' as title_name','q.type','q.chat_show')
                    ->leftJoin('questions_translates as qt','q.id','=','qt.questions_id')
                    ->leftJoin('questions_titles as titles','q.title_id','=','titles.id')
                    ->where('q.status','1')
                    ->where('q.step',$step)
                    ->where('q.p_id',$id)
                    ->where('qt.locale',$lang)
                    ->orderBy('q.ordering', 'ASC')
                    ->get();
            }
            else
            {
                $data = DB::table('questions as q')
                    ->select('qt.answer as result','qt.questions_id','q.id','titles.name_'.$lang.' as title_name','q.type','q.chat_show')
                    ->leftJoin('questions_translates as qt','q.id','=','qt.questions_id')
                    ->leftJoin('questions_titles as titles','q.title_id','=','titles.id')
                    ->where('q.status','1')
                    ->where('q.step',$step)
                    ->where('q.id',$id)
                    ->where('qt.locale',$lang)
                    ->orderBy('q.ordering', 'ASC')
                    ->get();
            }
        }
        else
        {
            if($step == 10000)
            {
                $data = DB::table('questions as q')
                    ->select('qt.value as result','qt.answer','qt.questions_id','q.id','titles.name_'.$lang.' as title_name','q.type','q.chat_show')
                    ->leftJoin('questions_translates as qt','q.id','=','qt.questions_id')
                    ->leftJoin('questions_titles as titles','q.title_id','=','titles.id')
                    ->where('q.status','1')
                    ->where('q.step','>',1)
                    ->where('q.p_id',0)
                    ->where('qt.locale',$lang)
                    ->orderBy('q.ordering', 'ASC')
                    ->get();
            }
            else
            {
                $data = DB::table('questions as q')
                    ->select('qt.value as result','qt.answer','qt.questions_id','q.id','titles.name_'.$lang.' as title_name','q.type','q.chat_show')
                    ->leftJoin('questions_translates as qt','q.id','=','qt.questions_id')
                    ->leftJoin('questions_titles as titles','q.title_id','=','titles.id')
                    ->where('q.status','1')
                    ->where('q.step',$step)
                    ->where('q.p_id',0)
                    ->where('qt.locale',$lang)
                    ->orderBy('q.ordering', 'ASC')
                    ->get();
            }


            $checkOther = true;
        }

        return response()->json([
            'data' => $data,
            'checkChild' => $checkChildQuestions,
            'checkOther' => $checkOther
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

    public function getMaxStepQuestions(Request $request)
    {
        $stepDesc = Questions::where('p_id',0)->orderBy('step', 'desc')->first(); // gets the whole row
        $maxStep = $stepDesc->step;

        return response()->json([
            'data' => $maxStep,
        ]);
    }

//    public function getNextStepQuestions(Request $request)
//    {
//        $step = $request->post("step");
//
//        $stepDesc = Questions::where('p_id',0)->orderBy('step', 'desc')->first(); // gets the whole row
//        $maxStep = $stepDesc->step;
//
//        return response()->json([
//            'data' => $maxStep,
//        ]);
//    }
}