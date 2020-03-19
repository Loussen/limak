<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 05.12.2018
 * Time: 0:10
 */

namespace App\Http\Controllers\Front;


use App\Http\Controllers\Controller;

use App\ModelNews\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function newsIn(Request $request, $id) {
        $news = News::with('translates')->find($id);

        return view('front/news/newsIn', compact('news'));
    }
}