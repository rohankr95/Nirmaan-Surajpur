@extends('layout.app')
@section('title', 'कार्य जोड़ें')
@section('content')
    @include('layout.includes.page_header', ['icon' => 'home', 'title' => 'कार्य जोड़ें'])
    <!-- Main content -->
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12">
              @include('work.workform')
            </div>
        </div>
    </div>  
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            changeLocationTypeLayout();
            @if (isset($work))
              
                changeLocationTypeLayout({{ $work->location_type_id }});
                @if ($work->location_type_id == 1)
                    $('#block').val({{ $work->village->grampanchayat->block_id }});
                    blockToGramPanchayat({{ $work->village->grampanchayat->block_id }}, '#gp',
                        {{ $work->village->grampanchayat->grampanchayat_id }});
                    grampanchayatToVillages({{ $work->village->grampanchayat->grampanchayat_id }}, '#village',
                        {{ $work->village_id }});
                @else
                    $('#city').val({{ $work->ward->city_id }});
                    cityToWard({{ $work->ward->city_id }}, '#ward', {{ $work->ward->ward_id }});
                @endif
            @endif
        });

        function setAtrr() {
            $('#city').prop('required',$('#location_type').val()==2);
            $('#ward').prop('required',$('#location_type').val()==2);
            $('#block').prop('required',$('#location_type').val()==1);
            $('#gp').prop('required',$('#location_type').val()==1);
           $('#village').prop('required',$('#location_type').val()==1);
        }

    </script>
@endsection
