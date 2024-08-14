<div class="card overflow-hidden">
    <div class="card-body">
        @auth
            <ul class="p-0 m-0">
                @forelse ($conversations as $conversation)
                    <a class="nav-link text-dark" href="{{ route('conversations.message.show', $conversation->id) }}">
                        <li class="nav-item border border-2 mb-3 me-0 p-2 d-flex justify-content-center align-items-center" style="list-style-type: none; width: 100%;">
                                <h5 class="m-0">{{ $conversation->name }}</h5>
                        </li>
                    </a>
                @empty
                    <h4>No conversations found</h4>
                @endforelse
            </ul>
        @endauth
        @guest
            <h5>Login to access your conversations.</h5>
        @endguest
    </div>
    @auth
        <form action="{{ route('conversation.create') }}" method="get">
            <div class="border d-flex justify-content-center align-items-center">
                <input type="submit" value="Create a new conversation" class="btn btn-primary w-100">
            </div>
        </form>
    @endauth
</div>