@extends('admin.layouts.admin')
@section('title', 'Réservations')
@section('page-title', 'Réservations')

@push('styles')
<style>
    .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
    .stat-card{background:white;border-radius:12px;padding:18px 20px;border:1px solid #E5E7EB;display:flex;align-items:center;gap:14px;}
    .stat-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
    .stat-icon.blue{background:#EFF6FF;color:#1565C0;} .stat-icon.green{background:#F0FDF4;color:#16A34A;} .stat-icon.orange{background:#FFF7ED;color:#EA580C;} .stat-icon.red{background:#FEF2F2;color:#DC2626;}
    .stat-value{font-size:22px;font-weight:700;color:#111827;} .stat-label{font-size:12px;color:#6B7280;}
    .toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;}
    .toolbar-left{display:flex;align-items:center;gap:12px;}
    .search-box{display:flex;align-items:center;gap:8px;background:white;border:1px solid #E5E7EB;border-radius:8px;padding:8px 14px;width:280px;}
    .search-box input{border:none;outline:none;font-size:14px;color:#374151;width:100%;background:transparent;}
    .search-box i{color:#9CA3AF;}
    .filter-select{background:white;border:1px solid #E5E7EB;border-radius:8px;padding:8px 14px;font-size:14px;color:#374151;outline:none;cursor:pointer;}
    .btn-add{background:#1565C0;color:white;border:none;border-radius:8px;padding:9px 16px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:8px;}
    .table-card{background:white;border-radius:12px;border:1px solid #E5E7EB;overflow:hidden;}
    table{width:100%;border-collapse:collapse;font-size:13.5px;}
    th{padding:10px 16px;text-align:left;background:#F9FAFB;color:#6B7280;font-weight:500;border-bottom:1px solid #F3F4F6;font-size:12px;text-transform:uppercase;letter-spacing:.05em;}
    td{padding:12px 16px;border-bottom:1px solid #F9FAFB;color:#374151;vertical-align:middle;}
    tr:last-child td{border-bottom:none;} tr:hover td{background:#FAFAFA;}
    .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:500;}
    .badge-pending{background:#FEF3C7;color:#92400E;} .badge-confirmed{background:#DBEAFE;color:#1E40AF;} .badge-completed{background:#D1FAE5;color:#065F46;} .badge-cancelled{background:#FEE2E2;color:#991B1B;} .badge-in_progress{background:#E0E7FF;color:#3730A3;}
    .action-btn{background:none;border:none;cursor:pointer;padding:5px 7px;border-radius:6px;color:#6B7280;font-size:14px;transition:all .15s;}
    .action-btn:hover{background:#F3F4F6;}
    .status-form select{background:white;border:1px solid #E5E7EB;border-radius:6px;padding:4px 8px;font-size:12px;color:#374151;outline:none;cursor:pointer;}
    .pagination-wrap{padding:16px 20px;display:flex;justify-content:flex-end;}
    /* Modal */
    .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:1000;align-items:center;justify-content:center;}
    .modal-overlay.active{display:flex;}
    .modal{background:white;border-radius:16px;padding:28px;width:500px;max-width:95vw;max-height:90vh;overflow-y:auto;}
    .modal h3{font-size:18px;font-weight:700;color:#111827;margin-bottom:20px;}
    .form-group{margin-bottom:16px;}
    .form-group label{display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px;}
    .form-group input,.form-group select,.form-group textarea{width:100%;padding:10px 14px;border:1px solid #E5E7EB;border-radius:8px;font-size:14px;color:#374151;outline:none;box-sizing:border-box;}
    .form-group input:focus,.form-group select:focus{border-color:#1565C0;}
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
        <div class="stat-icon blue"><i class="fa-solid fa-calendar-check"></i></div>
        <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Réservations totales</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
        <div><div class="stat-value">{{ $stats['confirmed'] }}</div><div class="stat-label">Confirmées</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
        <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">En attente</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-solid fa-circle-xmark"></i></div>
        <div><div class="stat-value">{{ $stats['cancelled'] }}</div><div class="stat-label">Annulées</div></div>
    </div>
</div>

<div class="toolbar">
    <div class="toolbar-left">
        <form method="GET" action="{{ route('admin.bookings') }}" style="display:flex;gap:12px;align-items:center;">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher une réservation...">
            </div>
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="all">Tous les statuts</option>
                <option value="pending"     {{ request('status')=='pending'     ? 'selected':'' }}>En attente</option>
                <option value="confirmed"   {{ request('status')=='confirmed'   ? 'selected':'' }}>Confirmée</option>
                <option value="in_progress" {{ request('status')=='in_progress' ? 'selected':'' }}>En cours</option>
                <option value="completed"   {{ request('status')=='completed'   ? 'selected':'' }}>Terminée</option>
                <option value="cancelled"   {{ request('status')=='cancelled'   ? 'selected':'' }}>Annulée</option>
            </select>
        </form>
    </div>
    <button class="btn-add" onclick="document.getElementById('modalBooking').classList.add('active')">
        <i class="fa-solid fa-plus"></i> Ajouter
    </button>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr><th>Client</th><th>Prestataire</th><th>Service</th><th>Date</th><th>Heure</th><th>Statut</th><th>Montant</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @php
                $statusLabels = ['pending'=>'En attente','confirmed'=>'Confirmé','in_progress'=>'En cours','completed'=>'Terminé','cancelled'=>'Annulé'];
            @endphp
            @foreach($bookings as $booking)
            <tr>
                <td>{{ $booking->user?->name ?? '—' }}</td>
                <td>{{ $booking->provider?->user?->name ?? '—' }}</td>
                <td>{{ $booking->service?->name ?? '—' }}</td>
                <td style="color:#6B7280;">
                    {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') : '—' }}
                </td>
                <td style="color:#6B7280;">
                    {{ $booking->booking_time ? \Carbon\Carbon::parse($booking->booking_time)->format('H:i') : '—' }}
                </td>
                <td><span class="badge badge-{{ $booking->status }}">{{ $statusLabels[$booking->status] ?? $booking->status }}</span></td>
                <td style="font-weight:600;">{{ number_format($booking->total_price, 0, ',', ' ') }} FCFA</td>
                <td>
                    <form method="POST" action="{{ route('admin.bookings.status', $booking->id) }}" style="display:inline;" class="status-form">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()">
                            @foreach($statusLabels as $val => $label)
                                <option value="{{ $val }}" {{ $booking->status==$val ? 'selected':'' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $bookings->links('pagination::simple-tailwind') }}</div>
</div>

<!-- Modal Ajouter Réservation -->
<div class="modal-overlay" id="modalBooking">
    <div class="modal">
        <h3>Nouvelle réservation</h3>
        <form method="POST" action="{{ route('admin.bookings.store') }}">
            @csrf
            <div class="form-group">
                <label>Client</label>
                <select name="user_id" required>
                    <option value="">Sélectionner un client</option>
                    @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Prestataire</label>
                <select name="provider_id" required>
                    <option value="">Sélectionner un prestataire</option>
                    @foreach(\App\Models\Provider::with('user')->get() as $provider)
                        <option value="{{ $provider->id }}">{{ $provider->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Service</label>
                <select name="service_id" required>
                    <option value="">Sélectionner un service</option>
                    @foreach(\App\Models\Service::where('is_active', true)->get() as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="booking_date" required min="{{ date('Y-m-d') }}">
            </div>
            <div class="form-group">
                <label>Heure</label>
                <input type="time" name="booking_time" required>
            </div>
            <div class="form-group">
                <label>Adresse d'intervention</label>
                <input type="text" name="address" placeholder="120, Rue 25, Antananarivo">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="document.getElementById('modalBooking').classList.remove('active')">Annuler</button>
                <button type="submit" class="btn-submit">Ajouter</button>
            </div>
        </form>
    </div>
</div>
@endsection