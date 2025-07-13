<div>
    <h1>Test di Paginazione</h1>

    <button wire:click="$toggle('filtered')" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
        Applica Filtro
    </button>

    <p>Filtro attivo: {{ $filtered ? 'Sì' : 'No' }}</p>

    <ul style="margin-left: 20px;">
        @foreach ($users as $user)
            <li wire:key="{{ $user->id }}">Utente: {{ $user->name }}</li>
        @endforeach
    </ul>

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>