<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ServiHome Admin — @yield('title', 'Dashboard')</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #F0F2F5;
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 240px;
            background: #0F172A;
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 100;
        }

        .sidebar-header {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #1565C0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
            font-weight: 600;
        }

        .sidebar-name {
            font-size: 14px;
            font-weight: 600;
            color: white;
        }

        .sidebar-role {
            font-size: 11px;
            color: rgba(255,255,255,0.5);
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 0;
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            border-radius: 0;
            position: relative;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.06);
            color: white;
        }

        .nav-item.active {
            background: rgba(21,101,192,0.3);
            color: white;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #1565C0;
            border-radius: 0 2px 2px 0;
        }

        .nav-item i {
            width: 18px;
            text-align: center;
            font-size: 15px;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .nav-logout {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            background: none;
            border: none;
            width: 100%;
        }

        .nav-logout:hover { color: #ff6b6b; }

        /* ── Main ── */
        .main {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Topbar ── */
        .topbar {
            background: white;
            padding: 0 28px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #E5E7EB;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
        }

        .admin-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #1565C0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 600;
        }

        /* ── Content ── */
        .content {
            padding: 28px;
            flex: 1;
        }

        /* ── Alert ── */
        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-avatar">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <div>
            <div class="sidebar-name">Admin</div>
            <div class="sidebar-role">Administrateur</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high"></i>
            Tableau de bord
        </a>
        <a href="{{ route('admin.users') }}"
           class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i>
            Utilisateurs
        </a>
        <a href="{{ route('admin.providers') }}"
           class="nav-item {{ request()->routeIs('admin.providers') ? 'active' : '' }}">
            <i class="fa-solid fa-user-tie"></i>
            Prestataires
        </a>
        <a href="#" class="nav-item">
            <i class="fa-solid fa-screwdriver-wrench"></i>
            Services
        </a>
        <a href="#" class="nav-item">
            <i class="fa-solid fa-tags"></i>
            Catégories
        </a>
        <a href="{{ route('admin.bookings') }}"
           class="nav-item {{ request()->routeIs('admin.bookings') ? 'active' : '' }}">
            <i class="fa-solid fa-calendar-check"></i>
            Réservations
        </a>
        <a href="#" class="nav-item">
            <i class="fa-solid fa-credit-card"></i>
            Paiements
        </a>
        <a href="#" class="nav-item">
            <i class="fa-solid fa-star"></i>
            Avis & Notes
        </a>
        <a href="#" class="nav-item">
            <i class="fa-solid fa-gear"></i>
            Paramètres
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="/" class="nav-logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            Déconnexion
        </a>
    </div>
</aside>

<!-- Main -->
<main class="main">
    <div class="topbar">
        <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        <div class="topbar-right">
            <div class="admin-badge">
                <div class="admin-avatar">A</div>
                Admin
                <i class="fa-solid fa-chevron-down" style="font-size:11px;color:#9CA3AF;"></i>
            </div>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
</main>

@stack('scripts')
</body>
</html>