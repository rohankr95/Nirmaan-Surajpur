@extends('layout.app')
@section('content')
    @include('layout.includes.page_header',['icon'=>'map','title'=>'कार्य मानचित्र'])
    <div class="content">

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form method="get" action="{{ route('reports.work-map') }}" class="form-inline">
                            <label for="financial_year">वित्तीय वर्ष</label>
                            <select name="financial_year" id="financial_year" class="form-control">
                                <option value="">-- सभी --</option>
                                @foreach(get_financial_years() as $fy)
                                    <option value="{{ $fy->id }}" {{ echo_selected($request->financial_year == $fy->id) }}>{{ $fy->name }}</option>
                                @endforeach
                            </select>
                            <label for="status">कार्य स्थिति</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">-- सभी --</option>
                                @foreach(get_work_statuses() as $st)
                                    <option value="{{ $st->work_status_id }}" {{ echo_selected($request->status == $st->work_status_id) }}>{{ $st->work_status_name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> खोजें</button>
                            <a href="{{ route('reports.work-map') }}" class="btn btn-default">रीसेट</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4>
                                कार्य मानचित्र
                                <span class="pull-right small">
                                    मानचित्र पर {{ count($mapped) }} कार्य
                                    @if($untagged > 0)
                                        | <span class="text-warning">{{ $untagged }} कार्य बिना जियो टैग</span>
                                    @endif
                                </span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if(count($mapped) === 0)
                            <p class="text-center text-muted m-0">
                                किसी कार्य में अक्षांश/देशान्तर दर्ज नहीं है। कार्य संपादित कर स्थान जोड़ें।
                            </p>
                        @else
                            <div id="works-map" style="height:560px;width:100%;"></div>
                            <div style="margin-top:10px">
                                <span class="label" style="background:#2e7d32">कार्य पूर्ण</span>
                                <span class="label" style="background:#f9a825">कार्य प्रगति पर</span>
                                <span class="label" style="background:#c62828">निरस्त / बंद</span>
                                <span class="label" style="background:#1565c0">अन्य</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(count($mapped) > 0)
        <link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">
        <script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
        <script>
            (function () {
                var works = {!! json_encode($mapped) !!};
                var map = L.map('works-map');

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                var bounds = [];
                works.forEach(function (w) {
                    var marker = L.circleMarker([w.lat, w.lng], {
                        radius: 8,
                        color: '#fff',
                        weight: 2,
                        fillColor: w.colour,
                        fillOpacity: 0.9
                    }).addTo(map);

                    marker.bindPopup(
                        '<b>' + w.name + '</b><br>' + w.status +
                        '<br><a href="' + w.url + '">विवरण देखें</a>'
                    );
                    bounds.push([w.lat, w.lng]);
                });

                map.fitBounds(bounds, { padding: [40, 40], maxZoom: 15 });
                setTimeout(function () { map.invalidateSize(); }, 200);

                // Exposed so the smoke test can confirm the markers were placed.
                window.__worksOnMap = works.length;
            })();
        </script>
    @endif
@endsection
