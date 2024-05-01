<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerSurveyResponse;
use App\Models\DiscoveryTrigger;
use App\Models\Gender;
use App\Models\AgeGroup;
use App\Models\Residence;
use App\Models\InfoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $uid = $request->query('uid');
    
        // UIDに基づいてCustomerを検索
        $customer = Customer::where('uuid', $uid)->first();
    
        // Customerが存在する場合
        if ($customer) {
            // info_flgが0であれば、つまりアンケート未回答であればアンケートページへ
            if ($customer->info_flg === 0) {
                // アンケートに必要なマスターデータを取得
                $genders = Gender::all();
                $ageGroups = AgeGroup::all();
                $residences = Residence::all();
                $discoveryTriggers = DiscoveryTrigger::all();
                $infoCategories = InfoCategory::all();

                return view('liff.survey', compact('customer', 'genders', 'ageGroups', 'residences', 'discoveryTriggers', 'infoCategories'));
            }
            
            // info_flgが1であれば、アンケート回答済みなのでスタンプページへ
            return view('liff.stamp');
        }

    
        // Customerが存在しない場合はLIFFの登録ビューを返す
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

        // 新しいCustomerを作成
        $customer = new Customer();
        $customer->uuid = $uid;
        $customer->nickname = $nickname ?? 'User_' . Str::random(8);
        $customer->save();

        // 登録完了後、LIFFのビューにリダイレクトしてUIDを渡す
        return redirect('/liff')->with('uid', $uid);
    }
    /**
     * アンケートページを表示する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function showSurvey(Request $request)
    {
        $customerId = $request->input('customer_id');
        $customer = Customer::findOrFail($customerId);

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
    
        // アンケートの回答を保存または更新
        $surveyResponse = CustomerSurveyResponse::updateOrCreate(
            ['customer_id' => $customerId], // 一意に識別する属性
            [
                'nickname' => $customer->nickname,
                'gender_id' => $request->input('gender_id'),
                'age_group_id' => $request->input('age_group_id'),
                'residence_id' => $request->input('residence_id'),
                'discovery_trigger_id' => $request->input('discovery_trigger_id'),
                'info_category_ids' => $request->input('info_category_ids', []) // 直接配列を設定
            ]
        );
        
        // 「その他」が選ばれ、カスタムレスポンスが入力された場合、DiscoveryCustomResponseに保存
        if ($discoveryTriggerId == DiscoveryTrigger::where('name', 'その他')->value('id') && !empty($customDiscoveryResponse)) {
            $surveyResponse->discoveryCustomResponses()->create([
                'customer_id' => $customerId,
                'text' => $customDiscoveryResponse
            ]);
        }

        // customers.info_flgを1に更新
        $customer->info_flg = 1;
        $customer->save();
    
        // スタンプ台紙ページにリダイレクト
        return view('liff.stamp');
    }
}