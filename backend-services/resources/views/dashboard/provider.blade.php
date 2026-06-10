@extends('layouts.dashboard')

@section('title', 'Dashboard Prestataire')
@section('page-title', 'Mon Planning')

@section('content')

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="label">Total réservations</div>
            <div class="value blue">{{ $stats['total_bookings'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">En attente</div>
            <div class="value orange">{{ $stats['pending'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Terminées</div>
            <div class="value green">{{ $stats['completed'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Note moyenne</div>
            <div class="value gold">{{ number_format($stats['rating'], 1) }} ⭐</div>
        </div>
    </div>

    {{-- FullCalendar --}}
    <div class="calendar-card">
        <div class="card-title">📅 Planning des réservations</div>
        <div id="calendar"></div>
    </div>

    {{-- Table réservations --}}
    <div class="table-card">
        <div class="card-title">📋 Réservations à venir</div>
        @if(count($bookings) === 0)
            <p style="color:#9E9E9E; font-size:14px; padding:16px 0;">
                Aucune réservation pour le moment.
            </p>
        @else
        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Adresse</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking['title'] }}</td>
                    <td>—</td>
                    <td>{{ \Carbon\Carbon::parse($booking['start'])->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking['start'])->format('H:i') }}</td>
                    <td>{{ $booking['address'] }}</td>
                    <td>
                        <span class="badge badge-{{ $booking['status'] }}">
                            {{ ucfirst($booking['status']) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'fr',
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: @json($bookings),
        eventClick: function(info) {
            alert(
                'Réservation : ' + info.event.title +
                '\nStatut : '    + info.event.extendedProps.status +
                '\nAdresse : '   + info.event.extendedProps.address
            );
        },
        height: 'auto',
        slotMinTime: '07:00:00',
        slotMaxTime: '20:00:00',
    });
    calendar.render();
});
</script>
@endpush