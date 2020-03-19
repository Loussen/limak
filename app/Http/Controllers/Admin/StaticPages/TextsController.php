<?php

namespace App\Http\Controllers\Admin\StaticPages;

use App\ModelStaticPages\Contents\Text;
use App\ModelStaticPages\Contents\TextTranslate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TextsController extends Controller
{
    /**
     * Text constructor.
     * @param Text $text
     */
    public function __construct()
    {}

    /**
     * @param $data
     * @return TextTranslate
     */
    public function insert($data)
    {
        $text = new Text();
        $text->save();
        // az
        $translateModel = new TextTranslate();
        $translateModel->name = $data->value['az']['name'];
        $translateModel->description = $data->value['az']['description'];
        $translateModel->text_id = $text->id;
        $translateModel->locale = 'az';
        $translateModel->save();
        // ru
        $translateModel = new TextTranslate();
        $translateModel->name = $data->value['ru']['name'];
        $translateModel->description = $data->value['ru']['description'];
        $translateModel->text_id = $text->id;
        $translateModel->locale = 'ru';
        $translateModel->save();

        return $text;
    }

    public function update(Request $request)
    {
        $translateModel = TextTranslate::findOrFail($request->value['az']['id']);
        $translateModel->name = $request->value['az']['name'];
        $translateModel->description = $request->value['az']['description'];
        $translateModel->update();

        $translateModel = TextTranslate::findOrFail($request->value['ru']['id']);
        $translateModel->name = $request->value['ru']['name'];
        $translateModel->description = $request->value['ru']['description'];
        $translateModel->update();

        return response()->json('ok', 200);
    }
}
