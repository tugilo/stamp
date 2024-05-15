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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // リクエストからUIDとニックネームを取得
        $uid = $request->input('uid');
        $nickname = $request->input('nickname');

        Log::info('Received UID and Nickname:', ['uid' => $uid, 'nickname' => $nickname]);

        // 同じニックネームが既に存在するかチェック
        if (Customer::where('nickname', $nickname)->exists()) {
            // 既に存在するニックネームの場合はエラーを返す
            return response()->json(['error' => 'このニックネームは既に使用されています。'], 422);
        }

        // 新しいCustomerを作成
        $customer = new Customer();
        $customer->uuid = $uid;
        $customer->nickname = $nickname ?? 'User_' . Str::random(8);
        $customer->save();

        Log::info('Created Customer ID:', ['ID' => $customer->id]);

        // 成功した場合、customer_idを含むJSONレスポンスを返す
        return response()->json(['customer_id' => $customer->id, 'message' => 'Registration successful', 'redirect' => '/liff/survey?customer_id=' . $customer->id]);
    }
        /**
     * アンケートページを表示する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
         */

         public function showSurvey(Request $request)
         {
            // 受け取ったリクエストデータをログに記録
            Log::info('Received Request Data:', $request->all());
            
            $customer_id = $request->query('customer_id'); // クエリパラメータから customer_id を取得
            $customer = Customer::findOrFail($customer_id);
         
             // アンケートに必要なマスターデータを取得
             $genders = Gender::all();
             $ageGroups = AgeGroup::all();
             $residences = Residence::all();
             $discoveryTriggers = DiscoveryTrigger::all();
             $infoCategories = InfoCategory::all();
         
             return view('liff.survey', compact('customer', 'genders', 'ageGroups', 'residences', 'discoveryTriggers', 'infoCategories'));
         }    


         public function storeSurvey(Request $request)
         {
             // 受け取ったリクエストデータを全てログに記録
             Log::info('Received Request Data:', $request->all());
         
             // バリデーションルールの設定
             $validator = Validator::make($request->all(), [
                 'gender_id' => 'required|exists:genders,id',
                 'age_group_id' => 'required|exists:age_groups,id',
                 'residence_id' => 'required|exists:residences,id',
                 'discovery_trigger_id' => 'nullable|exists:discovery_triggers,id',
                 'info_category_ids.*' => 'exists:info_categories,id',
                 'custom_discovery_response' => [
                     'nullable',
                     'string',
                     Rule::requiredIf(function () use ($request) {
                         return $request->input('discovery_trigger_id') === 5;
                     })
                 ],
                 'custom_info_response' => 'nullable|string'
             ]);
         
             if ($validator->fails()) {
                 Log::error('Validation failed', $validator->errors()->toArray());
                 return back()->withErrors($validator)->withInput();
             }
         
             $validated = $validator->validated();
             $customerId = $request->input('customer_id');
             $customer = Customer::findOrFail($customerId);                 
         
             // アンケートの回答を保存または更新
             $surveyResponse = CustomerSurveyResponse::updateOrCreate(
                 ['customer_id' => $customerId],
                 [
                     'nickname' => $customer->nickname,
                     'gender_id' => $validated['gender_id'],
                     'age_group_id' => $validated['age_group_id'],
                     'residence_id' => $validated['residence_id'],
                     'discovery_trigger_id' => $validated['discovery_trigger_id'],
                     'info_category_ids' => implode(',', $request->input('info_category_ids', []))
                 ]
             );
         
             Log::info('Survey Response ID:', ['ID' => $surveyResponse->id]);
         
             if ($surveyResponse->id && $request->filled('discovery_trigger_id')) {
                 $otherTriggerId = DiscoveryTrigger::where('name', 'その他')->value('id');
                 if ($validated['discovery_trigger_id'] == $otherTriggerId && $request->filled('custom_discovery_response')) {
                     DiscoveryCustomResponse::updateOrCreate(
                         [
                             'customer_id' => $customerId,
                             'survey_response_id' => $surveyResponse->id
                         ],
                         [
                             'text' => $validated['custom_discovery_response']
                         ]
                     );
                 }
             } else {
                 Log::error('Survey Response ID not found or not saved correctly.');
             }
         
             // InfoCustomResponseの保存または更新を行う
             InfoCustomResponse::saveOrUpdate($customerId, $surveyResponse->id, $request->input('info_category_ids', []), $request->input('custom_info_response'));
         
             // customers.info_flgを1に更新
             $customer->info_flg = 1;
             $customer->save();
         
             // スタンプページへの内部リダイレクト
             return redirect()->route('liff.stamp.index', ['customer_id' => $customerId]);
         }
         

         //セッションをクリアするメソッド
    public function clearSession(Request $request)
    {
        $request->session()->forget('survey_url');
        return response()->json(['status' => 'session cleared']);
    }
    
}