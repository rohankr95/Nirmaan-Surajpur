@if($work->latitude && $work->longitude)
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title text-center"><h4>कार्य स्थान</h4></div>
        </div>
        <div class="panel-body">
            <div id="work-location-map" style="height:340px;width:100%;"></div>
            <div class="text-center" style="margin-top:8px">
                <small class="text-muted">
                    अक्षांश: {{ $work->latitude }} | देशान्तर: {{ $work->longitude }}
                </small>
                <a class="btn btn-xs btn-default" target="_blank"
                   href="https://www.openstreetmap.org/?mlat={{ $work->latitude }}&mlon={{ $work->longitude }}#map=17/{{ $work->latitude }}/{{ $work->longitude }}">
                    बड़े मानचित्र में देखें
                </a>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">
    <script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
    <script>
        (function () {
            // Leaflet resolves its marker images relative to the script; the
            // assets are vendored locally so this works without internet.
            L.Icon.Default.prototype.options.imagePath = '{{ asset('assets/leaflet/images/') }}/';

            var lat = {{ $work->latitude }}, lng = {{ $work->longitude }};
            var map = L.map('work-location-map').setView([lat, lng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup({!! json_encode($work->work_name) !!})
                .openPopup();

            // The panel can be laid out before the map is sized, which leaves
            // Leaflet measuring a zero-height container.
            setTimeout(function () { map.invalidateSize(); }, 200);
        })();
    </script>
@endif
