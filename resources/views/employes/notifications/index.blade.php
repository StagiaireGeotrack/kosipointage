<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-bell"></i> Mes notifications
                @if($unreadCount > 0)
                    <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                @endif
            </h2>
            <a href="{{ route('employe.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="p-3">
        <div class="card">
            <div class="card-body">
                @if($notifications->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-bell-slash fs-1 d-block mb-2"></i>
                        <p>Aucune notification</p>
                        <small class="text-muted">Connecté en tant que : {{ Auth::guard('employe')->user()->Nom ?? Auth::user()->name ?? 'Utilisateur' }}</small>
                        <br>
                        <small class="text-muted">ID utilisateur : {{ Auth::guard('employe')->user()->ID ?? Auth::user()->ID ?? 'Inconnu' }}</small>
                    </div>
                @else
                    @foreach($notifications as $notification)
                        <div class="d-flex justify-content-between align-items-center p-3 mb-2 rounded" 
                             style="background: {{ $notification->is_read ? '#f8f9fa' : '#e8f4fd' }}; border-left: 4px solid {{ $notification->is_read ? '#ccc' : '#4299e1' }};">
                            <div>
                                <h6 class="mb-1">{{ $notification->title }}</h6>
                                <p class="mb-0 text-muted small">{{ $notification->message }}</p>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                @if($notification->leave_request_id)
                                    <a href="{{ route('employe.leave-requests.show', $notification->leave_request_id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> Voir
                                    </a>
                                @endif
                                @if(!$notification->is_read)
                                    <button onclick="markAsRead({{ $notification->id }})" 
                                            class="btn btn-sm btn-secondary">
                                        <i class="bi bi-check2"></i> Marquer comme lu
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function markAsRead(id) {
            const url = '{{ route("employe.notifications.read", ":id") }}'.replace(':id', id);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                alert('Erreur réseau');
            });
        }
    </script>
    @endpush
</x-app-layout>