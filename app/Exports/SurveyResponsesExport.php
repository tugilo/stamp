<?php

namespace App\Exports;

use App\Models\CustomerSurveyResponse;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class SurveyResponsesExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
     * エクスポートするクエリを定義する。
     * 
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return CustomerSurveyResponse::query()
            ->with(['gender', 'ageGroup', 'residence', 'discoveryTrigger', 'discoveryCustomResponses']);
    }

    /**
     * Excelファイルのヘッダを定義する。
     * 
     * @return array
     */
    public function headings(): array
    {
        return [
            'カスタマーID', 
            'ニックネーム', 
            '性別', 
            '年代', 
            '居住地', 
            '知ったきっかけ', 
            'その他の知ったきっかけ', 
            '観光情報', 
            'その他の観光情報'
        ];
    }

    /**
     * レコードごとのデータをマッピングする。
     * 
     * @param  mixed  $response
     * @return array
     */
    public function map($response): array
    {
        return [
            $response->customer_id,
            $response->nickname,
            optional($response->gender)->name ?? 'N/A',
            optional($response->ageGroup)->name ?? 'N/A',
            optional($response->residence)->name ?? 'N/A',
            optional($response->discoveryTrigger)->name ?? 'N/A',
            $response->discoveryCustomResponses->isNotEmpty() ? $response->discoveryCustomResponses->first()->text : 'N/A',
            implode(', ', $response->info_category_names),
            $response->custom_info_text
        ];
    }
}
