@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="mb-4">
            <a href="{{ route('businesses') }}" class="btn btn-sm btn-outline-secondary mb-3">&larr; Back to directory</a>
            <h1 class="h3 fw-bold text-dark mb-1">Register a local MSME</h1>
            <p class="text-muted mb-0">Add a business profile so buyers, partners, and district teams can discover your local capabilities.</p>
        </div>

        <div class="card p-4 p-lg-5">
            <form action="{{ route('businesses.store') }}" method="POST">
                @csrf

                <h2 class="h6 fw-bold text-uppercase text-success mb-3">Business identity</h2>
                <div class="row g-3 mb-4">
                    <div class="col-md-7">
                        <label for="name" class="form-label fw-semibold">Enterprise name</label>
                        <input id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required maxlength="255" placeholder="e.g. Howrah Precision Works">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-5">
                        <label for="industry_category" class="form-label fw-semibold">Industry / capability</label>
                        <input id="industry_category" name="industry_category" value="{{ old('industry_category') }}" class="form-control @error('industry_category') is-invalid @enderror" required maxlength="255" placeholder="e.g. Packaging">
                        @error('industry_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="state" class="form-label fw-semibold">State / Union Territory <span class="text-muted fw-normal">(auto-filled)</span></label>
                        <input id="state" name="state" value="{{ old('state') }}" class="form-control @error('state') is-invalid @enderror" required maxlength="255" autocomplete="address-level1" placeholder="Select a map location">
                        @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="district_name" class="form-label fw-semibold">District <span class="text-muted fw-normal">(auto-filled)</span></label>
                        <input id="district_name" name="district_name" value="{{ old('district_name') }}" class="form-control @error('district_name') is-invalid @enderror" required maxlength="255" autocomplete="address-level2" placeholder="Select a map location">
                        @error('district_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="locality" class="form-label fw-semibold">Local place / locality <span class="text-muted fw-normal">(auto-filled)</span></label>
                        <input id="locality" name="locality" value="{{ old('locality') }}" class="form-control @error('locality') is-invalid @enderror" required maxlength="255" autocomplete="address-line2" placeholder="Select a map location">
                        @error('locality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="registration_number" class="form-label fw-semibold">Udyam / MSME registration number <span class="text-muted fw-normal">(optional)</span></label>
                        <input id="registration_number" name="registration_number" value="{{ old('registration_number') }}" class="form-control @error('registration_number') is-invalid @enderror" maxlength="100" placeholder="e.g. UDYAM-WB-00-0000000">
                        @error('registration_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h2 class="h6 fw-bold text-uppercase text-success mb-3">Contact for discovery</h2>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="contact_name" class="form-label fw-semibold">Contact person</label>
                        <input id="contact_name" name="contact_name" value="{{ old('contact_name') }}" class="form-control @error('contact_name') is-invalid @enderror" required maxlength="255">
                        @error('contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="phone" class="form-label fw-semibold">Phone number</label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" required maxlength="20" minlength="8" inputmode="tel" autocomplete="tel" pattern="\+?[0-9][0-9\s\-()]{7,19}" title="Enter a valid phone number, for example +91 9876543210">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label fw-semibold">Email <span class="text-muted fw-normal">(optional)</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" maxlength="255">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">What does the enterprise make or provide? <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea id="description" name="description" rows="4" maxlength="1000" class="form-control @error('description') is-invalid @enderror" placeholder="Briefly describe products, services, machinery, or capacity.">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h2 class="h6 fw-bold text-uppercase text-success mb-3">Precise enterprise location <span class="text-muted fw-normal">(optional)</span></h2>
                <div class="border rounded p-3 p-lg-4 mb-4 bg-light">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                        <div>
                            <p class="fw-semibold text-dark mb-1">Place your MSME on the map</p>
                            <p class="small text-muted mb-0">Use your device location or click the map to set an accurate operating point.</p>
                        </div>
                        <button type="button" id="use-current-location" class="btn btn-sm btn-outline-success fw-semibold">Use my current location</button>
                    </div>
                    <div id="business-location-map" class="rounded border mb-3" style="height: 280px;"></div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="latitude" class="form-label small fw-semibold">Latitude</label>
                            <input id="latitude" name="latitude" value="{{ old('latitude') }}" class="form-control @error('latitude') is-invalid @enderror" inputmode="decimal" placeholder="e.g. 22.5958">
                            @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="longitude" class="form-label small fw-semibold">Longitude</label>
                            <input id="longitude" name="longitude" value="{{ old('longitude') }}" class="form-control @error('longitude') is-invalid @enderror" inputmode="decimal" placeholder="e.g. 88.2636">
                            @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <p id="location-status" class="small text-muted mt-3 mb-0" role="status">No precise location selected yet.</p>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                    <a href="{{ route('businesses') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success fw-bold">Submit MSME profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const locationStatus = document.getElementById('location-status');
        const map = L.map('business-location-map').setView([
            Number(latitudeInput.value) || 22.9734,
            Number(longitudeInput.value) || 78.6569
        ], latitudeInput.value && longitudeInput.value ? 15 : 5);
        let marker = null;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        async function fillAddressFromLocation(latitude, longitude) {
            locationStatus.textContent = 'Location selected. Finding the state, district, and locality...';
            locationStatus.className = 'small text-primary mt-3 mb-0';

            try {
                const response = await fetch(
                    'https://nominatim.openstreetmap.org/reverse?format=jsonv2&addressdetails=1&lat=' + latitude + '&lon=' + longitude,
                    { headers: { 'Accept-Language': 'en' } }
                );

                if (!response.ok) throw new Error('Reverse geocoding failed');
                const result = await response.json();
                const address = result.address || {};
                const district = address.state_district || address.district || address.county || '';
                const locality = address.suburb || address.neighbourhood || address.village || address.town || address.city || '';

                if (address.state) stateInput.value = address.state;
                if (district) districtInput.value = district.replace(/ district$/i, '');
                if (locality) localityInput.value = locality;

                locationStatus.textContent = 'Address detected from the pin. You can edit any field if needed.';
                locationStatus.className = 'small text-success mt-3 mb-0';
            } catch (error) {
                locationStatus.textContent = 'Pin saved, but the address could not be detected. Enter the fields manually.';
                locationStatus.className = 'small text-warning mt-3 mb-0';
            }
        }

        const stateInput = document.getElementById('state');
        const districtInput = document.getElementById('district_name');
        const localityInput = document.getElementById('locality');

        function setLocation(latitude, longitude, message, detectAddress = true) {
            latitudeInput.value = latitude.toFixed(7);
            longitudeInput.value = longitude.toFixed(7);
            const point = [latitude, longitude];

            if (!marker) {
                marker = L.marker(point, { draggable: true }).addTo(map);
                marker.on('dragend', function (event) {
                    const position = event.target.getLatLng();
                    setLocation(position.lat, position.lng, 'Location updated by moving the pin.');
                });
            } else {
                marker.setLatLng(point);
            }

            map.setView(point, Math.max(map.getZoom(), 15));
            locationStatus.textContent = message;
            locationStatus.className = 'small text-success mt-3 mb-0';

            if (detectAddress) fillAddressFromLocation(latitude, longitude);
        }

        if (latitudeInput.value && longitudeInput.value) {
            setLocation(Number(latitudeInput.value), Number(longitudeInput.value), 'Saved location loaded.');
        }

        map.on('click', function (event) {
            setLocation(event.latlng.lat, event.latlng.lng, 'Location selected from the map.');
        });

        document.getElementById('use-current-location').addEventListener('click', function () {
            if (!navigator.geolocation) {
                locationStatus.textContent = 'Location access is not supported by this browser.';
                locationStatus.className = 'small text-danger mt-3 mb-0';
                return;
            }

            locationStatus.textContent = 'Requesting your location...';
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    setLocation(position.coords.latitude, position.coords.longitude, 'Current device location selected.');
                },
                function () {
                    locationStatus.textContent = 'Location access was unavailable. You can select a point on the map instead.';
                    locationStatus.className = 'small text-danger mt-3 mb-0';
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 300000 }
            );
        });
    });
</script>
@endsection
