<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\Venue;
use App\Models\Area;
use App\Models\City;
use Illuminate\Http\Request;
use App\Exports\EventsExport;
use Maatwebsite\Excel\Facades\Excel;

class EventController extends Controller
{
    /**
     * イベント一覧を表示する。論理削除されていないイベントのみを取得。
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::with('organizer', 'venue', 'area', 'city')->where('show_flg', 1)->get();
        return view('events.index', compact('events'));
    }

    /**
     * 新規イベント登録フォームを表示する。
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizers = Organizer::all();
        $venues = Venue::all();
        $areas = Area::activeOrdered()->get();
        $cities = City::activeOrdered()->get();

        return view('events.create', compact('organizers', 'venues', 'areas', 'cities'));
    }

    /**
     * 新規イベントを登録する。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'organizer_id' => 'required|exists:organizers,id',
            'venue_id' => 'required|exists:venues,id',
            'area_id' => 'required|exists:areas,id',
            'city_id' => 'required|exists:cities,id',
            'event_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:event_date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'description' => 'nullable|string',
            'announcement_url' => 'nullable|url',
            'stamp_count' => 'required|integer|min:1',
        ]);

        $event = new Event($request->all());
        $event->code = $this->generateUniqueCode();
        $event->show_flg = 1; // イベントを表示するフラグを設定
        $event->save();

        return redirect()->route('events.index')->with('success', '新しいイベントが登録されました。');
    }

    /**
     * イベント編集フォームを表示する。
     *
     * @param  Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $organizers = Organizer::all();
        $venues = Venue::all();
        $areas = Area::activeOrdered()->get();
        $cities = City::activeOrdered()->get();

        return view('events.edit', compact('event', 'organizers', 'venues', 'areas', 'cities'));
    }

    /**
     * イベント情報を更新する。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'organizer_id' => 'required|exists:organizers,id',
            'venue_id' => 'required|exists:venues,id',
            'area_id' => 'required|exists:areas,id',
            'city_id' => 'required|exists:cities,id',
            'event_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:event_date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'description' => 'nullable|string',
            'announcement_url' => 'nullable|url',
            'stamp_count' => 'required|integer|min:1',
        ]);

        $event->update($request->all());

        return redirect()->route('events.index')->with('success', 'イベント情報が更新されました。');
    }

    /**
     * イベントを論理削除する。show_flgを0に設定。
     *
     * @param  Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event)
    {
        $event->show_flg = 0; // 論理削除フラグを設定
        $event->save();

        return redirect()->route('events.index')->with('success', 'イベントが削除されました。');
    }

    /**
     * イベントのユニークなコードを生成する。
     *
     * @return string
     */
    protected function generateUniqueCode()
    {
        do {
            $code = rand(1000, 9999); // 4桁のランダムな数値を生成
        } while (Event::where('code', $code)->exists()); // 重複がないかチェック

        return $code;
    }

    /**
     * 各イベントの参加者数を表示する。
     *
     * @return \Illuminate\Http\Response
     */
    public function showParticipations()
    {
        $events = Event::withCount('participations')->where('show_flg', 1)->get();
        return view('events.participations', compact('events'));
    }

    /**
     * イベントデータをExcelファイルとしてエクスポートする。
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new EventsExport, 'events.xlsx');
    }

}
