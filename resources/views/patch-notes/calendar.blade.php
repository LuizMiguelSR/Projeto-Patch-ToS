@extends('layouts.index')

@section('title', 'Calendar')

@section('content')
    <div class="container my-4">
        <h2 class="mb-4">🗓️ Calendar</h2>
        <div id="calendar"></div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const maintenanceDays = @json($dates);

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                dayCellDidMount: function(arg) {
                    const dateStr = arg.date.toISOString().split('T')[0];

                    const maintenance = maintenanceDays.find(d => d.date === dateStr);

                    if (maintenance) {
                        const link = document.createElement('a');
                        link.href = maintenance.url;
                        link.target = '_self';
                        link.style.display = 'block';
                        link.style.width = '100%';
                        link.style.height = '100%';
                        link.style.backgroundImage = "url('{{ asset('images/maintencenew.png') }}')";
                        link.style.backgroundSize = 'contain';
                        link.style.backgroundRepeat = 'no-repeat';
                        link.style.backgroundPosition = 'center';
                        link.style.position = 'absolute';
                        link.style.top = '0';
                        link.style.left = '0';
                        link.style.zIndex = '1';

                        arg.el.style.position = 'relative';
                        arg.el.appendChild(link);
                    }
                }
            });

            calendar.render();
        });
    </script>

    <style>
        #calendar .fc-daygrid-day.fc-day-today {
            background-color: #f1f1f1 !important;
        }
        .fc-daygrid-event {
            opacity: 0.7;
        }
    </style>
@endsection
