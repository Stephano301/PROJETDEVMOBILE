@extends('admin.layouts.admin')
@section('title', 'Paiements')
@section('page-title', 'Paiements')

@push('styles')
<style>
    .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
    .stat-card{background:white;border-radius:12px;padding:18px 20px;border:1px solid #E5E7EB;display:flex;align-items:center;gap:14px;}
    .stat-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
    .stat-icon.blue{background:#EFF6FF;color:#1565C0;} .stat-icon.green{background:#F0FDF4;color:#16A34A;} .stat-icon.orange{background:#FFF7ED;color:#EA580C;} .stat-icon.red{background:#FEF2F2;color:#DC2626;}
    .stat-value{font-size:20px;font-weight:700;color:#111827;line-height:1.2;} .stat-label{font-size:12px;color:#6B7280;}
    .toolbar{display:flex;align-items:center;gap:12px;margin-bottom:16px;}
    .search-box{display:flex;align-items:center;gap:8px;background:white;border:1px solid #E5E7EB;border-radius:8px;padding:8px 14px;width:280px;}
    .search-box input{border:none;outline:none;font-size:14px;color:#374151;width:100%;background:transparent;}
    .search-box i{color:#9CA3AF;}
    .filter-select{background:white;border:1px solid #E5E7EB;border-radius:8px;padding:8px 14px;font-size:14px;color:#374151;outline:none;cursor:pointer;}
    .table-card{background:white;border-radius:12px;border:1px solid #E5E7EB;overflow:hidden;}
    table{width:100%;border-collapse:collapse;font-size:13.5px;}
    th{padding:10px 16px;text-align:left;background:#F9FAFB;color:#6B7280;font-weight:500;border-bottom:1px solid #F3F4F6;font-size:12px;text-transform:uppercase;letter-spacing:.05em;}
    td{padding:12px 16px;border-bottom:1px solid #F9FAFB;color:#374151;vertical-align:middle;}
    tr:last-child td{border-bottom:none;} tr:hover td{background:#FAFAFA;}
    .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:500;}
    .badge-completed{background:#D1FAE5;color:#065F46;} .badge-confirmed{background:#DBEAFE;color:#1E40AF;} .badge-cancelled{background:#FEE2E2;color:#991B1B;}
    .action-btn{background:none;border:none;cursor:pointer;padding:5px 7px;border-radius:6px;color:#6B7280;font-size:14px;transition:all .15s;}
    .action-btn:hover{background:#F3F4F6;}
    .ref-code{font-family:monospace;background:#F3F4F6;padding:2px 6px;border-radius:4px;font-size:12px;}
    .pagination-wrap{padding:16px 20px;display:flex;justify-content:flex-end;}
</style>
@endpush

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-money-bill-wave"></i></div>
        <div><div class="stat-value">{{ number_format($stats['total_revenue'],0,',',' ') }} FCFA</div><div class="stat-label">Revenu total</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
        <div><div class="stat-value">{{ number_format($stats['paid'],0,',',' ') }} FCFA</div><div class="stat-label">Payés</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
        <div><div class="stat-value">{{ number_format($stats['pending'],0,',',' ') }} FCFA</div><div class="stat-label">En attente</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-solid fa-rotate-left"></i></div>
        <div><div class="stat-value">0 FCFA</div><div class="stat-label">Remboursés</div></div>
    </div>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('admin.payments') }}" style="display:flex;gap:12px;align-items:center;">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un paiement...">
        </div>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="all">Tous les statuts</option>
            <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Payé</option>
            <option value="confirmed" {{ request('status')=='confirmed'?'selected':'' }}>En attente</option>
        </select>
    </form>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr><th>Référence</th><th>Client</th><th>Prestataire</th><th>Montant</th><th>Statut</th><th>Date</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td><span class="ref-code">#RES-{{ str_pad($payment->id, 3, '0', STR_PAD_LEFT) }}</span></td>
                <td>{{ $payment->user?->name ?? '—' }}</td>
                <td>{{ $payment->provider?->user?->name ?? '—' }}</td>
                <td style="font-weight:600;">{{ number_format($payment->total_price,0,',',' ') }} FCFA</td>
                <td>
                    @if($payment->status == 'completed')
                        <span class="badge badge-completed">Payé</span>
                    @elseif($payment->status == 'confirmed')
                        <span class="badge badge-confirmed">En attente</span>
                    @else
                        <span class="badge badge-cancelled">Annulé</span>
                    @endif
                </td>
                <td style="color:#6B7280;">{{ $payment->booking_date ? \Carbon\Carbon::parse($payment->booking_date)->format('d/m/Y') : '—' }}</td>
                <td><button class="action-btn" title="Voir"><i class="fa-regular fa-eye"></i></button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $payments->links() }}</div>
</div>
@endsection