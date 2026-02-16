<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'BNGRC') ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #1a5276, #2e86c1) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
        }
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
        .stat-card .icon-box {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .stat-card .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #2c3e50;
        }
        .stat-card .stat-label {
            font-size: 0.85rem;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .table-card .card-header {
            background: white;
            border-bottom: 2px solid #f0f2f5;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
        }
        .progress {
            height: 8px;
            border-radius: 4px;
        }
        .badge-ville {
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 20px;
        }
        .footer {
            background: linear-gradient(135deg, #1a5276, #2e86c1);
            color: white;
            padding: 30px 0 15px;
            margin-top: 40px;
        }
        .footer h6 {
            font-size: 0.85rem;
            font-weight: 600;
        }
        .btn-detail {
            border-radius: 20px;
            font-size: 0.8rem;
            padding: 4px 14px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-shield-check me-2"></i>BNGRC
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= ($_SERVER['REQUEST_URI'] === '/' ? 'active' : '') ?>" href="/"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (str_starts_with($_SERVER['REQUEST_URI'], '/besoins') ? 'active' : '') ?>" href="/besoins"><i class="bi bi-exclamation-triangle me-1"></i>Besoins</a>
                    </li>
                </ul>
                <span class="navbar-text text-white-50">
                    <i class="bi bi-geo-alt me-1"></i>Côte Est - Madagascar
                </span>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 mt-4">
        <main>
