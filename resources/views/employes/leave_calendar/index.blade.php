<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0" style="font-family: 'Inter', sans-serif; font-weight: 600; color: #2d3748;">
                <i class="bi bi-calendar3" style="color: #5a7d8a;"></i> {{ __('Calendrier des congés') }}
            </h2>
            <a href="{{ route('employe.leave-requests.create') }}" class="btn" style="background: linear-gradient(135deg, #5a7d8a, #4a6a78); color: #fff; border: none; border-radius: 10px; padding: 8px 20px; font-weight: 500; font-size: 0.9rem; transition: all 0.3s; box-shadow: 0 2px 8px rgba(90, 125, 138, 0.25);">
                <i class="bi bi-plus-circle"></i> {{ __('Nouvelle demande') }}
            </a>
        </div>
    </x-slot>

    <div class="p-3" style="background: #f5f7fa; min-height: 100vh;">
        <div class="card border-0" style="border-radius: 16px; background: #ffffff; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
            <div class="card-body p-4">
                <!-- En-tête avec légende -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h5 style="font-family: 'Inter', sans-serif; font-weight: 600; color: #2d3748; font-size: 0.95rem; margin: 0;">
                        <i class="bi bi-grid-3x3-gap" style="color: #5a7d8a;"></i> Vue d'ensemble
                    </h5>
                    <div class="d-flex gap-3 flex-wrap">
                        <span class="d-flex align-items-center" style="font-size: 0.8rem; color: #4a5568; font-weight: 500;">
                            <span style="display: inline-block; width: 12px; height: 12px; background: #38a169; border-radius: 4px; margin-right: 6px;"></span>
                            Approuvé
                        </span>
                        <span class="d-flex align-items-center" style="font-size: 0.8rem; color: #4a5568; font-weight: 500;">
                            <span style="display: inline-block; width: 12px; height: 12px; background: #d69e2e; border-radius: 4px; margin-right: 6px;"></span>
                            En attente
                        </span>
                        <span class="d-flex align-items-center" style="font-size: 0.8rem; color: #4a5568; font-weight: 500;">
                            <span style="display: inline-block; width: 12px; height: 12px; background: #e53e3e; border-radius: 4px; margin-right: 6px;"></span>
                            Refusé
                        </span>
                        <span class="d-flex align-items-center" style="font-size: 0.8rem; color: #4a5568; font-weight: 500;">
                            <span style="display: inline-block; width: 12px; height: 12px; background: #a0aec0; border-radius: 4px; margin-right: 6px;"></span>
                            Brouillon
                        </span>
                    </div>
                </div>
                
                <!-- Le calendrier -->
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    @push('styles')
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
    
    <style>
        /* Calendrier principal */
        #calendar {
            max-width: 100%;
            margin: 0 auto;
        }
        
        #calendar .fc {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
        }
        
        /* Toolbar */
        #calendar .fc-toolbar-title {
            font-family: 'Inter', sans-serif !important;
            color: #2d3748 !important;
            font-size: 1.1rem !important;
            font-weight: 600 !important;
            letter-spacing: -0.3px;
        }
        
        #calendar .fc-toolbar {
            flex-wrap: wrap !important;
            gap: 10px !important;
            margin-bottom: 24px !important;
            padding-bottom: 16px !important;
            border-bottom: 1px solid #edf2f7 !important;
        }
        
        /* Boutons */
        #calendar .fc-button {
            background: #f7fafc !important;
            color: #4a5568 !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 6px 16px !important;
            font-weight: 500 !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 0.85rem !important;
            transition: all 0.2s ease !important;
            box-shadow: none !important;
        }
        
        #calendar .fc-button:hover {
            background: #edf2f7 !important;
            border-color: #cbd5e0 !important;
            transform: translateY(-1px);
        }
        
        #calendar .fc-button-primary:not(:disabled):active,
        #calendar .fc-button-primary:not(:disabled).fc-button-active {
            background: #5a7d8a !important;
            border-color: #5a7d8a !important;
            color: #fff !important;
        }
        
        #calendar .fc-button-primary:not(:disabled):active:focus,
        #calendar .fc-button-primary:not(:disabled).fc-button-active:focus {
            box-shadow: none !important;
        }
        
        /* En-tête des jours */
        #calendar .fc-col-header-cell {
            background: #f7fafc !important;
            padding: 8px 0 !important;
        }
        
        #calendar .fc-col-header-cell-cushion {
            font-weight: 600 !important;
            color: #5a7d8a !important;
            font-size: 0.8rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            text-decoration: none !important;
        }
        
        /* Jours */
        #calendar .fc-daygrid-day-number {
            font-weight: 500 !important;
            font-size: 0.85rem !important;
            color: #2d3748 !important;
            padding: 6px 8px !important;
        }
        
        #calendar .fc-day-today {
            background-color: rgba(90, 125, 138, 0.06) !important;
        }
        
        #calendar .fc-day-today .fc-daygrid-day-number {
            color: #5a7d8a !important;
            font-weight: 700 !important;
        }
        
        /* Jours du week-end */
        #calendar .fc-day-sat .fc-daygrid-day-number,
        #calendar .fc-day-sun .fc-daygrid-day-number {
            color: #a0aec0 !important;
        }
        
        /* Événements */
        #calendar .fc-event {
            cursor: pointer !important;
            border: none !important;
            border-radius: 6px !important;
            padding: 3px 8px !important;
            margin: 2px 4px !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            transition: all 0.25s ease !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
        }
        
        #calendar .fc-event:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
        }
        
        #calendar .fc-event-title {
            font-weight: 500 !important;
            font-size: 0.7rem !important;
        }
        
        /* Couleurs des événements */
        #calendar .fc-event-approved { 
            background: #38a169 !important; 
        }
        #calendar .fc-event-pending { 
            background: #d69e2e !important; 
        }
        #calendar .fc-event-rejected { 
            background: #e53e3e !important; 
        }
        #calendar .fc-event-draft { 
            background: #a0aec0 !important; 
        }
        
        /* Aujourd'hui */
        #calendar .fc-day-today {
            background: rgba(90, 125, 138, 0.06) !important;
        }
        
        /* Popover */
        #calendar .fc-popover {
            border-radius: 12px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08) !important;
            overflow: hidden !important;
        }
        
        #calendar .fc-popover-header {
            background: #f7fafc !important;
            padding: 8px 12px !important;
        }
        
        #calendar .fc-popover-title {
            font-family: 'Inter', sans-serif !important;
            font-weight: 600 !important;
            color: #2d3748 !important;
            font-size: 0.85rem !important;
        }
        
        /* Plus link */
        #calendar .fc-daygrid-more-link {
            font-size: 0.7rem !important;
            color: #5a7d8a !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            padding: 2px 8px !important;
            border-radius: 4px !important;
            transition: background 0.2s !important;
        }
        
        #calendar .fc-daygrid-more-link:hover {
            background: #f0f4f8 !important;
        }
        
        /* Message vide */
        #calendar .fc-no-events {
            font-family: 'Inter', sans-serif !important;
            color: #a0aec0 !important;
            font-size: 0.9rem !important;
            padding: 30px 0 !important;
            text-align: center !important;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            #calendar .fc-toolbar {
                flex-direction: column !important;
                align-items: center !important;
                gap: 12px !important;
            }
            #calendar .fc-toolbar-chunk {
                display: flex !important;
                justify-content: center !important;
                width: 100% !important;
            }
            #calendar .fc-toolbar-title {
                font-size: 0.95rem !important;
            }
            #calendar .fc-event {
                font-size: 0.6rem !important;
                padding: 2px 4px !important;
            }
            #calendar .fc-daygrid-day-number {
                font-size: 0.75rem !important;
                padding: 4px !important;
            }
        }
        
        /* Animation d'apparition */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        #calendar {
            animation: slideUp 0.6s ease forwards;
        }
        
        /* Scrollbar personnalisée */
        .fc-scroller {
            scrollbar-width: thin;
            scrollbar-color: #e2e8f0 transparent;
        }
        .fc-scroller::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        .fc-scroller::-webkit-scrollbar-track {
            background: transparent;
        }
        .fc-scroller::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 8px;
        }
    </style>
    @endpush

    @push('scripts')
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            
            if (!calendarEl) {
                console.error('Calendrier non trouvé');
                return;
            }
            
            try {
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    // Vue initiale
                    initialView: 'dayGridMonth',
                    locale: 'fr',
                    
                    // Barre d'outils
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    
                    // Boutons
                    buttonText: {
                        today: "Aujourd'hui",
                        month: "Mois",
                        week: "Semaine",
                        day: "Jour"
                    },
                    
                    // Événements
                    events: '{{ route("employe.calendar.events") }}',
                    
                    // Couleurs par défaut
                    eventColor: '#5a7d8a',
                    eventTextColor: '#ffffff',
                    
                    // Clic sur un événement
                    eventClick: function(info) {
                        if (info.event.extendedProps.url) {
                            window.location.href = info.event.extendedProps.url;
                        }
                    },
                    
                    // Personnalisation des événements
                    eventDidMount: function(info) {
                        const status = info.event.extendedProps.status;
                        const colors = {
                            'pending': '#d69e2e',
                            'approved': '#38a169',
                            'rejected': '#e53e3e',
                            'draft': '#a0aec0'
                        };
                        info.el.style.backgroundColor = colors[status] || '#5a7d8a';
                        info.el.style.borderColor = colors[status] || '#5a7d8a';
                        
                        if (status) {
                            info.el.classList.add('fc-event-' + status);
                        }
                    },
                    
                    // Affichage du contenu des événements
                    eventContent: function(info) {
                        const statusLabels = {
                            'pending': 'En attente',
                            'approved': 'Approuvé',
                            'rejected': 'Refusé',
                            'draft': 'Brouillon'
                        };
                        
                        return {
                            html: `
                                <div class="fc-event-title">
                                    <strong>${info.event.title}</strong>
                                    <div style="font-size: 0.6rem; opacity: 0.85; font-weight: 400;">
                                        ${statusLabels[info.event.extendedProps.status] || info.event.extendedProps.status}
                                    </div>
                                </div>
                            `
                        };
                    },
                    
                    // Messages
                    noEventsText: 'Aucun congé prévu pour cette période',
                    
                    // Hauteur
                    height: 'auto',
                    contentHeight: 'auto',
                    
                    // Premier jour de la semaine (lundi)
                    firstDay: 1,
                    
                    // Week-ends
                    weekends: true,
                    
                    // Afficher les heures
                    displayEventTime: false,
                    
                    // All-day
                    allDaySlot: true,
                    
                    // Aspect
                    dayMaxEvents: 3,
                    moreLinkText: function(n) {
                        return '+ ' + n + ' autre(s)';
                    },
                    
                    // Loading
                    loading: function(isLoading) {
                        const loader = document.getElementById('calendarLoader');
                        if (isLoading) {
                            if (!loader) {
                                const newLoader = document.createElement('div');
                                newLoader.id = 'calendarLoader';
                                newLoader.className = 'text-center py-4';
                                newLoader.style.color = '#a0aec0';
                                newLoader.innerHTML = `
                                    <div class="spinner-border" style="width: 30px; height: 30px; color: #5a7d8a;" role="status">
                                        <span class="visually-hidden">Chargement...</span>
                                    </div>
                                    <p style="margin-top: 8px; font-size: 0.9rem;">Chargement du calendrier...</p>
                                `;
                                calendarEl.parentNode.insertBefore(newLoader, calendarEl);
                            }
                        } else {
                            if (loader) {
                                loader.remove();
                            }
                        }
                    }
                });
                
                // Rendre le calendrier
                calendar.render();
                
                // Redimensionner au changement de fenêtre
                let resizeTimeout;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(function() {
                        calendar.updateSize();
                    }, 200);
                });
                
                console.log('Calendrier chargé avec succès !');
                
            } catch (error) {
                console.error('Erreur lors du chargement du calendrier:', error);
                calendarEl.innerHTML = `
                    <div class="alert" style="border-radius: 12px; border: 1px solid #fed7d7; background: #fff5f5; color: #9b2c2c; padding: 20px;">
                        <i class="bi bi-exclamation-triangle" style="color: #e53e3e;"></i> 
                        Erreur lors du chargement du calendrier. Veuillez réessayer.
                    </div>
                `;
            }
        });
    </script>
    @endpush
</x-app-layout>