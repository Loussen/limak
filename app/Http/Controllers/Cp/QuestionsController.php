<?php

namespace App\Http\Controllers\Cp;
use App\ModelQuestions\Questions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\ModelQuestions\QuestionsTranslate;

class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $limit = 10;
    public function index()
    {
        $params = [];
        if(Input::get('search') == "true"){
            $params = Input::get();
            $data = $this->search($params);
        }else{
            $data = Questions::with('translates')->whereHas('translates', function($query) {
                $query->where('locale', '=', 'az');
            })->orderBy('id', 'asc')->paginate($this->limit);
        }

        return view('cp.questions.index', compact('data', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = null;
        $url = null;
        return view('cp.questions.form', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'value.az' => 'required',
        ]);
        $model = new Questions();
        $step = $request->step;
        $model->step = $step;
        $translateModel = 'App\ModelQuestions\QuestionsTranslate';
        if(1==1)
        {
            $response = crud($model, $request, null, null, ['fields' => ['value', 'answer'], 'modelName' => $translateModel], 'questions_id');
            if ($response['status']) {
                Session::flash('message_success', 'Məlumat əlavə olundu!');
                Session::flash('alert-class', 'alert-success');
                return back();
            }
        }else{
            Session::flash('message_error', 'Xəta! Əməliyyatın davam etməsi üçün şəkil yükləyin!');
            Session::flash('alert-class', 'alert-danger');
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Questions::find($id);
        return view('cp.questions.form', compact('data', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request);
        $image = null;
        $model = Questions::find($id);
        $translateModel = $model->translates;
        $step = $request->step;
        $model->step = $step;
        //dd($translateModel );
        $response = crud($model, $request, null, $image, ['fields' => ['value', 'answer'], 'modelName' => $translateModel], 'questions_id', 'update');

        if ($response['status']) {
            Session::flash('message_success', 'Məlumat redaktə olundu!');
            Session::flash('alert-class', 'alert-success');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Questions::find($id);

        QuestionsTranslate::where('questions_id','=',$id)->delete();

        if ($model->delete()) {
            Session::flash('message_success', 'Məlumatlar silindi!');
            Session::flash('alert-class', 'alert-success');
            return back();
        }
    }

    public function search ($q) {
        $cp = [['locale', '=', 'az']];
        $p = [['id','<>', 0]];
        isset($q['id'])?$p[] = ['id', '=', $q['id']]:'';
        isset($q['name'])?$cp[] = ['name', 'like', '%'.$q['name'].'%']:'';
        isset($q['description'])?$cp[] = ['description', 'like', '%'.$q['description'].'%']:'';
        isset($q['created_at'])?$cp[] = ['created_at', 'like', '%'.$q['created_at'].'%']:'';
        $data = Questions::where($p)->with('translates')->whereHas('translates', function($query) use ($cp) {
            $query->where($cp);
        })->orderBy('id', 'desc')->paginate($this->limit);
        return $data;
    }
}
