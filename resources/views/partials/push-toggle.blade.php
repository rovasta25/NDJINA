@auth
    @if (!auth()->user()->isAdmin())
        <div class="push-card" data-push
             data-vapid="{{ config('webpush.public_key') }}"
             data-url="{{ route('push.store') }}"
             data-csrf="{{ csrf_token() }}"
             data-sw="{{ asset('sw.js') }}">
            <div class="push-card-text">
                <strong>Alertes sur cet appareil</strong>
                <span class="muted" data-push-status>Recevez une notification dès qu'un besoin est publié.</span>
            </div>
            <button type="button" class="btn btn-outline btn-sm" data-push-btn>Activer les notifications</button>
        </div>
    @endif
@endauth
