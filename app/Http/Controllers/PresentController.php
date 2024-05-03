<?php

namespace App\Http\Controllers;

use App\Models\Present;
use App\Models\PresentSyubetsu;
use Illuminate\Http\Request;

class PresentController extends Controller
{
    /**
     * プレゼント一覧表示
     */
    public function index()
    {
        $presents = Present::with('presentSyubetsu')->get(); // プレゼント情報と関連する種別情報を取得
        return view('presents.index', compact('presents'));
    }

    /**
     * プレゼント登録フォーム
     */
    public function create()
    {
        $syubetsu = PresentSyubetsu::all(); // プレゼント種別を取得
        return view('presents.create', compact('syubetsu'));
    }

    /**
     * プレゼント登録
     */
    public function store(Request $request)
    {
        $request->validate([
            'syubetsu_id' => 'required',
            'presents_name' => 'required|string|max:60',
            'comment' => 'nullable'
        ]);

        Present::create($request->all());
        return redirect()->route('presents.index')->with('success', 'プレゼントが登録されました。');
    }

    /**
     * プレゼント編集フォーム
     */
    public function edit(Present $present)
    {
        $syubetsu = PresentSyubetsu::all();
        return view('presents.edit', compact('present', 'syubetsu'));
    }

    /**
     * プレゼント更新
     */
    public function update(Request $request, Present $present)
    {
        $request->validate([
            'syubetsu_id' => 'required',
            'presents_name' => 'required|string|max:60',
            'comment' => 'nullable'
        ]);

        $present->update($request->all());
        return redirect()->route('presents.index')->with('success', 'プレゼントが更新されました。');
    }

    /**
     * 指定したプレゼントを削除する（非表示にする）。
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $present = Present::findOrFail($id);
        $present->show_flg = 0; // 削除ではなく、非表示フラグを設定
        $present->save();

        return redirect()->route('presents.index')->with('success', 'プレゼントを非表示にしました。');
    }

}
