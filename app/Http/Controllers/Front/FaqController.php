<?php
/**
 * Created by PhpStorm.
 * User: Rasad Hasanzada
 * Date: 12/18/2018
 * Time: 6:38 PM
 */

namespace App\Http\Controllers\Front;


use App\ModelFaq\Faq;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function index () {
        $questions = Faq::with('translates')->get();
        return view('front/questions/index', compact('questions'));
    }
}