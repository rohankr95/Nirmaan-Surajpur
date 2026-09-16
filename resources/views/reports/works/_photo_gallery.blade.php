@php
    // Progress photos are already stage-linked through work_progress.mb_stages_id;
    // they have simply never been presented as evidence of the work over time.
    // A status update can carry several files (work_progress_images) on top of
    // the older single upload_file column, so each entry is flattened into a
    // {file, date, type, id} pair before grouping -- type/id identify which
    // row to delete (a work_progress_images row, or the legacy upload_file
    // column on the work_progress entry itself).
    $groups = $work->work_progress
        ->flatMap(function ($entry) {
            $items = $entry->images->map(fn ($img) => (object) [
                'file' => $img->file_path,
                'type' => 'image',
                'id' => $img->wp_image_id,
            ]);
            if (filled($entry->upload_file)) {
                $items->push((object) [
                    'file' => $entry->upload_file,
                    'type' => 'legacy',
                    'id' => $entry->wp_id,
                ]);
            }
            return $items->unique('file')->map(fn ($item) => (object) [
                'file' => $item->file,
                'type' => $item->type,
                'id' => $item->id,
                'date' => $entry->status_update_date,
                'stage' => $entry->workTypeStage->work_type_stage_name ?? 'अन्य चरण',
            ]);
        })
        ->groupBy('stage');

    $completion = $work->work_complete;
@endphp

<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-title text-center">
            <h4>कार्य के छायाचित्र
                <span class="pull-right">
                    <button onclick="openEditModal('छायाचित्र जोड़ें / बदलें','{{ route('work-progress-images.create', ['work_id' => $work->work_id]) }}');" class="btn btn-sm btn-warning">
                        <i class="fa fa-pencil"></i>
                    </button>
                </span>
            </h4>
        </div>
    </div>
    <div class="panel-body">
        @if($groups->isEmpty() && !$completion)
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
                                    @php
                                        $isImage = in_array(strtolower(pathinfo($entry->file, PATHINFO_EXTENSION)), ['jpg','jpeg','png','gif','webp','bmp']);
                                        $deleteUrl = $entry->type === 'image'
                                            ? route('work-progress-images.destroy', $entry->id)
                                            : route('work-progress.destroy-legacy-photo', $entry->id);
                                    @endphp
                                    <div style="position:relative">
                                        <form method="post" action="{{ $deleteUrl }}"
                                              onsubmit="return confirm('इस छायाचित्र को हटाएं?');"
                                              style="position:absolute;top:4px;right:4px;z-index:2;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs" title="हटाएं">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form>
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
                                    </div>
                                    <div class="text-center" style="font-size:12px;margin-bottom:8px;">
                                        {{ $entry->date ? date('d-m-Y', strtotime($entry->date)) : '' }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($completion)
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <b>कार्य पूर्ण</b>
                                <span class="pull-right">
                                    <button onclick="openEditModal('कार्य पूर्ण छायाचित्र अद्यतन करें','{{ route('work-complete.edit-photo', $completion->id) }}');" class="btn btn-warning btn-xs" title="बदलें">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </span>
                            </div>
                            <div class="panel-body p-0">
                                @if($completion->upload_file)
                                    @php $completionIsImage = in_array(strtolower(pathinfo($completion->upload_file, PATHINFO_EXTENSION)), ['jpg','jpeg','png','gif','webp','bmp']); @endphp
                                    <div style="position:relative">
                                        <form method="post" action="{{ route('work-complete.destroy-photo', $completion->id) }}"
                                              onsubmit="return confirm('इस छायाचित्र को हटाएं?');"
                                              style="position:absolute;top:4px;right:4px;z-index:2;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs" title="हटाएं">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form>
                                        <a href="{{ asset($completion->upload_file) }}" target="_blank">
                                            @if($completionIsImage)
                                                <img src="{{ asset($completion->upload_file) }}" alt="कार्य पूर्ण"
                                                     style="width:100%;height:150px;object-fit:cover;margin-bottom:4px;">
                                            @else
                                                <div class="text-center" style="height:150px;line-height:150px;background:#eee;margin-bottom:4px;">
                                                    <i class="fa fa-file-text-o"></i> दस्तावेज़ देखें
                                                </div>
                                            @endif
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center text-muted" style="height:150px;line-height:150px;background:#f5f5f5;margin-bottom:4px;">
                                        कोई छायाचित्र नहीं
                                    </div>
                                @endif
                                <div class="text-center" style="font-size:12px;margin-bottom:8px;">
                                    {{ $completion->completion_date ? date('d-m-Y', strtotime($completion->completion_date)) : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
