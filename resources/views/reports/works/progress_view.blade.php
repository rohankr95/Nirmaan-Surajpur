<div class="row">
    <div class="col-sm-12">
        <div class="main">
            <ul class="cbp_tmtimeline">
                @foreach($work->work_type->work_stages as $stage)
                @php $currentStage = getWorkProgressData($work->work_id,$stage->work_type_stage_id); @endphp
                <li>
                    <time class="cbp_tmtime"><span style="white-space: nowrap;"><strong>{{ $stage->stage_number }} - चरण</strong></span> <span style="font-size: 18px;font-weight: bold;">{{ ($currentStage)?date('d/m/Y',strtotime($currentStage->created_at)):'-' }}</span></time>
                    <i class="fa fa-flag-checkered" {{ ($currentStage)?'style="background-color:lightgreen;"':'' }}></i>
                    <div class="cbp_tmlabel p-0" {{ ($currentStage)?'style="background-color:lightgreen;"':'' }}>
                        <h2 class="h4 m-0" style="padding: 10px; font-weight: bold">{{ $stage->work_type_stage_name }}</h2>
                        <div>
                            <table class="table table-bordered table-condensed m-0" {{ ($currentStage)?'style="background-color:lightgreen;"':'' }}>
                                <tbody>
                                    @if($currentStage)
                                    <tr><td>पूर्ण होने की अनुमानित तिथि</td><th>{{ $currentStage->estimated_completion_date}}</th></tr>
                                    <tr><td>व्यय राशि<th>{{ $currentStage->expenditure_amount}}</th></tr>
                                    <tr><td>टिप्पणी</td><th>{{ $currentStage->description}}</th></tr>
                                    <tr><td>संलग्न फाइल</td><td>{!! ($currentStage->file)?'<a href="'. asset($currentStage->file) .'" target="_blank" class="btn btn-xs btn-success">देखें</a>':'' !!}</td></tr>

                                    @else
                                    <tr><td class="text-center">-</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </li>
                @endforeach

            </ul>
        </div>
    </div>
</div>

<div class="row form-group m-t-20">
    <div class="col-sm-12 text-center">
        <span class="">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </span>
    </div>
</div>
