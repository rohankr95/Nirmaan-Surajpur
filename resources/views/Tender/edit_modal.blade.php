@include('Tender.tenderform', ['work' => $work, 'work_status' => $work_status, 'edit' => $edit])

@if (!$work->tenderChecked)
    <hr>
    <h4>कार्य आदेश <small class="text-muted">(वैकल्पिक)</small></h4>
    @include('work-complete.agreement', ['work' => $work, 'work_status' => $work_status])
@endif
