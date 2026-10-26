@php
    $latValue = old('latitude', $lat ?? null);
    $lngValue = old('longitude', $lng ?? null);
@endphp

<div class="form-group">
    <label>Position GPS <span class="muted">(facultatif, améliore le tri par proximité)</span></label>
    <div class="gps-row">
        <button type="button" class="btn btn-outline" data-geolocate>Me localiser</button>
        <span class="muted" data-geo-status>{{ $latValue !== null && $lngValue !== null ? 'Position enregistrée' : 'Aucune position' }}</span>
    </div>
    <input type="hidden" name="latitude" value="{{ $latValue }}">
    <input type="hidden" name="longitude" value="{{ $lngValue }}">
</div>

<script>
(function () {
    var btn = document.querySelector('[data-geolocate]');
    if (!btn) return;

    var status = document.querySelector('[data-geo-status]');
    var latInput = document.querySelector('input[name="latitude"]');
    var lngInput = document.querySelector('input[name="longitude"]');

    btn.addEventListener('click', function () {
        if (!navigator.geolocation) {
            status.textContent = 'Géolocalisation non supportée par ce navigateur.';
            return;
        }

        btn.disabled = true;
        status.textContent = 'Localisation en cours…';

        navigator.geolocation.getCurrentPosition(function (pos) {
            var lat = pos.coords.latitude.toFixed(7);
            var lng = pos.coords.longitude.toFixed(7);
            latInput.value = lat;
            lngInput.value = lng;
            status.textContent = 'Position obtenue, recherche de l\'adresse…';

            fetch('{{ route('geocode.reverse') }}?lat=' + lat + '&lng=' + lng, { headers: { 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    var city = document.getElementById('ville');
                    var lieu = document.getElementById('lieu');
                    if (city && data.city && !city.value) city.value = data.city;
                    if (lieu && data.label && !lieu.value) lieu.value = data.label;
                    status.textContent = data.label ? 'Position : ' + data.label : 'Position obtenue (' + lat + ', ' + lng + ')';
                    btn.disabled = false;
                })
                .catch(function () {
                    status.textContent = 'Position obtenue (' + lat + ', ' + lng + ')';
                    btn.disabled = false;
                });
        }, function () {
            status.textContent = 'Position refusée ou indisponible.';
            btn.disabled = false;
        }, { enableHighAccuracy: true, timeout: 10000 });
    });
})();
</script>
