<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventsExport implements FromQuery, WithHeadings
{
    /**
     * エクスポートするイベントデータのクエリを定義。
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return Event::query()->where('show_flg', 1);  // 論理削除されていないイベントのみを対象
    }

    /**
     * Excelファイルのヘッダ行を定義。
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID', 'イベント名', '主催者名', '会場名', '開催日', '終了日', 'スタンプ数', '表示フラグ'
        ];
    }

    /**
     * 各イベントデータをマッピングし、エクセルの列に合わせる。
     *
     * @param  mixed  $event
     * @return array
     */
    public function map($event): array
    {
        return [
            $event->id,
            $event->event_name,
            optional($event->organizer)->name,
            optional($event->venue)->name,
            $event->event_date->format('Y年m月d日'),
            optional($event->end_date)->format('Y年m月d日'),
            $event->stamp_count,
            $event->show_flg ? '表示' : '非表示'
        ];
    }
}