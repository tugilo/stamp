<?php

namespace App\Http\Controllers;

use App\Models\CustomerSurveyResponse;
use Illuminate\Http\Request;

class SurveyResponseController extends Controller
{

    /**
     * コントローラーインスタンスの生成
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * アンケート結果の一覧を表示する。
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $responses = CustomerSurveyResponse::with([
            'customer', 
            'gender', 
            'ageGroup', 
            'residence', 
            'discoveryTrigger', 
            'discoveryCustomResponses'
        ])->get();

        return view('survey.index', compact('responses'));
    }
}
