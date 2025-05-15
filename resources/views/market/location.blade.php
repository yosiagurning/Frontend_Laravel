@extends('layouts.app')

@section('title', 'Atur Lokasi Pasar')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        .map-container {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .map-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background: white;
            padding: 8px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .coordinates-display {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .form-control:read-only {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-30">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Atur Lokasi Pasar: {{ optional($market)->name ?? 'Pasar Tidak Ditemukan' }}
                    </h5>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>Klik pada peta untuk menetapkan lokasi pasar.</span>
                    </div>

                    <form action="{{ route('market.location.update', optional($market)->id) }}" method="POST">
                        @csrf
                        
                        <div class="coordinates-display">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-arrows-alt-v"></i></span>
                                            <input type="text" id="latitude" name="latitude" class="form-control"
                                                value="{{ optional($market)->latitude ?? '' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-arrows-alt-h"></i></span>
                                            <input type="text" id="longitude" name="longitude" class="form-control"
                                                value="{{ optional($market)->longitude ?? '' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="map-container">
                            <div id="map" style="width: 100%; height: 500px;"></div>
                            <div class="map-controls">
                                <button type="button" id="reset-view" class="btn btn-sm btn-light">
                                    <i class="fas fa-sync-alt"></i> Reset View
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('market.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Lokasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inisialisasi variabel
    const defaultLat = 2.3333;
    const defaultLng = 99.0667;
    
    // Ambil latitude dan longitude dari variabel Laravel atau gunakan nilai default
    let latitude = parseFloat("{{ optional($market)->latitude ?? defaultLat }}") || defaultLat;
    let longitude = parseFloat("{{ optional($market)->longitude ?? defaultLng }}") || defaultLng;
    
    // Inisialisasi peta menggunakan Leaflet
    const map = L.map('map', {
        zoomControl: false,  // Nonaktifkan kontrol zoom default
    }).setView([latitude, longitude], 14);
    
    // Tambahkan kontrol zoom di kanan bawah
    L.control.zoom({
        position: 'bottomright'
    }).addTo(map);
    
    // Layer citra satelit
    const satelliteLayer = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri, Maxar, Earthstar Geographics',
            maxZoom: 20
        }
    );
    
    // Layer label
    const labelsLayer = L.tileLayer(
        'https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; Esri &mdash; Labels',
            maxZoom: 20
        }
    );
    
    // Layer OpenStreetMap sebagai alternatif
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    });
    
    // Tambahkan layer ke peta
    satelliteLayer.addTo(map);
    labelsLayer.addTo(map);
    
    // Buat kontrol layer untuk beralih antara peta
    const baseMaps = {
        "Satelit": satelliteLayer,
        "OpenStreetMap": osmLayer
    };
    
    const overlayMaps = {
        "Label": labelsLayer
    };
    
    L.control.layers(baseMaps, overlayMaps, {
        position: 'bottomright'
    }).addTo(map);
    
    // Tambahkan marker ke peta dengan ikon kustom
    const marketIcon = L.icon({
        iconUrl: '/images/market-marker.png', // Ganti dengan path ikon Anda
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });
    
    // Gunakan ikon default jika ikon kustom tidak tersedia
    const marker = L.marker([latitude, longitude], {
        draggable: true,
        // icon: marketIcon // Uncomment jika ikon tersedia
    }).addTo(map);
    
    // Tambahkan popup ke marker
    marker.bindPopup(`<b>${"{{ optional($market)->name ?? 'Lokasi Pasar' }}"}</b><br>Drag untuk memindahkan`).openPopup();
    
    // Update input saat marker dipindahkan
    marker.on('dragend', function(event) {
        updateCoordinates(marker.getLatLng());
    });
    
    // Klik peta untuk memindahkan marker
    map.on('click', function(event) {
        marker.setLatLng(event.latlng);
        updateCoordinates(event.latlng);
    });
    
    // Fungsi untuk memperbarui koordinat
    function updateCoordinates(latlng) {
        document.getElementById('latitude').value = latlng.lat.toFixed(6);
        document.getElementById('longitude').value = latlng.lng.toFixed(6);
        marker.bindPopup(`<b>${"{{ optional($market)->name ?? 'Lokasi Pasar' }}"}</b><br>Lat: ${latlng.lat.toFixed(6)}, Lng: ${latlng.lng.toFixed(6)}`).openPopup();
    }
    
    // Reset view ke posisi awal
    document.getElementById('reset-view').addEventListener('click', function() {
        map.setView([latitude, longitude], 14);
    });
    
    // Load dan tampilkan GeoJSON Kecamatan
    fetch('/geojson/kecamatan.json')
        .then(res => res.json())
        .then(data => {
            L.geoJSON(data, {
                style: {
                    color: '#3388ff',
                    weight: 2,
                    fillOpacity: 0.1,
                    fillColor: '#3388ff'
                },
                onEachFeature: function(feature, layer) {
                    const namaKecamatan = feature.properties.nama || feature.properties.NAMOBJ || "Kecamatan";
                    layer.bindPopup(`<b>Kecamatan:</b> ${namaKecamatan}`);
                    
                    // Highlight saat hover
                    layer.on({
                        mouseover: function(e) {
                            layer.setStyle({
                                weight: 3,
                                fillOpacity: 0.3
                            });
                        },
                        mouseout: function(e) {
                            layer.setStyle({
                                weight: 2,
                                fillOpacity: 0.1
                            });
                        }
                    });
                }
            }).addTo(map);
        })
        .catch(error => {
            console.error("Error loading GeoJSON:", error);
        });
});
</script>
@endsection
