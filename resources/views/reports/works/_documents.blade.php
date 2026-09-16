@php
    $byType = $work->documents->groupBy('doc_type');
@endphp

<div class="panel panel-warning">
    <div class="panel-heading">
        <div class="panel-title text-center"><h4>प्रमाण पत्र एवं दस्तावेज़</h4></div>
    </div>
    <div class="panel-body">

        <div class="row" style="margin-bottom:12px">
            @foreach(['uc' => 'यूसी', 'cc' => 'सीसी', 'rwh' => 'आर.डब्लू.एच'] as $type => $short)
                @php $has = ($byType[$type] ?? collect())->isNotEmpty(); @endphp
                <div class="col-sm-4">
                    <div class="panel {{ $has ? 'panel-success' : 'panel-default' }}">
                        <div class="panel-body text-center" style="padding:10px">
                            <b>{{ $short }} अपलोड</b><br>
                            <span class="{{ $has ? 'text-success' : 'text-danger' }}">
                                {{ $has ? 'हाँ' : 'नहीं' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($work->documents->isNotEmpty())
            <table class="table table-bordered table-condensed table-striped">
                <thead class="bg-info">
                <tr>
                    <th width="5%">क्र.</th>
                    <th>प्रकार</th>
                    <th>संदर्भ क्रमांक</th>
                    <th>दिनांक</th>
                    <th>टिप्पणी</th>
                    <th>अपलोड करने वाला</th>
                    <th width="16%">कार्यवाही</th>
                </tr>
                </thead>
                <tbody>
                @php $sl = 1; @endphp
                @foreach($work->documents as $doc)
                    <tr>
                        <td>{{ $sl++ }}</td>
                        <td>{{ $doc->type_label }}</td>
                        <td>{{ $doc->reference_no ?? '—' }}</td>
                        <td>{{ optional($doc->document_date)->format('d-m-Y') ?? '—' }}</td>
                        <td>{{ $doc->remark ?? '—' }}</td>
                        <td>{{ $doc->uploader->name ?? '—' }}</td>
                        <td>
                            <a href="{{ asset($doc->file_path) }}" target="_blank" class="btn btn-xs btn-primary">
                                <i class="ti-eye"></i> देखें
                            </a>
                            <form method="post" action="{{ route('work-documents.destroy', $doc->document_id) }}"
                                  style="display:inline" onsubmit="return confirm('यह दस्तावेज़ हटाएं?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger"><i class="ti-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        <form method="post" action="{{ route('work-documents.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="work_id" value="{{ $work->work_id }}">
            <div class="row">
                <div class="col-sm-3">
                    <label for="doc_type" class="col-form-label">दस्तावेज़ का प्रकार</label>
                    <select name="doc_type" id="doc_type" class="form-control form-select" required>
                        @foreach(\App\Models\WorkDocument::TYPES as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="reference_no" class="col-form-label">संदर्भ क्रमांक</label>
                    <input type="text" name="reference_no" id="reference_no" class="form-control" placeholder="क्रमांक">
                </div>
                <div class="col-sm-2">
                    <label for="document_date" class="col-form-label">दिनांक</label>
                    <input type="date" name="document_date" id="document_date" class="form-control">
                </div>
                <div class="col-sm-3">
                    <label for="document_file" class="col-form-label">फ़ाइल (IMAGE/PDF)</label>
                    <input type="file" name="file" id="document_file" class="form-control"
                           accept=".jpg,.jpeg,.png,.gif,.webp,.pdf" required>
                </div>
                <div class="col-sm-2">
                    <label class="col-form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-success btn-block">अपलोड करें</button>
                </div>
            </div>
        </form>
    </div>
</div>
