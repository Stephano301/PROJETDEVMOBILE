@extends('admin.layouts.admin')
@section('title', 'Prestataires')
@section('page-title', 'Prestataires')

@push('styles')
<style>
    .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
    .stat-card{background:white;border-radius:12px;padding:18px 20px;border:1px solid #E5E7EB;display:flex;align-items:center;gap:14px;}
    .stat-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
    .stat-icon.blue{background:#EFF6FF;color:#1565C0;} .stat-icon.green{background:#F0FDF4;color:#16A34A;} .stat-icon.orange{background:#FFF7ED;color:#EA580C;} .stat-icon.red{background:#FEF2F2;color:#DC2626;}
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
    .badge-actif{background:#D1FAE5;color:#065F46;} .badge-inactif{background:#FEE2E2;color:#991B1B;} .badge-pending{background:#FEF3C7;color:#92400E;}
    .action-btn{background:none;border:none;cursor:pointer;padding:5px 7px;border-radius:6px;color:#6B7280;font-size:14px;transition:all .15s;}
    .action-btn:hover{background:#F3F4F6;}
    .avatar-text{width:32px;height:32px;border-radius:50%;background:#1565C0;color:white;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;margin-right:8px;vertical-align:middle;}
    .stars{color:#F59E0B;}
    .pagination-wrap{padding:16px 20px;display:flex;justify-content:flex-end;}
    .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:1000;align-items:center;justify-content:center;}
    .modal-overlay.active{display:flex;}
    .modal{background:white;border-radius:16px;padding:28px;width:500px;max-width:95vw;max-height:90vh;overflow-y:auto;}
    .modal h3{font-size:18px;font-weight:700;color:#111827;margin-bottom:20px;}
    .form-group{margin-bottom:16px;}
    .form-group label{display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px;}
    .form-group input,.form-group select,.form-group textarea{width:100%;padding:10px 14px;border:1px solid #E5E7EB;border-radius:8px;font-size:14px;color:#374151;outline:none;box-sizing:border-box;}
    .form-group input:focus{border-color:#1565C0;}
    .modal-footer{display:flex;justify-content:flex-end;gap:10px;margin-top:20px;}
    .btn-cancel{background:#F3F4F6;color:#374151;border:none;border-radius:8px;padding:9px 16px;font-size:14px;cursor:pointer;}
    .btn-submit{background:#1565C0;color:white;border:none;border-radius:8px;padding:9px 16px;font-size:14px;font-weight:500;cursor:pointer;}
    .alert-success{background:#D1FAE5;color:#065F46;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:14px;}
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert-success">{{ session('success') }}</div>
@endif

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-user-tie"></i></div>
        <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Prestataires totaux</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
        <div><div class="stat-value">{{ $stats['active'] }}</div><div class="stat-label">Actifs</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
        <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">En attente</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-solid fa-ban"></i></div>
        <div><div class="stat-value">{{ $stats['suspended'] }}</div><div class="stat-label">Suspendus</div></div>
    </div>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('admin.providers') }}">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un prestataire...">
        </div>
    </form>
    <button class="btn-add" onclick="document.getElementById('modalProvider').classList.add('active')">
        <i class="fa-solid fa-plus"></i> Ajouter
    </button>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr><th>Nom</th><th>Service</th><th>Email</th><th>Note</th><th>Statut</th><th>Inscription</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($providers as $provider)
            <tr>
                <td>
                    <span class="avatar-text">{{ strtoupper(substr($provider->user->name,0,1)) }}</span>
                    {{ $provider->user->name }}
                </td>
                <td>{{ $provider->services->first()?->name ?? '—' }}</td>
                <td style="color:#6B7280;">{{ $provider->user->email }}</td>
                <td>
                    <span class="stars">
                        @for($i=1;$i<=5;$i++)
                            <i class="fa-{{ $i <= round($provider->rating) ? 'solid' : 'regular' }} fa-star" style="font-size:12px;"></i>
                        @endfor
                    </span>
                    <span style="font-size:12px;color:#6B7280;margin-left:4px;">{{ number_format($provider->rating,1) }}</span>
                </td>
                <td>
                    @if($provider->is_available)
                        <span class="badge badge-actif"><i class="fa-solid fa-circle" style="font-size:7px;margin-right:4px;"></i>Actif</span>
                    @else
                        <span class="badge badge-inactif"><i class="fa-solid fa-circle" style="font-size:7px;margin-right:4px;"></i>Inactif</span>
                    @endif
                </td>
                <td style="color:#6B7280;">{{ $provider->created_at->format('d/m/Y') }}</td>
                <td>
                    <button class="action-btn" title="Voir"><i class="fa-regular fa-eye"></i></button>
                    <form method="POST" action="{{ route('admin.providers.toggle', $provider->id) }}" style="display:inline;">
                        @csrf @method('PATCH')
                        <button type="submit" class="action-btn" title="{{ $provider->is_available ? 'Suspendre' : 'Activer' }}">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $providers->links('pagination::simple-tailwind') }}</div>
</div>

<!-- Modal Ajouter Prestataire -->
<div class="modal-overlay" id="modalProvider">
    <div class="modal">
        <h3>Nouveau prestataire</h3>
        <form method="POST" action="{{ route('admin.providers.store') }}">
            @csrf
            <div class="form-group">
                <label>Nom complet</label>
                <input type="text" name="name" required placeholder="Ex: Jean Dupont">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="jean@exemple.com">
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required placeholder="Minimum 8 caractères">
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="text" name="phone" placeholder="Ex: 034 12 345 67">
            </div>
            <div class="form-group">
                <label>Ville</label>
                <input type="text" name="city" placeholder="Ex: Antananarivo">
            </div>
            <div class="form-group">
                <label>Bio</label>
                <textarea name="bio" rows="3" placeholder="Description du prestataire..."></textarea>
            </div>
            <div class="form-group">
                <label>Tarif horaire (FCFA)</label>
                <input type="number" name="hourly_rate" required min="0" placeholder="Ex: 25000">
            </div>
            <div class="form-group">
                <label>Service principal</label>
                <select name="service_id">
                    <option value="">Sélectionner un service</option>
                    @foreach(\App\Models\Service::where('is_active', true)->get() as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="document.getElementById('modalProvider').classList.remove('active')">Annuler</button>
                <button type="submit" class="btn-submit">Ajouter</button>
            </div>
        </form>
    </div>
</div>
@endsection