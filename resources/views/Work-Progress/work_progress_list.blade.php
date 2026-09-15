<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">
            <h4> कार्य प्रगति सूची </h4>
        </div>
    </div>
    {{-- <div class="panel-body"> --}}
        <table class="table table-bordered table-hover table-striped table-sm">
            <thead class="bg-info">
            <tr>
                <th>क्र.</th>
                <th>कार्य आईडी</th>
                <th>कार्य नाम</th>
                <th>कार्य स्थिति</th>
                <th>एमबी स्टेज</th>
                <th>स्थिति अद्यतन तिथि</th>
                <th>अनुमानित समापन की तिथि</th>
                <th>व्यय राशि</th>
                <th>विवरण</th>
                {{-- <th>Action</th> --}}
            </tr>
            </thead>
            <tbody>
            @php $sl = 1; @endphp
            @foreach ($work->work_progress as $list)
                <tr>
                    <td>{{ $sl++ }}</td>
                    <td>{{ $list->work->work_id }}</td>
                    <td>{{ $list->work->work_name }}</td>
                    <td>{{ $list->workStatus->work_status_name }}</td>
                    <td>{{ $list->workTypeStage->work_type_stage_name }}</td>
                    <td>{{ $list->status_update_date }}</td>
                    <td>{{ $list->estimated_completion_date }}</td>
                    <td>{{ $list->expenditure_amount }}</td>
                    <td>{{ $list->description }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    {{-- </div> --}}
</div>


