@extends('admin.layouts.admin')
@section('title', 'Utilisateurs')
@section('page-title', 'Utilisateurs')

@push('styles')
<style>
    .stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
    .stat-card { background:white; border-radius:12px; padding:18px 20px; border:1px solid #E5E7EB; display:flex; align-items:center; gap:14px; }
    .stat-icon { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
    .stat-icon.blue{background:#EFF6FF;color:#1565C0;} .stat-icon.green{background:#F0FDF4;color:#16A34A;} .stat-icon.red{background:#FEF2F2;color:#DC2626;}
    .stat-value{font-size:22px;font-weight:700;color:#111827;} .stat-label{font-size:12px;color:#6B7280;}
    .toolbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
    .search-box{display:flex;align-items:center;gap:8px;background:white;border:1px solid #E5E7EB;border-radius:8px;padding:8px 14px;width:280px;}
    .search-box input{border:none;outline:none;font-size:14px;color:#374151;width:100%;background:transparent;}
    .search-box i{color:#9CA3AF;}
    .table-card{background:white;border-radius:12px;border:1px solid #E5E7EB;overflow:hidden;}
    table{width:100%;border-collapse:collapse;font-size:13.5px;}
    th{padding:10px 16px;text-align:left;background:#F9FAFB;color:#6B7280;font-weight:500;border-bottom:1px solid #F3F4F6;font-size:12px;text-transform:uppercase;letter-spacing:.05em;}
    td{padding:12px 16px;border-bottom:1px solid #F9FAFB;color:#374151;vertical-align:middle;}
    tr:last-child td{border-bottom:none;} tr:hover td{background:#FAFAFA;}
    .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:500;}
    .badge-actif{background:#D1FAE5;color:#065F46;} .badge-inactif{background:#FEE2E2;color:#991B1B;}
    .role-badge{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:6px;font-size:12px;background:#F3F4F6;color:#374151;}
    .action-btn{background:none;border:none;cursor:pointer;padding:5px 7px;border-radius:6px;color:#6B7280;font-size:14px;transition:all .15s;}
    .action-btn:hover{background:#F3F4F6;color:#111827;}
    .avatar-text{width:32px;height:32px;border-radius:50%;background:#1565C0;color:white;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;margin-right:8px;vertical-align:middle;}
    .pagination-wrap{padding:16px 20px;display:flex;justify-content:flex-end;}
</style>
@endpush

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
        <div><div class="stat-value">{{ $users->total() }}</div><div class="stat-label">Utilisateurs totaux</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-user-check"></i></div>
        <div><div class="stat-value">{{ $users->total() }}</div><div class="stat-label">Actifs</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-solid fa-user-xmark"></i></div>
        <div><div class="stat-value">0</div><div class="stat-label">Inactifs</div></div>
    </div>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('admin.users') }}">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un utilisateur...">
        </div>
    </form>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Nom</th><th>Rôle</th><th>Email</th><th>Inscription</th><th>Statut</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <span class="avatar-text">{{ strtoupper(substr($user->name,0,1)) }}</span>
                    {{ $user->name }}
                </td>
                <td><span class="role-badge">{{ ucfirst($user->getRoleNames()->first() ?? 'client') }}</span></td>
                <td style="color:#6B7280;">{{ $user->email }}</td>
                <td style="color:#6B7280;">{{ $user->created_at->format('d/m/Y') }}</td>
                <td><span class="badge badge-actif"><i class="fa-solid fa-circle" style="font-size:7px;margin-right:4px;"></i>Actif</span></td>
                <td>
                    <button class="action-btn" title="Voir"><i class="fa-regular fa-eye"></i></button>
                    <button class="action-btn" title="Modifier"><i class="fa-regular fa-pen-to-square"></i></button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $users->links() }}</div>
</div>
@endsection