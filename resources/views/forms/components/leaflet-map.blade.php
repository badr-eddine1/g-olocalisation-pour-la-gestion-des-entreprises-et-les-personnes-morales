@php
    $mapId = 'leafletMap_' . \Illuminate\Support\Str::random(5);
@endphp

<div wire:ignore>
    <div id="{{ $mapId }}" style="height: 300px;"></div>

    <input type="hidden" wire:model="{{ $getStatePath() }}.lat">
    <input type="hidden" wire:model="{{ $getStatePath() }}.lng">
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let lat = @js($getState()['lat'] ?? 31.63);
        let lng = @js($getState()['lng'] ?? -8.0);

        let map = L.map('{{ $mapId }}').setView([lat, lng], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        // Mise à jour depuis JS vers PHP
        marker.on('dragend', function (e) {
            let pos = marker.getLatLng();
            window.livewire.find('{{ $this->getId() }}')
                .set('{{ $getStatePath() }}.lat', pos.lat)
                .set('{{ $getStatePath() }}.lng', pos.lng);
        });

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            window.livewire.find('{{ $this->getId() }}')
                .set('{{ $getStatePath() }}.lat', e.latlng.lat)
                .set('{{ $getStatePath() }}.lng', e.latlng.lng);
        });

        // Écoute d’un événement browser pour mise à jour depuis PHP
        window.addEventListener('update-map-position', function (event) {
            const { lat, lng } = event.detail;
            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], 12);
        });

        setTimeout(() => map.invalidateSize(), 300);
    });
</script>
@endpush

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
@endpush
