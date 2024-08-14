<nav class="navbar navbar-expand-lg bg-dark border-bottom border-bottom-dark ticky-top bg-body-tertiary"
    data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand fw-light" href="{{ route('home') }}"><span class="fas fa-pen-nib me-1"> </span>{{config('app.name')}}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">

                @guest

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('login')}}">Login</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('register')}}">Register</a>
                    </li>

                @endguest

                @auth

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <input class="nav-link" type="submit" value="LOG OUT">
                        </form>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('profile.settings', auth()->user()->id)}}">{{ auth()->user()->name }}</a>
                    </li>
                    
                @endauth

            </ul>
        </div>
    </div>
</nav>