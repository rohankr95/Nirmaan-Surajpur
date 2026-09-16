<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>कार्य विवरण प्रपत्र — {{ $work->work_name }}</title>
    <style>
        :root { --ink:#1a1a1a; --rule:#999; --head:#1f3352; --soft:#f2f4f7; }
        * { box-sizing: border-box; }
        body {
            font-family: "Nirmala UI", "Noto Sans Devanagari", "Mangal", sans-serif;
            color: var(--ink); margin: 0; padding: 18px; font-size: 12px; line-height: 1.45;
            background: #fff;
        }
        .sheet { max-width: 900px; margin: 0 auto; }
        .masthead {
            display: flex; justify-content: space-between; align-items: flex-start;
            border-bottom: 3px solid var(--head); padding-bottom: 10px; margin-bottom: 4px;
        }
        .masthead h1 { font-size: 20px; margin: 0; color: var(--head); }
        .masthead .sub { font-size: 11px; color: #555; }
        .masthead .meta { text-align: right; font-size: 11px; }
        .doctitle {
            text-align: center; font-size: 15px; font-weight: bold; letter-spacing: 2px;
            margin: 14px 0 16px; color: var(--head);
        }
        h2.section {
            font-size: 12px; background: var(--head); color: #fff; padding: 5px 9px;
            margin: 14px 0 0; letter-spacing: .5px;
        }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid var(--rule); padding: 5px 8px; vertical-align: top; }
        th { background: var(--soft); text-align: left; font-weight: 600; width: 22%; }
        td.val { font-weight: 600; }
        .photos { display: flex; gap: 10px; flex-wrap: wrap; padding: 10px 0; }
        .photos figure { margin: 0; width: 30%; }
        .photos img { width: 100%; height: 130px; object-fit: cover; border: 1px solid var(--rule); }
        .photos figcaption { font-size: 10px; text-align: center; padding-top: 3px; }
        .signatures {
            display: flex; justify-content: space-between; gap: 12px;
            margin-top: 48px; page-break-inside: avoid;
        }
        .signatures div { flex: 1; text-align: center; font-size: 11px; }
        .signatures .line { border-top: 1px solid var(--ink); padding-top: 5px; margin-top: 40px; }
        .footnote {
            margin-top: 22px; border-top: 1px solid var(--rule); padding-top: 6px;
            font-size: 10px; color: #666; display: flex; justify-content: space-between;
        }
        .toolbar { max-width: 900px; margin: 0 auto 14px; text-align: right; }
        .toolbar button, .toolbar a {
            font: inherit; padding: 7px 16px; border: 1px solid var(--head); background: var(--head);
            color: #fff; cursor: pointer; text-decoration: none; border-radius: 3px;
        }
        .toolbar a { background: #fff; color: var(--head); margin-right: 6px; }

        @media print {
            body { padding: 0; font-size: 11px; }
            .toolbar { display: none; }
            h2.section { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .sheet { max-width: none; }
        }
        @page { size: A4; margin: 12mm; }
    </style>
</head>
<body>

<div class="toolbar">
    <a href="{{ route('reports.work-details', $work->work_id) }}">वापस जाएं</a>
    <button onclick="window.print()">प्रिंट करें</button>
</div>

<div class="sheet">
    <div class="masthead">
        <div>
            <h1>{{ config('app.name') }}</h1>
            <div class="sub">जिला सूरजपुर, छत्तीसगढ़</div>
        </div>
        <div class="meta">
            <div>प्रिंट दिनांक: <b>{{ date('d-m-Y') }}</b></div>
            <div>कार्य क्रमांक: <b>{{ sprintf('%06d', $work->work_id) }}</b></div>
        </div>
    </div>

    <div class="doctitle">कार्य विवरण प्रपत्र</div>

    <h2 class="section">कार्य का विवरण</h2>
    <table>
        <tr><th>कार्य का नाम</th><td class="val" colspan="3">{{ $work->work_name }}</td></tr>
        <tr>
            <th>वित्तीय वर्ष</th><td class="val">{{ $work->financial_year->name ?? '—' }}</td>
            <th>योजना</th><td class="val">{{ $work->scheme->scheme_name ?? '—' }}</td>
        </tr>
        <tr>
            <th>कार्य प्रकार</th><td class="val">{{ $work->work_type->work_type_name ?? '—' }}</td>
            <th>कार्य इकाई</th><td class="val">{{ $work->units_of_work ?? '—' }}</td>
        </tr>
        <tr>
            <th>विकासखण्ड</th><td class="val">{{ $work->village->grampanchayat->block->block_name ?? '—' }}</td>
            <th>ग्राम पंचायत</th><td class="val">{{ $work->village->grampanchayat->grampanchayat_name ?? '—' }}</td>
        </tr>
        <tr>
            <th>ग्राम / वार्ड</th><td class="val">{{ $work->village->village_name ?? $work->ward->ward_name ?? '—' }}</td>
            <th>एजेंसी</th><td class="val">{{ $work->office->office_name ?? '—' }}</td>
        </tr>
        <tr>
            <th>विभाग</th><td class="val">{{ $work->department->department_name ?? '—' }}</td>
            <th>वर्तमान स्थिति</th><td class="val">{{ $work->status->work_status_name ?? '—' }}</td>
        </tr>
    </table>

    <h2 class="section">तकनीकी एवं प्रशासकीय स्वीकृति</h2>
    <table>
        <tr>
            <th>तकनीकी स्वीकृति क्रमांक</th><td class="val">{{ $work->technical_sanction->ts_no ?? '—' }}</td>
            <th>तकनीकी स्वीकृति दिनांक</th><td class="val">{{ optional($work->technical_sanction)->submission_date ? date('d-m-Y', strtotime($work->technical_sanction->submission_date)) : '—' }}</td>
        </tr>
        <tr>
            <th>तकनीकी स्वीकृति राशि</th><td class="val">{{ optional($work->technical_sanction)->ts_amount ? '₹ '.number_format($work->technical_sanction->ts_amount, 2) : '—' }}</td>
            <th>अनुमोदन दिनांक</th><td class="val">{{ optional($work->technical_sanction)->approval_date ? date('d-m-Y', strtotime($work->technical_sanction->approval_date)) : '—' }}</td>
        </tr>
        <tr>
            <th>प्रशासकीय स्वीकृति क्रमांक</th><td class="val">{{ $work->administrative_sanction->as_no ?? '—' }}</td>
            <th>प्रशासकीय स्वीकृति दिनांक</th><td class="val">{{ optional($work->administrative_sanction)->submission_date ? date('d-m-Y', strtotime($work->administrative_sanction->submission_date)) : '—' }}</td>
        </tr>
        <tr>
            <th>प्रशासकीय स्वीकृति राशि</th><td class="val">{{ optional($work->administrative_sanction)->as_amount ? '₹ '.number_format($work->administrative_sanction->as_amount, 2) : '—' }}</td>
            <th>स्वीकृतिकर्ता</th><td class="val">{{ $work->administrative_sanction->govt_or_district ?? '—' }}</td>
        </tr>
    </table>

    <h2 class="section">निविदा एवं कार्य आदेश</h2>
    <table>
        <tr>
            <th>निविदा क्रमांक</th><td class="val">{{ $work->tender->tender_no ?? '—' }}</td>
            <th>निविदा जारी दिनांक</th><td class="val">{{ optional($work->tender)->tender_release_date ? date('d-m-Y', strtotime($work->tender->tender_release_date)) : '—' }}</td>
        </tr>
        <tr>
            <th>निविदा खुलने की दिनांक</th><td class="val">{{ optional($work->tender)->tender_opening_date ? date('d-m-Y', strtotime($work->tender->tender_opening_date)) : '—' }}</td>
            <th>कार्य आदेश क्रमांक</th><td class="val">{{ optional($work->agreement)->work_order_no ?? '—' }}</td>
        </tr>
        <tr>
            <th>कार्य आदेश दिनांक</th><td class="val">{{ optional($work->agreement)->work_order_date ? date('d-m-Y', strtotime($work->agreement->work_order_date)) : '—' }}</td>
            <th>कार्य आदेश राशि</th><td class="val">{{ optional($work->agreement)->work_order_amount ? '₹ '.number_format($work->agreement->work_order_amount, 2) : '—' }}</td>
        </tr>
        <tr>
            <th>ठेकेदार / ग्रामपंचायत</th><td class="val" colspan="3">{{ optional(optional($work->agreement)->contractor)->contractor_name ?? '—' }}</td>
        </tr>
    </table>

    <h2 class="section">कार्य की प्रगति एवं समापन</h2>
    <table>
        <tr>
            <th>वर्तमान चरण</th><td class="val">{{ $work->stage->work_type_stage_name ?? '—' }}</td>
            <th>कुल व्यय राशि</th><td class="val">₹ {{ number_format($work->work_progress->sum('expenditure_amount'), 2) }}</td>
        </tr>
        <tr>
            <th>अनुमानित समापन दिनांक</th><td class="val">{{ $work->workComplete_endDate ? date('d-m-Y', strtotime($work->workComplete_endDate)) : '—' }}</td>
            <th>वास्तविक समापन दिनांक</th><td class="val">{{ optional($work->work_complete)->completion_date ? date('d-m-Y', strtotime($work->work_complete->completion_date)) : '—' }}</td>
        </tr>
    </table>

    <h2 class="section">उत्तरदायी अधिकारी</h2>
    <table>
        <tr>
            <th>उप अभियंता</th><td class="val">{{ $work->employee->emp_name ?? '—' }}</td>
            <th>मोबाइल न.</th><td class="val">{{ $work->employee->emp_mobile ?? '—' }}</td>
        </tr>
        <tr>
            <th>एसडीओ</th><td class="val">{{ $work->sdo->emp_name ?? '—' }}</td>
            <th>मोबाइल न.</th><td class="val">{{ $work->sdo->emp_mobile ?? '—' }}</td>
        </tr>
    </table>

    @php
        $shots = $work->work_progress
            ->filter(fn ($e) => filled($e->upload_file)
                && in_array(strtolower(pathinfo($e->upload_file, PATHINFO_EXTENSION)), ['jpg','jpeg','png','gif','webp','bmp']))
            ->take(6);
    @endphp
    @if($shots->isNotEmpty())
        <h2 class="section">छायाचित्र</h2>
        <div class="photos">
            @foreach($shots as $shot)
                <figure>
                    <img src="{{ asset($shot->upload_file) }}" alt="{{ $shot->workTypeStage->work_type_stage_name ?? '' }}">
                    <figcaption>
                        {{ $shot->workTypeStage->work_type_stage_name ?? '' }}
                        @if($shot->status_update_date) — {{ date('d-m-Y', strtotime($shot->status_update_date)) }} @endif
                    </figcaption>
                </figure>
            @endforeach
        </div>
    @endif

    <div class="signatures">
        <div><div class="line">उप अभियंता</div></div>
        <div><div class="line">एसडीओ / सहायक अभियंता</div></div>
        <div><div class="line">कार्यपालन अभियंता</div></div>
        <div><div class="line">सक्षम अधिकारी</div></div>
    </div>

    <div class="footnote">
        <span>{{ config('app.name') }} — कार्य क्रमांक {{ sprintf('%06d', $work->work_id) }}</span>
        <span>यह प्रणाली द्वारा तैयार किया गया दस्तावेज़ है</span>
    </div>
</div>

</body>
</html>
