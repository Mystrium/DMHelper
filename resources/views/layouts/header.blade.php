<header class="bg-dark text-white fixed-top">
    <div class="px-3">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="#">DM Helper</a>
            <div class="navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/users">Учасники</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/games">Ігри</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/playlists">Музика</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="/games/my">Мої ігри</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/playlists/my">Плейлисти</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="/profile/{{ Auth::user()->id }}">Профіль</a>
                                </li>
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
                            <a class="nav-link" href="/login">Вхід/Реєстрація</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>
    </div>
</header>