@extends('admin.layouts.admin')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@push('styles')
<style>
    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px 24px;
        border: 1px solid #E5E7EB;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-icon.blue   { background: #EFF6FF; color: #1565C0; }
    .stat-icon.green  { background: #F0FDF4; color: #16A34A; }
    .stat-icon.orange { background: #FFF7ED; color: #EA580C; }
    .stat-icon.purple { background: #FAF5FF; color: #7C3AED; }

    .stat-value {
        font-size: 26px;
        font-weight: 700;
        color: #111827;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 13px;
        color: #6B7280;
        margin-top: 2px;
    }

    /* Tables Grid */
    .tables-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .table-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        overflow: hidden;
    }

    .table-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #F3F4F6;
        font-size: 15px;
        font-weight: 600;
        color: #111827;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }

    th {
        padding: 10px 16px;
        text-align: left;
        background: #F9FAFB;
        color: #6B7280;
        font-weight: 500;
        border-bottom: 1px solid #F3F4F6;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    td {
        padding: 12px 16px;
        border-bottom: 1px solid #F9FAFB;
        color: #374151;
        vertical-align: middle;
    }

    tr:last-child td { border-bottom: none; }

    tr:hover td { background: #FAFAFA; }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-actif      { background: #D1FAE5; color: #065F46; }
    .badge-inactif    { background: #FEE2E2; color: #991B1B; }
    .badge-pending    { background: #FEF3C7; color: #92400E; }
    .badge-confirmed  { background: #DBEAFE; color: #1E40AF; }
    .badge-completed  { background: #D1FAE5; color: #065F46; }
    .badge-cancelled  { background: #FEE2E2; color: #991B1B; }
    .badge-in_progress{ background: #E0E7FF; color: #3730A3; }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 12px;
        background: #F3F4F6;
        color: #374151;
    }

    .action-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px 7px;
        border-radius: 6px;
        color: #6B7280;
        font-size: 14px;
        transition: all 0.15s;
    }

    .action-btn:hover { background: #F3F4F6; color: #111827; }
    .action-btn.view:hover  { color: #1565C0; }
    .action-btn.edit:hover  { color: #16A34A; }

    .table-footer {
        padding: 14px 20px;
        text-align: center;
        border-top: 1px solid #F3F4F6;
    }

    .table-footer a {
        color: #1565C0;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .table-footer a:hover { text-decoration: underline; }

    .avatar-text {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #1565C0;
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        margin-right: 8px;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
                <div class="stat-label">Utilisateurs totaux</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_providers']) }}</div>
                <div class="stat-label">Prestataires actifs</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($stats['total_bookings']) }}</div>
                <div class="stat-label">Réservations totales</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <div class="stat-value">
                    {{ number_format($stats['total_revenue'], 0, ',', ' ') }} FCFA
                </div>
                <div class="stat-label">Revenu total</div>
            </div>
        </div>
    </div>

    {{-- Tables --}}
    <div class="tables-grid">

        {{-- Utilisateurs récents --}}
        <div class="table-card">
            <div class="table-card-header">
                <i class="fa-solid fa-users" style="color:#1565C0;margin-right:8px;"></i>
                Utilisateurs récents
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Rôle</th>
                        <th>Email</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $user)
                    <tr>
                        <td>
                            <span class="avatar-text">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                            {{ $user->name }}
                        </td>
                        <td>
                            <span class="role-badge">
                                {{ ucfirst($user->getRoleNames()->first() ?? 'client') }}
                            </span>
                        </td>
                        <td style="color:#6B7280;">{{ $user->email }}</td>
                        <td>
                            <span class="badge badge-actif">
                                <i class="fa-solid fa-circle" style="font-size:7px;margin-right:4px;"></i>
                                Actif
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="table-footer">
                <a href="{{ route('admin.users') }}">
                    Voir tous les utilisateurs
                    <i class="fa-solid fa-arrow-right" style="font-size:12px;margin-left:4px;"></i>
                </a>
            </div>
        </div>

        {{-- Réservations récentes --}}
        <div class="table-card">
            <div class="table-card-header">
                <i class="fa-solid fa-calendar-check" style="color:#1565C0;margin-right:8px;"></i>
                Réservations récentes
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Prestataire</th>
                        <th>Service</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                    <tr>
                        <td>{{ $booking->user?->name ?? '—' }}</td>
                        <td>{{ $booking->provider?->user?->name ?? '—' }}</td>
                        <td>{{ $booking->service?->name ?? '—' }}</td>
                        <td>
                            @php
                                $statusLabels = [
                                    'pending'     => 'En attente',
                                    'confirmed'   => 'Confirmé',
                                    'in_progress' => 'En cours',
                                    'completed'   => 'Terminé',
                                    'cancelled'   => 'Annulé',
                                ];
                                $label = $statusLabels[$booking->status] ?? $booking->status;
                            @endphp
                            <span class="badge badge-{{ $booking->status }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td>
                            <button class="action-btn view" title="Voir">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                            <button class="action-btn edit" title="Modifier">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="table-footer">
                <a href="{{ route('admin.bookings') }}">
                    Voir toutes les réservations
                    <i class="fa-solid fa-arrow-right" style="font-size:12px;margin-left:4px;"></i>
                </a>
            </div>
        </div>

    </div>

@endsection