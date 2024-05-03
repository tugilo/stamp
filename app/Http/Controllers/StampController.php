<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\Present;
use App\Models\CustomerPresent;
use App\Models\Zip;
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
     * プレゼント応募フォームを表示する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function applyPresentForm($customer_id, $syubetsu_id)
    {
        $customer = Customer::findOrFail($customer_id);
        $prefectures = Zip::getPrefectures();

        // syubetsu_id に基づいてプレゼントを取得
        $presents = Present::where('syubetsu_id', $syubetsu_id)->get();

        if ($presents->isEmpty()) {
            Log::error('syubetsu_id に該当するプレゼントが見つかりません: ' . $syubetsu_id);
            return redirect()->back()->with('error', 'このカテゴリーには利用可能なプレゼントがありません。');
        }

        Log::info('syubetsu_id に基づいて取得したプレゼント: ' . $syubetsu_id, ['presents' => $presents->toArray()]);

        // 過去の応募データを取得
        $previousApplication = CustomerPresent::where('customer_id', $customer_id)
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->first();

 
        return view('liff.present', compact('customer', 'presents', 'prefectures', 'syubetsu_id', 'previousApplication'));
    }

     /**
     * スタンプ数に応じたプレゼント応募処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function applyForPresent(Request $request)
    {
        $customerId = $request->input('customer_id');
        $syubetsuId = $request->input('syubetsu_id'); // 応募するプレゼントの種別ID
    
        $customer = Customer::findOrFail($customerId);
    
        // 応募条件を確認（スタンプ数が3以上か6以上か）
        $requiredStamps = $syubetsuId == 1 ? 6 : 3;
    
        if ($customer->stamp_count < $requiredStamps) {
            return back()->with('error', 'スタンプ数が足りません。');
        }
    
        // 重複応募のチェック
        if (CustomerPresent::where('customer_id', $customerId)->where('syubetsu_id', $syubetsuId)->exists()) {
            return back()->with('error', 'このプレゼントにはすでに応募済みです。');
        }
    
        // プレゼント応募データとフラグ更新
        CustomerPresent::create([
            'customer_id' => $customerId,
            'syubetsu_id' => $syubetsuId,
            'present_id' => $request->input('present_id'),
            'name' => $request->input('name'),
            'name_kana' => $request->input('name_kana'),
            'tel' => $request->input('tel'),
            'email' => $request->input('email'),
            'zip' => $request->input('zip'),
            'prefecture' => $request->input('prefecture'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
            'building' => $request->input('building', ''),
            'comment' => $request->input('comment', ''),
            'applied_at' => now(),
        ]);
    
        // フラグを更新
        if ($syubetsuId == 1) {
            $customer->update(['applied_for_a_prize' => 1]);
        } else if ($syubetsuId == 2) {
            $customer->update(['applied_for_b_prize' => 1]);
        }
    
        return redirect()->route('liff.stamp.index', ['customer_id' => $customerId])->with('success', 'プレゼントに応募しました！');
    }
    
     

}