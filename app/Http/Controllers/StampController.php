<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Event;
use App\Models\EventParticipation;
use Illuminate\Http\Request;

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

        // 顧客の参加イベント情報を取得
        $eventParticipations = $customer->eventParticipations;

        return view('liff.stamp', compact('customer', 'eventParticipations'));
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

        // イベントコードに対応するイベントを取得
        $event = Event::where('code', $eventCode)->first();

        if ($event) {
            // 顧客の参加イベント情報を取得
            $customer = Customer::findOrFail($customerId);
            $eventParticipations = $customer->eventParticipations;

            // イベントへの参加が既にあるかチェック
            $existingParticipation = $eventParticipations->where('event_id', $event->id)->first();

            if (!$existingParticipation) {
                // 新しい参加情報を作成
                $participation = new EventParticipation();
                $participation->customer_id = $customerId;
                $participation->event_id = $event->id;
                $participation->participation_date = now();
                $participation->stamps_earned = $event->stamp_count;
                $participation->save();
                // 顧客のstamp_countを更新
                $customer->stamp_count += $event->stamp_count;
                $customer->save();
            }
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