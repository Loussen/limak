<?php

namespace App\Http\Controllers\Cp;
use App\Http\Controllers\Controller;
use App\ModelQuestions\Questions;
use App\ModelQuestions\QuestionsTitle;
use App\Models\QuestionsTranslate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class QuestionsTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getList (Request $request)
    {
        $per_page = 5;
        $data = DB::table('questions_titles as q')
            ->select('*')
            ->orderBy('q.id', 'DESC')
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


        $questionsTitle = new QuestionsTitle();
        $questionsTitle->name_az = $request->name_az;
        $questionsTitle->name_ru = $request->name_ru;
        $questionsTitle->save();

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

        $findQuestion = QuestionsTitle::findOrFail((int)$request->id);
        $findQuestion->name_az = $request->name_az;
        $findQuestion->name_ru = $request->name_ru;
        $findQuestion->updated_at = date("Y-m-d H:i:s");
        $findQuestion->update();

        return response()->json([
            'status' => 200,
            'data' => true
        ]);
    }

    public function getQuestionsTitle ($id)
    {
        $data = DB::table('questions_titles as q')
            ->select('*')
            ->where('q.id',$id)
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
        DB::delete('delete from questions_titles where id = ?',[$id]);

        return response()->json([
            'status' => 200,
            'data' => true
        ]);
    }

}
