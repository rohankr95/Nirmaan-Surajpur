@extends('layout.app')
{{-- @section('title', 'Dashboard') --}}
@section('content')
    @include('layout.includes.page_header', ['icon' => 'home', 'text' => 'Dashboard'])
    <!-- Main content -->
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4> Work Progress </h4>
                        </div>
                    </div>
                    <div class="panel-body">
                      @include('Work-Progress.workprogressform')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
 <script>
     $(document).ready(function() {
        $('#work_id').on('change', function() {
            WorkToMbStages(this.value,"#mb_stages");
        });
        function WorkToMbStages(work_id,mb_stages,selectedValue='') {
        var work_id = work_id;
        $(mb_stages).html('');
         $.ajax({
                 url: "{{ url('api/fetch-WorkToMbStages') }}",
                 type: "POST",
                 data: {
                     work_id: work_id,
                     _token: '{{ csrf_token() }}'
                 },
                 dataType: 'json',
                 success: function (result) {
                    //  alert(result);

                     $(mb_stages).html('<option value="">-- Select Stages --</option>');
                     $.each(result.stages, function (key, value) {
                         var isSelected = (value.work_type_stage_id===selectedValue)?'selected':'';
                         $(mb_stages).append('<option value="' + value
                             .work_type_stage_id + '" '+isSelected+  '>'+ value
                             .work_type_stage_name + '</option>');
                     });
                 }
         });

    }

    });


    $(document).ready(function() {
        $('#work_id').on('change', function() {
            WorkToWorkStatus(this.value,"#work_status");
        });
        function WorkToWorkStatus(work_id,work_status,selectedValue='') {
        var work_id = work_id;
        // $(work_status).html('');
         $.ajax({
                 url: "{{ url('api/fetch-WorkToWorkStatus') }}",
                 type: "POST",
                 data: {
                     work_id: work_id,
                     _token: '{{ csrf_token() }}'
                 },
                 dataType: 'json',
                 success: function (result) {

                    $('#work_status').val(result.status);
                    //  $(work_status).html('<option value="">-- Select Work Status --</option>');
                    //  $.each(result.status, function (key, value) {
                    //      var isSelected = (value.work_status_id===selectedValue)?'selected':'';
                    //      $(work_status).append('<option value="' + value
                    //          .work_status_id + '" '+isSelected+  '>'+ value
                    //          .work_status_name + '</option>');
                    //  });
                 }
         });

    }

    });
</script>
@endsection
