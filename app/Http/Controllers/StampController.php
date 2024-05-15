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
        // 顧客IDとイベントコードをリクエストから取得
        $customerId = $request->input('customer_id');
        $eventCode = $request->input('event_code');

        // リクエストデータをログに記録
        Log::info('Received Request Data:', $request->all());

        // イベントコードのバリデーションを行い、必須かつ数値であることを確認
        $validated = $request->validate([
            'event_code' => 'required|numeric',
        ], [
            'event_code.numeric' => '半角数字でご入力お願いします。',
            'event_code.required' => 'イベントコードは必須です。',
        ]);

        // イベントコードに基づいてイベント情報を取得
        $event = Event::where('code', $eventCode)->where('show_flg', 1)->first();
        if (!$event) {
            // イベントが存在しないか、無効な場合はエラーメッセージを返す
            Log::error('Invalid Event Code:', ['event_code' => $eventCode]);
            return redirect()->back()->with('error', '無効なイベントコードです。');
        }

        // イベント情報をログに記録
        Log::info('Event Retrieved:', ['event' => $event]);

        // イベントの有効期限を計算（終了日があればそれを基準に、なければ開催日から3日後）
        $expirationDate = $event->end_date ? $event->end_date->addDays(3) : $event->event_date->addDays(3);
        if (now()->gt($expirationDate)) {
            // 現在日時が有効期限を過ぎている場合はエラーメッセージを返す
            Log::error('Event Expired:', ['expiration_date' => $expirationDate]);
            return redirect()->back()->with('error', 'このイベントのスタンプは有効期限切れです。');
        }

        // 顧客の参加情報を取得
        $customer = Customer::findOrFail($customerId);

        // 既に同一イベントへの参加記録が存在するかをチェック
        $existingParticipation = EventParticipation::where('customer_id', $customerId)
                                                    ->where('event_id', $event->id)
                                                    ->exists();
        if ($existingParticipation) {
            // 既に参加している場合はエラーメッセージを返す
            Log::error('Event Already Participated:', ['customer_id' => $customerId, 'event_id' => $event->id]);
            return redirect()->back()->with('error', 'すでに参加済みのイベントです。');
        }

        // 新しいイベント参加情報を作成して保存
        $participation = new EventParticipation([
            'customer_id' => $customerId,
            'event_id' => $event->id,
            'participation_date' => now(),
            'stamps_earned' => $event->stamp_count
        ]);
        $participation->save();

        // 顧客のスタンプ数を更新
        $customer->stamp_count += $event->stamp_count;
        $customer->save();

        // アンケートURLが設定されている場合、セッションに保存して後で利用可能にする
        if ($event->survey_url) {
            session(['survey_url' => $event->survey_url]);
            // セッションに保存したアンケートURLをログに記録
            Log::info('Survey URL Stored in Session:', ['survey_url' => $event->survey_url]);
        }

        // 処理が完了した後、顧客をスタンプページにリダイレクト
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
        // バリデーションルールの定義
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'syubetsu_id' => 'required|numeric',
            'present_id' => 'required|exists:presents,id',
            'name' => 'required|string|max:255',
            'name_kana' => 'required|string|max:255',
            'tel' => 'required|digits_between:10,11', // 10または11桁の数字
            'email' => 'required|email|max:255',
            'zip' => 'required|regex:/^\d{7}$/', // 7桁の数字のみ
            'prefecture' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:1000',
        ]);

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
