<header class="bg-dark text-white fixed-top">
    <div class="px-3">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="#">DM Helper</a>
            <div class="navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/users">{{__('links.users')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/games">{{__('links.games')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/playlists">{{__('links.playlists')}}</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="/games/my">{{__('links.mygames')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/playlists/my">{{__('links.myplaylists')}}</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="/profile/{{ Auth::user()->id }}">{{__('links.profile')}}</a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">{{__('links.logout')}}</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="/login">{{__('links.login')}} / {{__('links.signin')}}</a>
                        </li>
                    @endauth
                </ul>
                <a href="{{ route('lang.switch', 'en') }}" class="nav-link">Eng</a>|
                <a href="{{ route('lang.switch', 'uk') }}" class="nav-link">Укр</a>
            </div>
        </nav>
    </div>
</header>