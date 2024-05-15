@extends('adminlte::page')

@section('title', 'プレゼント応募者一覧')

@section('css')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        #applicants-table th.small-col, #applicants-table td.small-col {
            width: 50px;  /* セルの幅を50pxに固定 */
            max-width: 50px;  /* 最大幅も50pxに設定 */
            text-align: center;
        }
        #applicants-table td, #applicants-table th {
            white-space: nowrap;
            vertical-align: middle; /* セルの内容を中央揃えに */
        }
    </style>
@stop

@section('content_header')
    <h1>プレゼント応募者一覧</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label for="present-filter">プレゼントを選択</label>
                <select id="present-filter" class="form-control">
                    <option value="">全てのプレゼント</option>
                    @foreach ($presents as $present)
                        <option value="{{ $present->presents_name }}">{{ $present->presents_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <a href="{{ route('presents.export') }}" id="export-btn" class="btn btn-success">エクスポート</a>
            </div>

            <table class="table table-bordered" id="applicants-table">
                <thead>
                    <tr>
                        <th>応募日</th>
                        <th>プレゼント名</th>
                        <th>応募者名</th>
                        <th>応募者カナ</th>
                        <th>メールアドレス</th>
                        <th>電話番号</th>
                        <th>郵便番号</th>
                        <th>都道府県</th>
                        <th>市区町村</th>
                        <th>住所</th>
                        <th>建物名</th>
                        <th>コメント</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applicants as $applicant)
                        <tr data-present-id="{{ $applicant->present_id }}">
                            <td>{{ \Carbon\Carbon::parse($applicant->created_at)->format('Y年m月d日 H:i:s') }}</td>
                            <td>{{ $applicant->present->presents_name }}</td>
                            <td>{{ $applicant->name }}</td>
                            <td>{{ $applicant->name_kana }}</td>
                            <td>{{ $applicant->email }}</td>
                            <td>{{ $applicant->tel }}</td>
                            <td>{{ $applicant->zip }}</td>
                            <td>{{ $applicant->prefecture }}</td>
                            <td>{{ $applicant->city }}</td>
                            <td>{{ $applicant->address }}</td>
                            <td>{{ $applicant->building }}</td>
                            <td>{{ $applicant->comment }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#applicants-table').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/ja.json"
                },
                responsive: true,
                autoWidth: false,
                scrollX: true,
            });

            $('#present-filter').on('change', function() {
                var selectedPresentName = $(this).val();

                // DataTableのフィルタリング
                if (selectedPresentName) {
                    table.column(1).search('^' + selectedPresentName + '$', true, false).draw();
                } else {
                    table.column(1).search('').draw();
                }

                // エクスポートボタンのリンク更新
                var exportUrl = "{{ route('presents.export') }}";
                if (selectedPresentName) {
                    exportUrl += '?present_name=' + selectedPresentName;
                }
                $('#export-btn').attr('href', exportUrl);
            });
        });
    </script>
@stop
