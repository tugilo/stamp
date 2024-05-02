<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerSurveyResponse;
use App\Models\DiscoveryTrigger;
use App\Models\DiscoveryCustomResponse;
use App\Models\Gender;
use App\Models\AgeGroup;
use App\Models\Residence;
use App\Models\InfoCategory;
use App\Models\InfoCustomResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LiffController extends Controller
{
    /**
     * LIFFで初期化時に呼び出されるメソッド。LINE UIDをもとに顧客を識別します。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function initializeLiff(Request $request)
    {
        // LINEから送信されたUIDを取得
        $uid = $request->input('uid');
        Log::info('Received UID:', ['uid' => $uid]); // 受け取ったUIDをログに記録

        // UIDをもとに顧客情報を検索
        $customer = Customer::where('uuid', $uid)->first();

        if ($customer) {
            Log::info('Customer found:', ['customer_id' => $customer->id]); // 顧客が見つかった場合、顧客IDをログに記録
            // 顧客が見つかった場合、顧客IDとinfo_flgを返す
            return response()->json(['customerId' => $customer->id, 'info_flg' => $customer->info_flg]);
        } else {
            Log::warning('Customer not found for UID:', ['uid' => $uid]); // 顧客が見つからない場合、警告ログを記録
            // 顧客が見つからない場合、エラーを返す
            return response()->json(['error' => 'Customer not found'], 404);
        }
    }

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

        // Customerが存在する場合は登録済みと判定し、info_flgも返す
        if ($customer) {
            return response()->json(['registered' => true, 'info_flg' => $customer->info_flg, 'customerId' => $customer->id]);
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
        return view('liff.index');
    }

    /**
     * LIFFから送信されたLINE UIDとニックネームを受け取り、Customerを作成する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // リクエストからUIDとニックネームを取得
        $uid = $request->input('uid');
        $nickname = $request->input('nickname');
    
        Log::info('Received UID and Nickname:', ['uid' => $uid, 'nickname' => $nickname]); // 受け取ったUIDとニックネームをログに出力
    
        // 新しいCustomerを作成
        $customer = new Customer();
        $customer->uuid = $uid;
        $customer->nickname = $nickname ?? 'User_' . Str::random(8);
        $customer->save();
    
        Log::info('Created Customer ID:', ['ID' => $customer->id]); // 作成したCustomerのIDをログに出力
        // 登録完了後、アンケートページにリダイレクト（パスパラメータを渡す）
        return redirect()->route('liff.survey.show', ['customer_id' => $customer->id]);
    }

    /**
     * アンケートページを表示する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function showSurvey(Request $request)
    {
        $uid = $request->input('uid');
        Log::info('uid:', ['uid' => $uid]); // ログにIDを出力
        
        $customer = Customer::where('uuid', $uid)->first();
    
        // アンケートに必要なマスターデータを取得
        $genders = Gender::all();
        $ageGroups = AgeGroup::all();
        $residences = Residence::all();
        $discoveryTriggers = DiscoveryTrigger::all();
        $infoCategories = InfoCategory::all();
    
        return view('liff.survey', compact('customer', 'genders', 'ageGroups', 'residences', 'discoveryTriggers', 'infoCategories'));
    }

    /**
     * アンケートの回答を保存する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSurvey(Request $request)
    {
        $customerId = $request->input('customer_id');
        $customer = Customer::findOrFail($customerId);

        // リクエストから必要な値を取得
        $discoveryTriggerId = $request->input('discovery_trigger_id');
        $customDiscoveryResponse = $request->input('custom_discovery_response');
        $infoCategoryIds = $request->input('info_category_ids', []);
        $customInfoResponse = $request->input('custom_info_response');

        // アンケートの回答を保存または更新
        $surveyResponse = CustomerSurveyResponse::updateOrCreate(
            ['customer_id' => $customerId],
            [
                'nickname' => $customer->nickname,
                'gender_id' => $request->input('gender_id'),
                'age_group_id' => $request->input('age_group_id'),
                'residence_id' => $request->input('residence_id'),
                'discovery_trigger_id' => $discoveryTriggerId,
                'info_category_ids' => implode(',', $infoCategoryIds)
            ]
        );

        Log::info('Survey Response ID:', ['ID' => $surveyResponse->id]); // ログにIDを出力

        if ($surveyResponse->id) {
            $otherTriggerId = DiscoveryTrigger::where('name', 'その他')->value('id');
            if ($discoveryTriggerId == $otherTriggerId && !empty($customDiscoveryResponse)) {
                DiscoveryCustomResponse::updateOrCreate(
                    [
                        'customer_id' => $customerId,
                        'survey_response_id' => $surveyResponse->id
                    ],
                    [
                        'text' => $customDiscoveryResponse
                    ]
                );
            }
        } else {
            Log::error('Survey Response ID not found or not saved correctly.');
        }

        // InfoCustomResponseの保存または更新を行う
        InfoCustomResponse::saveOrUpdate($customerId, $surveyResponse->id, $infoCategoryIds, $customInfoResponse);

        // customers.info_flgを1に更新
        $customer->info_flg = 1;
        $customer->save();

        // スタンプページへの内部リダイレクト
        return redirect()->route('liff.stamp.index', ['customer_id' => $customerId]);
    }
}