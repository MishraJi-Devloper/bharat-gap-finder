<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bharat Manufacturing Gap Finder</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Chart.js & Leaflet JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        body { 
            background-color: #f8fafc; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
        }
        .navbar { 
            background: #0f172a; 
            border-bottom: 3px solid #059669; 
        }
        .card { 
            border-radius: 8px; 
            border: 1px solid #e2e8f0; 
        }
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
    <nav class="navbar navbar-dark px-4 py-3">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <span class="text-success">BHARAT</span> MANUFACTURING GAP FINDER
        </a>
        <span class="text-secondary small">District Economic Intelligence Platform</span>
    </nav>

    <div class="container-fluid py-4 px-4">
        @yield('content')
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>