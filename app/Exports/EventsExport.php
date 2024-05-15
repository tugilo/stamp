<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EventsExport implements FromQuery, WithHeadings, WithMapping
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
            'イベントID', 'イベント名', '開催日', '終了日', '利用者数'
        ];
    }

    /**
     * 各イベントデータをマッピングし、エクセルの列に合わせる。
     *
     * @param  Event  $event
     * @return array
     */
    public function map($event): array
    {
        return [
            $event->id,
            $event->event_name,
            $event->event_date->format('Y年m月d日'),
            optional($event->end_date)->format('Y年m月d日'),
            $event->participations()->count()  // イベントごとの参加者数を集計
        ];
    }
}
