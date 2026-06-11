<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1>
        <i class="fas fa-dolly me-2" style="color: var(--warning)"></i>
        <?= $is_edit ? 'Edit Order Pengiriman' : 'Buat Order Pengiriman Baru' ?>
    </h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span>
        <a href="<?= base_url('admin/orders') ?>">Order Logistik</a>
        <span class="separator">/</span>
        <span><?= $is_edit ? 'Edit' : 'Tambah' ?></span>
    </div>
</div>

<div class="row" data-aos="fade-up">
    <div class="col-12">
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-file-invoice" style="color: var(--warning)"></i>
                    Form Rincian Pengiriman &amp; Peta Google Maps
                </div>
            </div>
            <div class="content-card-body" style="padding: 24px;">
                <form method="POST" action="<?= $is_edit ? base_url('admin/orders/update/' . $order['id']) : base_url('admin/orders/store') ?>" autocomplete="off">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                    <!-- Custom CSS for Responsive Side-by-Side Layout and Google Autocomplete styling -->
                    <style>
                        .pac-container {
                            background-color: #1e293b !important;
                            border: 1px solid #334155 !important;
                            border-radius: 8px !important;
                            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
                            z-index: 99999 !important;
                            font-family: inherit !important;
                            margin-top: 4px !important;
                        }
                        .pac-item {
                            padding: 10px 14px !important;
                            color: #cbd5e1 !important;
                            cursor: pointer !important;
                            border-top: 1px solid #1e293b !important;
                            font-size: 13px !important;
                            transition: background-color 0.15s ease;
                        }
                        .pac-item:hover {
                            background-color: rgba(99, 102, 241, 0.2) !important;
                        }
                        .pac-item-query {
                            color: #ffffff !important;
                            font-size: 13px !important;
                        }
                        .pac-matched {
                            color: #38bdf8 !important;
                        }
                        .pac-icon {
                            display: none !important;
                        }
                        @media (min-width: 992px) {
                            .picker-map-col {
                                border-left: 1px solid var(--dark-border);
                                padding-left: 32px !important;
                            }
                        }
                        @media (max-width: 991px) {
                            .picker-map-col {
                                margin-top: 32px;
                                border-top: 1px solid var(--dark-border);
                                padding-top: 32px !important;
                            }
                        }
                    </style>

                    <div class="row">
                        <!-- LEFT COLUMN: Form Inputs -->
                        <div class="col-lg-5 col-12">
                            <!-- Customer Name -->
                            <div class="form-group">
                                <label class="form-label-custom" for="customer_name">Nama Pelanggan (Customer Name)</label>
                                <input type="text" name="customer_name" id="customer_name" 
                                       class="form-control-custom" placeholder="Contoh: PT Sumber Alam Makmur" 
                                       value="<?= htmlspecialchars($order['customer_name'] ?? '') ?>" required>
                            </div>

                            <!-- Cargo Description -->
                            <div class="form-group">
                                <label class="form-label-custom" for="cargo_description">Deskripsi Muatan (Cargo Description)</label>
                                <textarea name="cargo_description" id="cargo_description" rows="3" 
                                          class="form-control-custom" placeholder="Contoh: 10 Palet Semen Tiga Roda" 
                                          required><?= htmlspecialchars($order['cargo_description'] ?? '') ?></textarea>
                            </div>

                            <!-- Dimensions / Weight Row -->
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label class="form-label-custom" for="weight">Berat Kargo (Ton)</label>
                                    <input type="number" step="0.01" name="weight" id="weight" 
                                           class="form-control-custom" placeholder="Contoh: 12.50" 
                                           value="<?= htmlspecialchars($order['weight'] ?? '0.00') ?>" required>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label class="form-label-custom" for="volume">Volume Kargo (CBM)</label>
                                    <input type="number" step="0.01" name="volume" id="volume" 
                                           class="form-control-custom" placeholder="Contoh: 35.5" 
                                           value="<?= htmlspecialchars($order['volume'] ?? '0.00') ?>" required>
                                </div>
                            </div>

                            <!-- Route Row -->
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label class="form-label-custom" for="origin">Kota Asal (Origin)</label>
                                    <input type="text" name="origin" id="origin" 
                                           class="form-control-custom" placeholder="Ketik rute asal..." 
                                           value="<?= htmlspecialchars($order['origin'] ?? '') ?>" required>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label class="form-label-custom" for="destination">Kota Tujuan (Destination)</label>
                                    <input type="text" name="destination" id="destination" 
                                           class="form-control-custom" placeholder="Ketik rute tujuan..." 
                                           value="<?= htmlspecialchars($order['destination'] ?? '') ?>" required>
                                </div>
                            </div>

                            <!-- ETA -->
                            <div class="form-group mt-1">
                                <label class="form-label-custom" for="eta">Estimasi Waktu Tiba (ETA)</label>
                                <?php 
                                $eta_val = '';
                                if (!empty($order['eta'])) {
                                    $eta_val = date('Y-m-d\TH:i', strtotime($order['eta']));
                                }
                                ?>
                                <input type="datetime-local" name="eta" id="eta" 
                                       class="form-control-custom" 
                                       value="<?= $eta_val ?>" required>
                            </div>

                            <!-- Form Actions -->
                            <div style="margin-top: 32px; display: flex; gap: 12px;">
                                <a href="<?= base_url('admin/orders') ?>" class="btn-primary-custom" style="flex: 1; justify-content: center; background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc;">
                                    Batal
                                </a>
                                <button type="submit" class="btn-primary-custom" style="flex: 2; justify-content: center;">
                                    <i class="fas fa-save me-1"></i> Simpan Order
                                </button>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: Google Maps Picker (Enlarged) -->
                        <div class="col-lg-7 col-12 picker-map-col">
                            <!-- Hidden Coordinate Fields -->
                            <input type="hidden" name="origin_latitude" id="origin_latitude" value="<?= htmlspecialchars($order['origin_latitude'] ?? '') ?>">
                            <input type="hidden" name="origin_longitude" id="origin_longitude" value="<?= htmlspecialchars($order['origin_longitude'] ?? '') ?>">
                            <input type="hidden" name="destination_latitude" id="destination_latitude" value="<?= htmlspecialchars($order['destination_latitude'] ?? '') ?>">
                            <input type="hidden" name="destination_longitude" id="destination_longitude" value="<?= htmlspecialchars($order['destination_longitude'] ?? '') ?>">

                            <!-- Load Google Maps JavaScript API with places library -->
                            <script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item('google_maps_api_key') ?>&libraries=places&language=id"></script>

                            <div class="form-group" style="height: 100%; display: flex; flex-direction: column;">
                                <label class="form-label-custom">
                                    <i class="fas fa-map-location-dot me-1" style="color: var(--primary-light);"></i> 
                                    Penentu Lokasi Peta (Ketik alamat pencarian atau klik peta)
                                </label>
                                
                                <div id="picker-map" style="height: 430px; border-radius: 12px; border: 1px solid var(--dark-border); margin-bottom: 12px;"></div>
                                
                                <div style="font-size: 12px; color: var(--text-muted); display: flex; justify-content: space-between; margin-bottom: 16px; background: rgba(30,41,59,0.5); padding: 10px 14px; border-radius: 8px; border: 1px solid var(--dark-border);">
                                    <span><i class="fas fa-circle-dot me-1" style="color: #10b981;"></i> <strong>Asal:</strong> <span id="lbl_origin_coords"><?= !empty($order['origin_latitude']) ? (float)$order['origin_latitude'] . ', ' . (float)$order['origin_longitude'] : 'Belum ditentukan' ?></span></span>
                                    <span><i class="fas fa-location-dot me-1" style="color: #dc2626;"></i> <strong>Tujuan:</strong> <span id="lbl_destination_coords"><?= !empty($order['destination_latitude']) ? (float)$order['destination_latitude'] . ', ' . (float)$order['destination_longitude'] : 'Belum ditentukan' ?></span></span>
                                </div>
                                
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <button type="button" id="btn-select-origin" class="btn btn-sm text-white" style="font-size: 11px; background: #10b981; border: 1px solid #10b981; padding: 8px 16px; border-radius: 6px; font-weight: 600;">
                                        <i class="fas fa-circle-dot me-1"></i> Klik &amp; Pilih Titik Asal
                                    </button>
                                    <button type="button" id="btn-select-destination" class="btn btn-sm text-white" style="font-size: 11px; background: rgba(220,38,38,0.15); border: 1px solid #dc2626; color: #fca5a5; padding: 8px 16px; border-radius: 6px; font-weight: 600;">
                                        <i class="fas fa-location-dot me-1"></i> Klik &amp; Pilih Titik Tujuan
                                    </button>
                                    <button type="button" id="btn-detect-gps" class="btn btn-sm text-white" style="font-size: 11px; background: rgba(99,102,241,0.15); border: 1px solid var(--primary-light); color: var(--primary-light); padding: 8px 16px; border-radius: 6px; font-weight: 600; margin-left: auto;">
                                        <i class="fas fa-location-crosshairs me-1"></i> Deteksi GPS Admin (Asal)
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Default coords: Midpoint Java
    let defaultLat = -6.9175;
    let defaultLng = 110.0;
    
    // Coords from order if exist
    const initialOriginLat = <?= !empty($order['origin_latitude']) ? (float)$order['origin_latitude'] : 'null' ?>;
    const initialOriginLng = <?= !empty($order['origin_longitude']) ? (float)$order['origin_longitude'] : 'null' ?>;
    const initialDestLat = <?= !empty($order['destination_latitude']) ? (float)$order['destination_latitude'] : 'null' ?>;
    const initialDestLng = <?= !empty($order['destination_longitude']) ? (float)$order['destination_longitude'] : 'null' ?>;

    // Dark Mode custom styles for Google Maps
    const darkMapStyle = [
        { elementType: 'geometry', stylers: [{ color: '#1e293b' }] },
        { elementType: 'labels.text.stroke', stylers: [{ color: '#1e293b' }] },
        { elementType: 'labels.text.fill', stylers: [{ color: '#94a3b8' }] },
        { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#334155' }] },
        { featureType: 'road', elementType: 'geometry.stroke', stylers: [{ color: '#1e293b' }] },
        { featureType: 'road', elementType: 'labels.text.fill', stylers: [{ color: '#cbd5e1' }] },
        { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#0f172a' }] },
        { featureType: 'water', elementType: 'labels.text.fill', stylers: [{ color: '#475569' }] },
        { featureType: 'poi', stylers: [{ visibility: 'simplified' }] }
    ];

    const map = new google.maps.Map(document.getElementById('picker-map'), {
        center: { lat: defaultLat, lng: defaultLng },
        zoom: 7,
        styles: darkMapStyle,
        mapTypeControl: false,
        streetViewControl: false
    });

    // Directions Service for routing
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true,
        polylineOptions: {
            strokeColor: '#6366f1',
            strokeOpacity: 0.8,
            strokeWeight: 5
        }
    });

    let originMarker = null;
    let destMarker = null;
    let activeMode = 'origin'; // 'origin' or 'destination'

    const btnOrigin = document.getElementById('btn-select-origin');
    const btnDest = document.getElementById('btn-select-destination');

    function updateButtonUI() {
        if (activeMode === 'origin') {
            btnOrigin.style.background = '#10b981';
            btnOrigin.style.borderColor = '#10b981';
            btnOrigin.style.color = '#fff';
            
            btnDest.style.background = 'rgba(220,38,38,0.15)';
            btnDest.style.borderColor = '#dc2626';
            btnDest.style.color = '#fca5a5';
        } else {
            btnOrigin.style.background = 'rgba(16,185,129,0.15)';
            btnOrigin.style.borderColor = '#10b981';
            btnOrigin.style.color = '#10b981';
            
            btnDest.style.background = '#dc2626';
            btnDest.style.borderColor = '#dc2626';
            btnDest.style.color = '#fff';
        }
    }
    updateButtonUI();

    btnOrigin.addEventListener('click', function() {
        activeMode = 'origin';
        updateButtonUI();
    });

    btnDest.addEventListener('click', function() {
        activeMode = 'destination';
        updateButtonUI();
    });

    function setOriginCoords(lat, lng) {
        document.getElementById('origin_latitude').value = lat;
        document.getElementById('origin_longitude').value = lng;
        document.getElementById('lbl_origin_coords').innerText = lat.toFixed(5) + ', ' + lng.toFixed(5);
    }

    function setDestCoords(lat, lng) {
        document.getElementById('destination_latitude').value = lat;
        document.getElementById('destination_longitude').value = lng;
        document.getElementById('lbl_destination_coords').innerText = lat.toFixed(5) + ', ' + lng.toFixed(5);
    }

    function calculateAndDisplayRoute() {
        const oLat = parseFloat(document.getElementById('origin_latitude').value);
        const oLng = parseFloat(document.getElementById('origin_longitude').value);
        const dLat = parseFloat(document.getElementById('destination_latitude').value);
        const dLng = parseFloat(document.getElementById('destination_longitude').value);
        
        if (!isNaN(oLat) && !isNaN(oLng) && !isNaN(dLat) && !isNaN(dLng)) {
            directionsService.route({
                origin: new google.maps.LatLng(oLat, oLng),
                destination: new google.maps.LatLng(dLat, dLng),
                travelMode: google.maps.TravelMode.DRIVING
            }, function(response, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(response);
                } else {
                    console.error('Directions request failed: ' + status);
                }
            });
        }
    }

    // Set initial markers from db
    if (initialOriginLat && initialOriginLng) {
        const originLatLng = new google.maps.LatLng(initialOriginLat, initialOriginLng);
        originMarker = new google.maps.Marker({
            position: originLatLng,
            map: map,
            title: 'Asal',
            draggable: true,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 8,
                fillColor: '#10b981',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 2
            }
        });
        
        originMarker.addListener('dragend', function() {
            const pos = originMarker.getPosition();
            setOriginCoords(pos.lat(), pos.lng());
            calculateAndDisplayRoute();
        });
        
        map.setCenter(originLatLng);
        map.setZoom(9);
    }

    if (initialDestLat && initialDestLng) {
        const destLatLng = new google.maps.LatLng(initialDestLat, initialDestLng);
        destMarker = new google.maps.Marker({
            position: destLatLng,
            map: map,
            title: 'Tujuan',
            draggable: true,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 8,
                fillColor: '#dc2626',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 2
            }
        });
        
        destMarker.addListener('dragend', function() {
            const pos = destMarker.getPosition();
            setDestCoords(pos.lat(), pos.lng());
            calculateAndDisplayRoute();
        });
        
        calculateAndDisplayRoute();
    }

    // Map click action
    map.addListener('click', function(e) {
        const lat = e.latLng.lat();
        const lng = e.latLng.lng();
        const latlng = e.latLng;

        if (activeMode === 'origin') {
            if (originMarker) {
                originMarker.setPosition(latlng);
            } else {
                originMarker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    title: 'Asal',
                    draggable: true,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 8,
                        fillColor: '#10b981',
                        fillOpacity: 1,
                        strokeColor: '#ffffff',
                        strokeWeight: 2
                    }
                });
                
                originMarker.addListener('dragend', function() {
                    const pos = originMarker.getPosition();
                    setOriginCoords(pos.lat(), pos.lng());
                    calculateAndDisplayRoute();
                });
            }
            setOriginCoords(lat, lng);

            // Reverse geocode to get address name
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: latlng }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    let addr = results[0].formatted_address;
                    addr = addr.replace(', Indonesia', '');
                    document.getElementById('origin').value = addr;
                } else {
                    document.getElementById('origin').value = lat.toFixed(5) + ', ' + lng.toFixed(5);
                }
            });
        } else {
            if (destMarker) {
                destMarker.setPosition(latlng);
            } else {
                destMarker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    title: 'Tujuan',
                    draggable: true,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 8,
                        fillColor: '#dc2626',
                        fillOpacity: 1,
                        strokeColor: '#ffffff',
                        strokeWeight: 2
                    }
                });
                
                destMarker.addListener('dragend', function() {
                    const pos = destMarker.getPosition();
                    setDestCoords(pos.lat(), pos.lng());
                    calculateAndDisplayRoute();
                });
            }
            setDestCoords(lat, lng);

            // Reverse geocode to get address name
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: latlng }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    let addr = results[0].formatted_address;
                    addr = addr.replace(', Indonesia', '');
                    document.getElementById('destination').value = addr;
                } else {
                    document.getElementById('destination').value = lat.toFixed(5) + ', ' + lng.toFixed(5);
                }
            });
        }
        calculateAndDisplayRoute();
    });

    // --- Google Places Autocomplete Search Setup ---
    function setupGoogleAutocomplete(inputId, markerType) {
        const input = document.getElementById(inputId);
        const options = {
            componentRestrictions: { country: 'id' },
            fields: ['geometry', 'formatted_address']
        };
        const autocomplete = new google.maps.places.Autocomplete(input, options);
        let placeSelected = false;

        // Track when a place is selected from Google's dropdown list
        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) {
                return;
            }

            placeSelected = true;
            const lat = place.geometry.location.lat();
            const lng = place.geometry.location.lng();
            const latlng = place.geometry.location;

            let cleanAddress = place.formatted_address;
            cleanAddress = cleanAddress.replace(', Indonesia', '');
            input.value = cleanAddress;

            updateMarkerAndRoute(latlng, lat, lng, markerType);
        });

        // Reset when user types new characters
        input.addEventListener('input', function() {
            placeSelected = false;
        });

        // Prevent form submit on Enter, trigger geocoder by blurring the field instead
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                input.blur();
            }
        });

        // Blur event: if they typed/pasted something but didn't select, geocode it!
        input.addEventListener('blur', function() {
            setTimeout(() => { // Small delay to let clicks on dropdown register first
                if (placeSelected) return;
                
                const address = input.value.trim();
                if (address.length < 3) return;

                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ address: address, componentRestrictions: { country: 'id' } }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        const latlng = results[0].geometry.location;
                        const lat = latlng.lat();
                        const lng = latlng.lng();
                        
                        let cleanAddr = results[0].formatted_address;
                        cleanAddr = cleanAddr.replace(', Indonesia', '');
                        input.value = cleanAddr;
                        
                        placeSelected = true;
                        updateMarkerAndRoute(latlng, lat, lng, markerType);
                    }
                });
            }, 300);
        });
    }

    // Helper to update marker, center map and redraw routing path
    function updateMarkerAndRoute(latlng, lat, lng, markerType) {
        if (markerType === 'origin') {
            if (originMarker) {
                originMarker.setPosition(latlng);
            } else {
                originMarker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    title: 'Asal',
                    draggable: true,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 8,
                        fillColor: '#10b981',
                        fillOpacity: 1,
                        strokeColor: '#ffffff',
                        strokeWeight: 2
                    }
                });
                originMarker.addListener('dragend', function() {
                    const pos = originMarker.getPosition();
                    setOriginCoords(pos.lat(), pos.lng());
                    calculateAndDisplayRoute();
                });
            }
            setOriginCoords(lat, lng);
            activeMode = 'destination';
            updateButtonUI();
        } else {
            if (destMarker) {
                destMarker.setPosition(latlng);
            } else {
                destMarker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    title: 'Tujuan',
                    draggable: true,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 8,
                        fillColor: '#dc2626',
                        fillOpacity: 1,
                        strokeColor: '#ffffff',
                        strokeWeight: 2
                    }
                });
                destMarker.addListener('dragend', function() {
                    const pos = destMarker.getPosition();
                    setDestCoords(pos.lat(), pos.lng());
                    calculateAndDisplayRoute();
                });
            }
            setDestCoords(lat, lng);
        }

        map.setCenter(latlng);
        map.setZoom(12);
        calculateAndDisplayRoute();
    }

    setupGoogleAutocomplete('origin', 'origin');
    setupGoogleAutocomplete('destination', 'destination');

    // --- Admin Browser GPS Detection with Google Geocoder ---
    const btnDetectGPS = document.getElementById('btn-detect-gps');
    if (btnDetectGPS) {
        btnDetectGPS.addEventListener('click', function() {
            if ("geolocation" in navigator) {
                btnDetectGPS.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mendeteksi GPS...';
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const latlng = new google.maps.LatLng(lat, lng);
                    
                    if (originMarker) {
                        originMarker.setPosition(latlng);
                    } else {
                        originMarker = new google.maps.Marker({
                            position: latlng,
                            map: map,
                            title: 'Asal',
                            draggable: true,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: 8,
                                fillColor: '#10b981',
                                fillOpacity: 1,
                                strokeColor: '#ffffff',
                                strokeWeight: 2
                            }
                        });
                        originMarker.addListener('dragend', function() {
                            const pos = originMarker.getPosition();
                            setOriginCoords(pos.lat(), pos.lng());
                            calculateAndDisplayRoute();
                        });
                    }
                    setOriginCoords(lat, lng);
                    map.setCenter(latlng);
                    map.setZoom(14);
                    
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ location: latlng }, function(results, status) {
                        if (status === 'OK' && results[0]) {
                            let addr = results[0].formatted_address;
                            addr = addr.replace(', Indonesia', '');
                            document.getElementById('origin').value = addr;
                        } else {
                            document.getElementById('origin').value = lat.toFixed(5) + ', ' + lng.toFixed(5);
                        }
                        btnDetectGPS.innerHTML = '<i class="fas fa-location-crosshairs me-1"></i> Deteksi GPS Admin (Asal)';
                        activeMode = 'destination';
                        updateButtonUI();
                        calculateAndDisplayRoute();
                    });
                }, function(error) {
                    console.error("GPS Detection Error:", error);
                    alert("Gagal mendeteksi lokasi GPS. Pastikan izin lokasi diaktifkan di browser Anda.");
                    btnDetectGPS.innerHTML = '<i class="fas fa-location-crosshairs me-1"></i> Deteksi GPS Admin (Asal)';
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000
                });
            } else {
                alert("Browser Anda tidak mendukung deteksi lokasi.");
            }
        });
    }
});
</script>
