<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Str;

class LiffController extends Controller
{
    /**
     * Customerの登録状態をチェックする
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        $uid = $request->input('uid');

        // UIDに基づいてCustomerを検索
        $customer = Customer::where('uuid', $uid)->first();

        // Customerが存在する場合は登録済みと判定
        if ($customer) {
            return response()->json(['registered' => true]);
        }

        // Customerが存在しない場合は未登録と判定
        return response()->json(['registered' => false]);
    }
    
    /**
     * LIFFを表示するためのビューを返す
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $uid = $request->input('uid');

        // UIDに基づいてCustomerを検索
        $customer = Customer::where('uuid', $uid)->first();

        // Customerが存在する場合は登録済みのビューを返す
        if ($customer) {
            return view('liff.registered', ['nickname' => $customer->nickname]);
        }

        // Customerが存在しない場合はLIFFのビューを返す
        return view('liff.index');
    }

    /**
     * LIFFから送信されたLINE UIDとニックネームを受け取り、Customerを作成する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // リクエストからUIDとニックネームを取得
        $uid = $request->input('uid');
        $nickname = $request->input('nickname');

        // 新しいCustomerを作成
        $customer = new Customer();
        $customer->uuid = $uid;
        $customer->nickname = $nickname ?? 'User_' . Str::random(8);
        $customer->save();

        // 登録済みのビューを返す
        return view('liff.registered', ['nickname' => $customer->nickname]);
    }
}