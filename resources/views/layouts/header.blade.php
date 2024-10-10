<header class="bg-dark text-white fixed-top">
    <div class="px-3">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="#">DM Helper</a>
            <div class="navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">Головна</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Спільнота</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Персонажі</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="/game">Ігри</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/playlists">Плейлисти</a>
                        </li>
                        <li class="nav-item">
                            @if(!request()->has('play'))
                                <a href="{{ url()->current() }}?play=true" class="btn btn-success">Грати</a>
                            @else
                                <a href="{{ request()->fullUrlWithoutQuery('play') }}" class="btn btn-warning">Досить</a>
                            @endif
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">Вийти</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="/auth">Вхід/Реєстрація</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>
    </div>
</header>