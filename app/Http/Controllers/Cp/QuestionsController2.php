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
        $per_page = 10;
        $data = DB::table('questions as q')
            ->select('*','qpt.value as p_value','q.step as q_step')
            ->leftJoin('questions as p', function ($join) {
                $join->on('p.id', '=', 'q.p_id');
                $join->leftJoin('questions_translates as qpt','p.id','=','qpt.questions_id');
                $join->where('qpt.locale','az');
            })

//            ->leftJoin('questions as p','p.id','=','q.p_id')
//            ->leftJoin('questions_translates as qpt','p.id','=','qpt.questions_id')
            ->leftJoin('questions_translates as qt','q.id','=','qt.questions_id')
            ->leftJoin('questions_titles as q_titles','q.title_id','=','q_titles.id')
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
        $model->ordering = $request->ordering;
        $model->p_id = $request->parent;
        $model->title_id = $request->title_id;
        $model->type = $request->type;
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
        $findQuestion->ordering = $request->ordering;
        $findQuestion->p_id = $request->parent;
        $findQuestion->title_id = $request->title_id;
        $findQuestion->type = $request->type;
        $findQuestion->updated_at = date("Y-m-d H:i:s");
        $findQuestion->update();

        $findQuestionTranslateAz = QuestionsTranslate::where('questions_id', '=' , (int)$findQuestion->id)->where('locale', '=', 'az')->first();
        $findQuestionTranslateRu = QuestionsTranslate::where('questions_id', '=' , (int)$findQuestion->id)->where('locale', '=', 'ru')->first();

        if($findQuestionTranslateAz)
        {
            // AZ
            $questionTranslate = QuestionsTranslate::where('questions_id', '=', (int)$request->question_id)->where('locale', '=', 'az')->firstOrFail();
            $questionTranslate->value = $request->question_az;
            $questionTranslate->answer = $request->answer_az;
            $questionTranslate->updated_at = date("Y-m-d H:i:s");
            $questionTranslate->update();
        }

        if($findQuestionTranslateRu)
        {
            // RU
            $questionTranslate = QuestionsTranslate::where('questions_id', '=' , (int)$request->question_id)->where('locale', '=', 'ru')->firstOrFail();
            $questionTranslate->value = $request->question_ru;
            $questionTranslate->answer = $request->answer_ru;
            $questionTranslate->updated_at = date("Y-m-d H:i:s");
            $questionTranslate->update();
        }

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

    public function getParentQuestions ($id)
    {
        $data = DB::table('questions as q')
            ->select('*')
            ->leftJoin('questions_translates as qt','q.id','=','qt.questions_id')
//            ->where('qt.answer','=', '')
            ->where('qt.locale','az')
            ->where('q.id','!=',$id)
            ->where('q.status','1')
            ->orderBy('q.id', 'DESC')
            ->get();

//        var_dump($data); exit;

        if($data) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }

    }

    public function getTitlesQuestions ()
    {
        $data = DB::table('questions_titles')
            ->select('*')
            ->where('status','1')
            ->get();

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
