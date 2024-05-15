<?php

namespace App\Http\Controllers;

use App\Exports\ApplicantsExport;
use App\Models\CustomerPresent;
use App\Models\Present;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class CustomerPresentController extends Controller
{
    /**
     * プレゼント応募者一覧を表示する
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // プレゼント応募者のデータを取得
        $applicants = CustomerPresent::with('customer', 'present')->get();
        
        // プレゼントデータを取得
        $presents = Present::all();
        
        // ログ出力
        Log::info('Applicants: ', $applicants->toArray());
        Log::info('Presents: ', $presents->toArray());

        // ビューにデータを渡して表示
        return view('presents.applicants', compact('applicants', 'presents'));
    }

    /**
     * プレゼント応募者一覧をExcelファイルとしてエクスポートする
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $presentName = $request->query('present_name');

        $query = CustomerPresent::with('customer', 'present');

        if ($presentName) {
            $query->whereHas('present', function($q) use ($presentName) {
                $q->where('presents_name', $presentName);
            });
        }

        $applicants = $query->get();

        $timestamp = now()->format('YmdHis'); // タイムスタンプの生成
        $presentId = $presentName ? Present::where('presents_name', $presentName)->first()->id : 'all'; // プレゼントIDの取得
        $filename = $timestamp . '_applicants_' . $presentId . '.xlsx'; // ファイル名の生成

        return Excel::download(new ApplicantsExport($applicants), $filename);
    }
}
