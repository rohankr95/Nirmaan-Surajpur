@php
    // Progress photos are already stage-linked through work_progress.mb_stages_id;
    // they have simply never been presented as evidence of the work over time.
    // A status update can carry several files (work_progress_images) on top of
    // the older single upload_file column, so each entry is flattened into a
    // {file, date} pair before grouping.
    $groups = $work->work_progress
        ->flatMap(function ($entry) {
            $files = $entry->images->pluck('file_path');
            if (filled($entry->upload_file)) {
                $files->push($entry->upload_file);
            }
            return $files->unique()->map(fn ($file) => (object) [
                'file' => $file,
                'date' => $entry->status_update_date,
                'stage' => $entry->workTypeStage->work_type_stage_name ?? 'अन्य चरण',
            ]);
        })
        ->groupBy('stage');

    $completionFile = $work->work_complete->upload_file ?? null;
@endphp

<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-title text-center">
            <h4>कार्य के छायाचित्र</h4>
        </div>
    </div>
    <div class="panel-body">
        @if($groups->isEmpty() && !$completionFile)
            <p class="text-center text-muted m-0">इस कार्य के लिए कोई छायाचित्र अपलोड नहीं किया गया है</p>
        @else
            <div class="row">
                @foreach($groups as $stageName => $entries)
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <b>{{ $stageName }}</b>
                                <span class="badge pull-right">{{ $entries->count() }}</span>
                            </div>
                            <div class="panel-body p-0">
                                @foreach($entries as $entry)
                                    @php $isImage = in_array(strtolower(pathinfo($entry->file, PATHINFO_EXTENSION)), ['jpg','jpeg','png','gif','webp','bmp']); @endphp
                                    <a href="{{ asset($entry->file) }}" target="_blank" title="{{ $entry->date }}">
                                        @if($isImage)
                                            <img src="{{ asset($entry->file) }}"
                                                 alt="{{ $stageName }} — {{ $entry->date }}"
                                                 style="width:100%;height:150px;object-fit:cover;margin-bottom:4px;">
                                        @else
                                            <div class="text-center" style="height:150px;line-height:150px;background:#eee;margin-bottom:4px;">
                                                <i class="fa fa-file-text-o"></i> दस्तावेज़ देखें
                                            </div>
                                        @endif
                                    </a>
                                    <div class="text-center" style="font-size:12px;margin-bottom:8px;">
                                        {{ $entry->date ? date('d-m-Y', strtotime($entry->date)) : '' }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($completionFile)
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <b>कार्य पूर्ण</b>
                            </div>
                            <div class="panel-body p-0">
                                <a href="{{ asset($completionFile) }}" target="_blank">
                                    <img src="{{ asset($completionFile) }}" alt="कार्य पूर्ण"
                                         style="width:100%;height:150px;object-fit:cover;margin-bottom:4px;">
                                </a>
                                <div class="text-center" style="font-size:12px;margin-bottom:8px;">
                                    {{ optional($work->work_complete)->completion_date ? date('d-m-Y', strtotime($work->work_complete->completion_date)) : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
