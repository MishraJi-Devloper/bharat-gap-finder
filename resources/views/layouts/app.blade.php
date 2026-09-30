<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bharat Manufacturing Gap Finder</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Chart.js & Leaflet JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    
    <style>
        body { 
            background: #f4f7fb;
            color: #172033;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            min-height: 100vh;
        }
        #ambient-3d-canvas {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            pointer-events: none;
            opacity: 0.55;
        }
        .navbar { 
            position: relative;
            z-index: 2;
            background: #0f172a;
            border-bottom: 3px solid #059669;
        }
        .card { 
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #ffffff !important;
            color: #172033;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.1);
        }
        .card-header, .table-light, .bg-white, .bg-light {
            background: #ffffff !important;
            color: #172033 !important;
        }
        .text-dark { color: #172033 !important; }
        .text-muted, .text-secondary { color: #64748b !important; }
        .form-control, .form-select {
            color: #172033;
            background-color: #ffffff;
            border-color: #cbd5e1;
        }
        .form-control::placeholder { color: #64748b; }
        .form-control:focus, .form-select:focus {
            color: #172033;
            background-color: #ffffff;
            border-color: #059669;
            box-shadow: 0 0 0 0.2rem rgba(5, 150, 105, 0.14);
        }
        .table { --bs-table-color: #334155; --bs-table-bg: transparent; --bs-table-hover-color: #172033; }
        .table > :not(caption) > * > * { border-bottom-color: #e2e8f0; }
        .page-shell { max-width: 1680px; margin: 0 auto; }
        .badge-score { 
            font-size: 0.95rem; 
            font-weight: 700; 
            background-color: #ecfdf5; 
            color: #047857; 
            border: 1px solid #a7f3d0; 
        }
        /* Leaflet stacking fix */
        .leaflet-pane { 
            z-index: 1 !important; 
        }
        .leaflet-top, .leaflet-bottom { 
            z-index: 2 !important; 
        }
    </style>
</head>
<body>
    <canvas id="ambient-3d-canvas" aria-hidden="true"></canvas>
    <nav class="navbar navbar-dark px-3 px-lg-4 py-3">
        <div class="d-flex align-items-center gap-3">
            <a class="navbar-brand fw-bold mb-0" href="{{ route('dashboard') }}">
                <span class="text-success">BHARAT</span> MANUFACTURING GAP FINDER
            </a>
            <span class="text-secondary small d-none d-md-inline border-start ps-3 border-secondary">District Economic Intelligence Platform</span>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end mt-3 mt-lg-0" id="mainNavigation">
            <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2">
                <a href="{{ route('businesses') }}" class="btn btn-sm btn-outline-light fw-semibold">MSME Directory</a>
                @auth
                    <a href="{{ route('businesses.create') }}" class="btn btn-sm btn-outline-info fw-semibold">Register MSME</a>
                    <a href="{{ route('project.report') }}" class="btn btn-sm btn-outline-light fw-semibold">Project report</a>
                    <a href="{{ route('project.technical-report') }}" class="btn btn-sm btn-outline-light fw-semibold">Technical report</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.businesses') }}" class="btn btn-sm btn-warning fw-semibold">Review MSMEs</a>
                        <a href="{{ route('admin.invitations') }}" class="btn btn-sm btn-outline-warning fw-semibold">Invite users</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="d-flex">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light fw-semibold">Sign out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light fw-semibold">Sign in</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-success fw-bold">Create account</a>
                @endauth
                <a href="{{ route('compare') }}" class="btn btn-sm btn-outline-light fw-semibold">Compare Districts</a>
                <a href="{{ route('supply.create') }}" class="btn btn-sm btn-outline-info fw-semibold">+ Log Supply</a>
                <a href="{{ route('demand.create') }}" class="btn btn-sm btn-success fw-bold">+ Ingest Demand</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid page-shell py-4 px-3 px-lg-4">
        @yield('content')
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            if (!window.THREE) return;

            const canvas = document.getElementById('ambient-3d-canvas');
            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(60, innerWidth / innerHeight, 0.1, 300);
            camera.position.z = 48;

            const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
            renderer.setPixelRatio(Math.min(devicePixelRatio, 1.5));
            renderer.setSize(innerWidth, innerHeight);

            const positions = new Float32Array(260 * 3);
            for (let index = 0; index < positions.length; index += 3) {
                positions[index] = (Math.random() - 0.5) * 130;
                positions[index + 1] = (Math.random() - 0.5) * 85;
                positions[index + 2] = (Math.random() - 0.5) * 90;
            }

            const particleGeometry = new THREE.BufferGeometry();
            particleGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            const particles = new THREE.Points(
                particleGeometry,
                new THREE.PointsMaterial({ color: 0x059669, size: 0.38, transparent: true, opacity: 0.38 })
            );
            scene.add(particles);

            const ring = new THREE.Mesh(
                new THREE.TorusGeometry(18, 0.035, 8, 120),
                new THREE.MeshBasicMaterial({ color: 0x2563eb, transparent: true, opacity: 0.12 })
            );
            ring.rotation.x = 1.1;
            scene.add(ring);

            let pointerX = 0;
            let pointerY = 0;
            addEventListener('pointermove', function (event) {
                pointerX = (event.clientX / innerWidth - 0.5) * 2;
                pointerY = (event.clientY / innerHeight - 0.5) * 2;
            });
            addEventListener('resize', function () {
                camera.aspect = innerWidth / innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(innerWidth, innerHeight);
            });

            function animate() {
                requestAnimationFrame(animate);
                particles.rotation.y += 0.00025;
                particles.rotation.x += (pointerY * 0.04 - particles.rotation.x) * 0.02;
                ring.rotation.z += 0.001;
                ring.rotation.y += (pointerX * 0.08 - ring.rotation.y) * 0.02;
                renderer.render(scene, camera);
            }
            animate();
        })();
    </script>
</body>
</html>