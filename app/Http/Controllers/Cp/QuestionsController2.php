<?php

namespace App\Http\Controllers\Cp;
use App\Http\Controllers\Controller;
use App\ModelQuestions\Questions;
use App\Models\QuestionsTranslate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class QuestionsController2 extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getList (Request $request)
    {
        $per_page = 5;
        $data = DB::table('questions as q')
            ->select('*')
            ->leftJoin('questions_translates as qt','q.id','=','qt.questions_id')
            ->where('qt.locale','az')
            ->orderBy('q.id', 'ASC')
            ->paginate($per_page);

        if($data) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }

    /**
     * Insert questions data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
//        $request->validate([
//            'value.az' => 'required',
//        ]);

        $model = new Questions();
        $step = $request->step;
        $model->step = $step;
        $model->save();

        // AZ
        $questionTranslate = new QuestionsTranslate();
        $questionTranslate->value = $request->question_az;
        $questionTranslate->answer = $request->answer_az;
        $questionTranslate->questions_id = $model->id;
        $questionTranslate->locale = 'az';
        $questionTranslate->save();

        // RU
        if( !empty($request->question_ru) && !empty($request->answer_ru) ) {
            $questionTranslate = new QuestionsTranslate();
            $questionTranslate->value = $request->question_ru;
            $questionTranslate->answer = $request->answer_ru;
            $questionTranslate->questions_id = $model->id;
            $questionTranslate->locale = 'ru';
            $questionTranslate->save();
        }

        return response()->json([
            'status' => 200,
            'data' => true
        ]);
    }

    /**
     * Update questions data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
//        $request->validate([
//            'value.az' => 'required',
//        ]);

        $findQuestion = Questions::findOrFail((int)$request->question_id);
        $step = $request->step;
        $findQuestion->step = $step;
        $findQuestion->updated_at = date("Y-m-d H:i:s");
        $findQuestion->update();

        // AZ
        $questionTranslate = QuestionsTranslate::where('questions_id', '=' , (int)$request->question_id)->where('locale', '=', 'az')->firstOrFail();
        $questionTranslate->value = $request->question_az;
        $questionTranslate->answer = $request->answer_az;
        $questionTranslate->updated_at = date("Y-m-d H:i:s");
        $questionTranslate->update();

        // RU
        $questionTranslate = QuestionsTranslate::where('questions_id', '=' , (int)$request->question_id)->where('locale', '=', 'ru')->firstOrFail();
        $questionTranslate->value = $request->question_ru;
        $questionTranslate->answer = $request->answer_ru;
        $questionTranslate->updated_at = date("Y-m-d H:i:s");
        $questionTranslate->update();

        return response()->json([
            'status' => 200,
            'data' => true
        ]);
    }

    public function getQuestion ($id)
    {
        $data = DB::table('questions as q')
            ->select('*')
            ->leftJoin('questions_translates as qt','q.id','=','qt.questions_id')
            ->where('q.id',$id)
            ->where('qt.questions_id',$id)
            ->get();

        if($data) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }

    }

    public function getParentQuestions ()
    {
        $data = DB::table('questions as q')
            ->select('*')
            ->leftJoin('questions_translates as qt','q.id','=','qt.questions_id')
//            ->where('qt.answer','=', '')
            ->where('qt.locale','az')
            ->where('q.status','1')
            ->get();

//        var_dump($data); exit;

        if($data) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }

    }

    public function delete($id)
    {
        DB::delete('delete from questions where id = ?',[$id]);
        DB::delete('delete from questions_translates where questions_id = ?',[$id]);

        return response()->json([
            'status' => 200,
            'data' => true
        ]);
    }

}
