<?php

namespace App\Http\Controllers;

use App\Models\CustomerSurveyResponse;
use App\Exports\SurveyResponsesExport;
use Maatwebsite\Excel\Facades\Excel;
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
    // SurveyResponseController の index メソッド内
    public function index()
    {
        $responses = CustomerSurveyResponse::with([
            'customer', 
            'gender', 
            'ageGroup', 
            'residence', 
            'discoveryTrigger', 
            'discoveryCustomResponses',
            'infoCustomResponses'
        ])->get();

        return view('survey.index', compact('responses'));
    }

    /**
     * アンケート結果をExcelファイルとしてエクスポートする。
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $filename = now()->format('YmdHis') . '_survey_responses.xlsx';
        return Excel::download(new SurveyResponsesExport, $filename);
    }


}
