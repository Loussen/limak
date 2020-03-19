<?php

namespace App\Http\Controllers\Admin;

use App\ModelFaq\Faq;
use App\ModelFaq\FaqTranslate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $limit = 10;
    public function index()
    {
        $params = [];
        if(Input::get('search') == "true"){
            $params = Input::get();
            $data = $this->search($params);
        }else{
            $data = Faq::with('translates')->whereHas('translates', function($query) {
                $query->where('locale', '=', 'az');
            })->paginate($this->limit);
        }

        return view('admin.faq.index', compact('data', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = null;
        return view('admin.faq.form', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Faq();
        $translateModel = new FaqTranslate();
        $response = crud($model, $request, null, null, ['fields' => ['answer', 'question'], 'modelName' => $translateModel], 'faq_id');

        if ($response['status']) {
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
        $data = Faq::find($id);

        return view('admin.faq.form', compact('data', 'id'));
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
        $model = Faq::find($id);
        $translateModel = $model->translates;
        $response = crud($model, $request, null, null, ['fields' => ['answer', 'question'], 'modelName' => $translateModel], 'faq_id', 'update');

        if ($response['status']) {
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
        $model = Faq::destroy($id);

        if ($model) {
            return back();
        }
    }

    public function search ($q) {
        $cp = [['locale', '=', 'az']];
        $p = [['id','<>', 0]];
        isset($q['id'])?$p[] = ['id', '=', $q['id']]:'';
        isset($q['answer'])?$cp[] = ['answer', 'like', '%'.$q['answer'].'%']:'';
        isset($q['question'])?$cp[] = ['question', 'like', '%'.$q['question'].'%']:'';
        $data = Faq::where($p)->with('translates')->whereHas('translates', function($query) use ($cp) {
            $query->where($cp);
        })->orderBy('id', 'desc')->paginate($this->limit);
        return $data;
    }
}
