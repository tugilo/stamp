<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * コントローラーインスタンスの生成
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * アプリケーションダッシュボードを表示
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $events = Event::all(); // すべてのイベントを取得
        return view('home', compact('events')); // ビューにイベントデータを渡す
    }

    /**
     * イベントデータをJSON形式で返すAPIメソッド
     */
    public function events()
    {
        $events = Event::all()->map(function ($event) {
            return [
                'title' => $event->event_name,
                'start' => $event->event_date,
                'end' => $event->end_date ?? $event->event_date,
                'url' => $event->announcement_url ?? null
            ];
        });

        return response()->json($events);
    }
}
