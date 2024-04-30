<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    /**
     * 主催者一覧を表示する
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizers = Organizer::all();
        return view('organizers.index', compact('organizers'));
    }

    /**
     * 新規主催者登録フォームを表示する
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('organizers.create');
    }

    /**
     * 新しい主催者を登録する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_info' => 'nullable|string|max:255',
        ]);

        Organizer::create($request->all());

        return redirect()->route('organizers.index')->with('success', '新しい主催者が登録されました。');
    }

    /**
     * 主催者編集フォームを表示する
     *
     * @param  \App\Models\Organizer  $organizer
     * @return \Illuminate\Http\Response
     */
    public function edit(Organizer $organizer)
    {
        return view('organizers.edit', compact('organizer'));
    }

    /**
     * 主催者情報を更新する
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Organizer  $organizer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organizer $organizer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_info' => 'nullable|string|max:255',
        ]);

        $organizer->update($request->all());

        return redirect()->route('organizers.index')->with('success', '主催者情報が更新されました。');
    }

    /**
     * 主催者を削除する
     *
     * @param  \App\Models\Organizer  $organizer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organizer $organizer)
    {
        $organizer->delete();

        return redirect()->route('organizers.index')->with('success', '主催者が削除されました。');
    }
}