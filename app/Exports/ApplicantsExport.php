<?php

namespace App\Exports;

use App\Models\CustomerPresent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApplicantsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $applicants;

    public function __construct($applicants)
    {
        $this->applicants = $applicants;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->applicants;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '応募日',
            'プレゼント名',
            '応募者名',
            '応募者カナ',
            'メールアドレス',
            '電話番号',
            '郵便番号',
            '都道府県',
            '市区町村',
            '住所',
            '建物名',
            'コメント',
        ];
    }

    /**
     * @param \App\Models\CustomerPresent $applicant
     * @return array
     */
    public function map($applicant): array
    {
        return [
            $applicant->created_at->format('Y年m月d日 H:i:s'),
            $applicant->present->presents_name,
            $applicant->name,
            $applicant->name_kana,
            $applicant->email,
            $applicant->tel,
            $applicant->zip,
            $applicant->prefecture,
            $applicant->city,
            $applicant->address,
            $applicant->building,
            $applicant->comment,
        ];
    }
}
