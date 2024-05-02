<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Event;
use App\Models\EventParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StampController extends Controller
{
    /**
     * スタンプラリーのページを表示する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $customerId = $request->input('customer_id');
        $customer = Customer::findOrFail($customerId);
    
        // 顧客のスタンプ数を取得
        $stampCount = min($customer->stamp_count, 6);  // 最大スタンプ数を6に制限
        Log::info('Received stampCount:', ['stampCount' => $stampCount]);
    
        // スタンプ状態の初期化
        $stamps = collect(range(1, 6))->mapWithKeys(function ($number) use ($stampCount) {
            return [$number => $number <= $stampCount];
        });
    
        Log::info('Stamps:', ['stamps' => $stamps->all()]);
    
        return view('liff.stamp', compact('customer', 'stamps'));
    }
    

     /**
     * スタンプを押す処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $customerId = $request->input('customer_id');
        $eventCode = $request->input('event_code');
        $validated = $request->validate([
            'event_code' => 'required|numeric',
        ], [
            'event_code.numeric' => '半角数字でご入力お願いします。',
            'event_code.required' => 'イベントコードは必須です。',
        ]);
    
        // イベントコードに対応するイベントを取得
        $event = Event::where('code', $eventCode)->first();
    
        if (!$event) {
            // イベントが見つからない場合、エラーメッセージをセッションに格納してリダイレクト
            return redirect()->back()->with('error', 'イベントが終了しているか、存在しないコードです。');
        }
    
        // 顧客の参加イベント情報を取得
        $customer = Customer::findOrFail($customerId);
        $eventParticipations = $customer->eventParticipations;
    
        // イベントへの参加が既にあるかチェック
        $existingParticipation = $eventParticipations->where('event_id', $event->id)->first();
    
        if (!$existingParticipation) {
            // 新しい参加情報を作成
            $participation = new EventParticipation([
                'customer_id' => $customerId,
                'event_id' => $event->id,
                'participation_date' => now(),
                'stamps_earned' => $event->stamp_count
            ]);
            $participation->save();
    
            // 顧客のstamp_countを更新
            $customer->stamp_count += $event->stamp_count;
            $customer->save();
        }
    
        return redirect()->route('liff.stamp.index', ['customer_id' => $customerId]);
    }
    
    /**
     * 4つ目のプレゼントに応募する処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyPrize4(Request $request)
    {
        $customerId = $request->input('customer_id');

        // 4つ目のプレゼントに応募する処理を実装

        return response()->json(['message' => '4つ目のプレゼントに応募しました']);
    }

    /**
     * 9つ目のプレゼントに応募する処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyPrize9(Request $request)
    {
        $customerId = $request->input('customer_id');

        // 9つ目のプレゼントに応募する処理を実装

        return response()->json(['message' => '9つ目のプレゼントに応募しました']);
    }
}