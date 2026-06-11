@extends('admin.layouts.admin')
@section('title', 'Catégories')
@section('page-title', 'Catégories')

@push('styles')
<style>
    .stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;}
    .stat-card{background:white;border-radius:12px;padding:18px 20px;border:1px solid #E5E7EB;display:flex;align-items:center;gap:14px;}
    .stat-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
    .stat-icon.blue{background:#EFF6FF;color:#1565C0;} .stat-icon.green{background:#F0FDF4;color:#16A34A;} .stat-icon.red{background:#FEF2F2;color:#DC2626;}
    .stat-value{font-size:22px;font-weight:700;color:#111827;} .stat-label{font-size:12px;color:#6B7280;}
    .toolbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
    .search-box{display:flex;align-items:center;gap:8px;background:white;border:1px solid #E5E7EB;border-radius:8px;padding:8px 14px;width:280px;}
    .search-box input{border:none;outline:none;font-size:14px;color:#374151;width:100%;background:transparent;}
    .search-box i{color:#9CA3AF;}
    .btn-add{background:#1565C0;color:white;border:none;border-radius:8px;padding:9px 16px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:8px;}
    .table-card{background:white;border-radius:12px;border:1px solid #E5E7EB;overflow:hidden;}
    table{width:100%;border-collapse:collapse;font-size:13.5px;}
    th{padding:10px 16px;text-align:left;background:#F9FAFB;color:#6B7280;font-weight:500;border-bottom:1px solid #F3F4F6;font-size:12px;text-transform:uppercase;letter-spacing:.05em;}
    td{padding:12px 16px;border-bottom:1px solid #F9FAFB;color:#374151;vertical-align:middle;}
    tr:last-child td{border-bottom:none;} tr:hover td{background:#FAFAFA;}
    .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:500;}
    .badge-actif{background:#D1FAE5;color:#065F46;} .badge-inactif{background:#FEE2E2;color:#991B1B;}
    .action-btn{background:none;border:none;cursor:pointer;padding:5px 7px;border-radius:6px;color:#6B7280;font-size:14px;transition:all .15s;}
    .action-btn:hover{background:#F3F4F6;}
    .pagination-wrap{padding:16px 20px;display:flex;justify-content:flex-end;}
</style>
@endpush

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-tags"></i></div>
        <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Catégories totales</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
        <div><div class="stat-value">{{ $stats['active'] }}</div><div class="stat-label">Actives</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-solid fa-circle-xmark"></i></div>
        <div><div class="stat-value">{{ $stats['inactive'] }}</div><div class="stat-label">Désactivées</div></div>
    </div>
</div>

<div class="toolbar">
    <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Rechercher une catégorie...">
    </div>
    <button class="btn-add"><i class="fa-solid fa-plus"></i> Ajouter</button>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr><th>Nom de la catégorie</th><th>Nombre de services</th><th>Statut</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <tr>
                <td style="font-weight:500;">{{ ucfirst($cat->category) }}</td>
                <td>{{ $cat->total }}</td>
                <td>
                    @if($cat->active > 0)
                        <span class="badge badge-actif"><i class="fa-solid fa-circle" style="font-size:7px;margin-right:4px;"></i>Actif</span>
                    @else
                        <span class="badge badge-inactif"><i class="fa-solid fa-circle" style="font-size:7px;margin-right:4px;"></i>Désactivé</span>
                    @endif
                </td>
                <td>
                    <button class="action-btn" title="Voir"><i class="fa-regular fa-eye"></i></button>
                    <button class="action-btn" title="Modifier"><i class="fa-regular fa-pen-to-square"></i></button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $categories->links() }}</div>
</div>
@endsection