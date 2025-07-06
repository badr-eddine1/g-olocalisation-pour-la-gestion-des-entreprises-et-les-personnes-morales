<div>
    <div id="leafletMap" style="height: 300px;"></div>

    {{-- Champs cachés synchronisés avec Livewire --}}
    <input type="hidden" wire:model.defer="{{ $getStatePath() }}.lat">
    <input type="hidden" wire:model.defer="{{ $getStatePath() }}.lng">
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let lat = @js($getState()['lat'] ?? 31.63);
        let lng = @js($getState()['lng'] ?? -8.0);

        let map = L.map('leafletMap').setView([lat, lng], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        // Mise à jour après glissement du marqueur
        marker.on('dragend', function (e) {
            let pos = marker.getLatLng();
            @this.set('{{ $getStatePath() }}.lat', pos.lat);
            @this.set('{{ $getStatePath() }}.lng', pos.lng);
        });

        // Mise à jour après clic sur la carte
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            @this.set('{{ $getStatePath() }}.lat', e.latlng.lat);
            @this.set('{{ $getStatePath() }}.lng', e.latlng.lng);
        });
    });
</script>
@endpush
