<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ServiHome — @yield('title', 'Dashboard')</title>

    <!-- FullCalendar CDN -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #F5F5F5;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background: #1565C0;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 0;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
        }

        .sidebar-logo {
            padding: 24px 20px;
            font-size: 20px;
            font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            letter-spacing: 0.5px;
        }

        .sidebar-logo span {
            color: #FF6D00;
        }

        .sidebar-nav {
            padding: 16px 0;
            flex: 1;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(255,255,255,0.12);
            color: white;
        }

        .sidebar-nav a .icon {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.15);
            font-size: 13px;
            color: rgba(255,255,255,0.6);
        }

        /* Main content */
        .main {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: white;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #E0E0E0;
        }

        .topbar h1 {
            font-size: 18px;
            font-weight: 600;
            color: #1A1A1A;
        }

        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #555;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #1565C0;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 500;
        }

        .content {
            padding: 24px;
            flex: 1;
        }

        /* Stats cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #E0E0E0;
        }

        .stat-card .label {
            font-size: 13px;
            color: #757575;
            margin-bottom: 8px;
        }

        .stat-card .value {
            font-size: 28px;
            font-weight: 600;
            color: #1A1A1A;
        }

        .stat-card .value.blue   { color: #1565C0; }
        .stat-card .value.orange { color: #FF6D00; }
        .stat-card .value.green  { color: #2E7D32; }
        .stat-card .value.gold   { color: #F57F17; }

        /* Calendar */
        .calendar-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #E0E0E0;
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #1A1A1A;
            margin-bottom: 16px;
        }

        #calendar {
            min-height: 500px;
        }

        /* Table */
        .table-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #E0E0E0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th {
            text-align: left;
            padding: 10px 12px;
            background: #F5F5F5;
            color: #757575;
            font-weight: 500;
            border-bottom: 1px solid #E0E0E0;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #F0F0F0;
            color: #1A1A1A;
        }

        tr:last-child td { border-bottom: none; }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-pending    { background: #FFF3E0; color: #E65100; }
        .badge-confirmed  { background: #E3F2FD; color: #1565C0; }
        .badge-completed  { background: #E8F5E9; color: #2E7D32; }
        .badge-cancelled  { background: #FFEBEE; color: #C62828; }
        .badge-in_progress{ background: #E8F5E9; color: #1B5E20; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">Servi<span>Home</span></div>
        <nav class="sidebar-nav">
            <a href="/dashboard/provider" class="active">
                <span class="icon">📅</span> Dashboard
            </a>
            <a href="#">
                <span class="icon">📋</span> Réservations
            </a>
            <a href="#">
                <span class="icon">⏰</span> Disponibilités
            </a>
            <a href="#">
                <span class="icon">💰</span> Revenus
            </a>
            <a href="#">
                <span class="icon">👤</span> Mon Profil
            </a>
        </nav>
        <div class="sidebar-footer">
            Binôme 06 · J1
        </div>
    </aside>

    <!-- Main -->
    <main class="main">
        <div class="topbar">
            <h1>@yield('page-title', 'Dashboard')</h1>
            <div class="user-info">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <span>{{ Auth::user()->name }}</span>
            </div>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </main>
    @stack('scripts')
</body>
</html>