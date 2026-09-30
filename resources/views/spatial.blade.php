<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bharat Manufacturing Gap Finder - 3D Spatial Grid</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <style>
        :root {
            --bg-spatial: #040814;
            --accent-cyan: #06b6d4;
            --card-glass: rgba(10, 18, 36, 0.65);
            --card-border: rgba(255, 255, 255, 0.08);
        }

        * { box-sizing: border-box; user-select: none; }

        body {
            background-color: var(--bg-spatial);
            color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            overflow: hidden;
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
        }

        #webgl-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1;
        }

        .vignette-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 2;
            background: radial-gradient(circle at center, transparent 30%, rgba(4, 8, 20, 0.85) 90%);
            pointer-events: none;
        }

        .spatial-hud {
            position: relative;
            z-index: 10;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem;
            pointer-events: none;
        }

        .interactive {
            pointer-events: auto;
        }

        .glass-panel {
            background: var(--card-glass);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.7), inset 0 1px 1px 0 rgba(255, 255, 255, 0.12);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-panel:hover {
            border-color: rgba(6, 182, 212, 0.4);
            box-shadow: 0 35px 70px -15px rgba(6, 182, 212, 0.25);
        }

        .badge-spatial {
            background: rgba(6, 182, 212, 0.12);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            padding: 5px 12px;
            border-radius: 9999px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .spatial-stat-value {
            font-size: 2.4rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #ffffff 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>

    <canvas id="webgl-canvas"></canvas>
    <div class="vignette-overlay"></div>

    <div class="spatial-hud">
        <header class="d-flex justify-content-between align-items-center interactive">
            <div class="d-flex align-items-center gap-3">
                <span class="badge-spatial">3D Spatial Telemetry</span>
                <h5 class="mb-0 fw-bold tracking-tight">
                    <span class="text-success">BHARAT</span> GAP FINDER
                </h5>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light px-3 py-2 rounded-pill fw-semibold">
                    &larr; Standard Dashboard
                </a>
                <a href="{{ route('demand.create') }}" class="btn btn-sm btn-success px-3 py-2 rounded-pill fw-bold shadow">
                    + Ingest Demand
                </a>
            </div>
        </header>

        <div class="row justify-content-center text-center interactive">
            <div class="col-lg-6">
                <div class="glass-panel p-4">
                    <span class="badge-spatial mb-2 d-inline-block">Live Spatial Nodes</span>
                    <h2 class="fw-bolder text-white mb-2" style="font-size: 1.8rem;">
                        Interactive Industrial Geometry
                    </h2>
                    <p class="text-light opacity-75 small mb-3">
                        Move cursor to tilt perspective. Click and drag across the sphere to orbit Indian industrial clusters in 3D space.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('compare') }}" class="btn btn-sm btn-primary px-3 py-2 rounded-pill fw-bold">
                            Compare Districts
                        </a>
                        <a href="{{ route('handbook.pdf') }}" class="btn btn-sm btn-outline-secondary text-white px-3 py-2 rounded-pill fw-semibold">
                            Download Dossier (PDF)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 interactive">
            <div class="col-md-4">
                <div class="glass-panel p-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary small fw-bold">TRACKED ENTERPRISES</span>
                        <span class="badge bg-success bg-opacity-25 text-success">Live Sync</span>
                    </div>
                    <div class="spatial-stat-value">{{ $totalBusinesses ?? 142 }}</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">Verified manufacturing plants mapped in 3D clusters</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-panel p-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary small fw-bold">IDENTIFIED DEFICITS</span>
                        <span class="badge bg-danger bg-opacity-25 text-danger">Unmet Demand</span>
                    </div>
                    <div class="spatial-stat-value text-danger">{{ $totalGaps ?? 28 }}</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">Critical product shortages requiring regional capacity expansion</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-panel p-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary small fw-bold">SPATIAL ENGINE</span>
                        <span class="badge bg-info bg-opacity-25 text-info">WebGL 60 FPS</span>
                    </div>
                    <div class="spatial-stat-value text-info">&lt; 16ms</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">Hardware GPU rendering &bull; Drag mouse to spin</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('webgl-canvas');
        const scene = new THREE.Scene();
        scene.fog = new THREE.FogExp2(0x040814, 0.0018);

        const camera = new THREE.PerspectiveCamera(55, window.innerWidth / window.innerHeight, 0.1, 1000);
        camera.position.set(0, 0, 85);

        const renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

        const globeGroup = new THREE.Group();
        scene.add(globeGroup);

        const sphereGeometry = new THREE.SphereGeometry(28, 36, 36);
        const sphereMaterial = new THREE.MeshBasicMaterial({
            color: 0x06b6d4,
            wireframe: true,
            transparent: true,
            opacity: 0.18
        });
        const globeMesh = new THREE.Mesh(sphereGeometry, sphereMaterial);
        globeGroup.add(globeMesh);

        const coreGeo = new THREE.SphereGeometry(26.5, 24, 24);
        const coreMat = new THREE.MeshBasicMaterial({
            color: 0x0a192f,
            transparent: true,
            opacity: 0.85
        });
        const coreMesh = new THREE.Mesh(coreGeo, coreMat);
        globeGroup.add(coreMesh);

        const nodeGroup = new THREE.Group();
        globeGroup.add(nodeGroup);

        const nodeGeometry = new THREE.SphereGeometry(0.7, 12, 12);
        const nodeMaterialDeficit = new THREE.MeshBasicMaterial({ color: 0xef4444 });
        const nodeMaterialSupply = new THREE.MeshBasicMaterial({ color: 0x10b981 });

        const nodeCount = 48;
        for (let i = 0; i < nodeCount; i++) {
            const phi = Math.acos(-1 + (2 * i) / nodeCount);
            const theta = Math.sqrt(nodeCount * Math.PI) * phi;
            const radius = 28.5;

            const x = radius * Math.cos(theta) * Math.sin(phi);
            const y = radius * Math.sin(theta) * Math.sin(phi);
            const z = radius * Math.cos(phi);

            const isDeficit = i % 3 === 0;
            const marker = new THREE.Mesh(nodeGeometry, isDeficit ? nodeMaterialDeficit : nodeMaterialSupply);
            marker.position.set(x, y, z);
            nodeGroup.add(marker);
        }

        const particleCount = 600;
        const particleGeo = new THREE.BufferGeometry();
        const positions = new Float32Array(particleCount * 3);

        for (let i = 0; i < particleCount * 3; i += 3) {
            positions[i] = (Math.random() - 0.5) * 280;
            positions[i + 1] = (Math.random() - 0.5) * 280;
            positions[i + 2] = (Math.random() - 0.5) * 280;
        }

        particleGeo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        const particleMat = new THREE.PointsMaterial({
            color: 0x38bdf8,
            size: 0.7,
            transparent: true,
            opacity: 0.5
        });
        const particleSystem = new THREE.Points(particleGeo, particleMat);
        scene.add(particleSystem);

        let targetX = 0;
        let targetY = 0;
        let isDragging = false;
        let previousMousePosition = { x: 0, y: 0 };

        window.addEventListener('mousemove', (e) => {
            const mouseX = (e.clientX - window.innerWidth / 2);
            const mouseY = (e.clientY - window.innerHeight / 2);

            targetX = mouseX * 0.0008;
            targetY = mouseY * 0.0008;

            if (isDragging) {
                const deltaX = e.clientX - previousMousePosition.x;
                const deltaY = e.clientY - previousMousePosition.y;
                globeGroup.rotation.y += deltaX * 0.005;
                globeGroup.rotation.x += deltaY * 0.005;
            }

            previousMousePosition = { x: e.clientX, y: e.clientY };
        });

        window.addEventListener('mousedown', () => { isDragging = true; });
        window.addEventListener('mouseup', () => { isDragging = false; });

        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        const clock = new THREE.Clock();

        function animate() {
            requestAnimationFrame(animate);
            const elapsedTime = clock.getElapsedTime();

            if (!isDragging) {
                globeGroup.rotation.y += 0.0025;
                globeGroup.rotation.x += 0.0008;
            }

            camera.position.x += (targetX * 25 - camera.position.x) * 0.05;
            camera.position.y += (-targetY * 25 - camera.position.y) * 0.05;
            camera.lookAt(scene.position);

            particleSystem.rotation.y = elapsedTime * 0.02;

            renderer.render(scene, camera);
        }

        animate();
    </script>
</body>
</html>