<header class="main-header">
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul id="app_components" class="nav navbar-nav">
                <notification-componet user_id="{{ auth()->user()->id }}"></notification-componet>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="user-name hidden-xs">
                            {{ Auth::user()->name }}
                        </span>
                        <div class="roles-top">
                            @foreach (Auth::user()->roles as $role)
                            <em>({{ $role->name }}) | </em>
                            @endforeach
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->                        <!-- Menu Footer-->
                        <li>
                            <a href="#" class=""><i class="fas fa-key"></i> Cambiar contraseña</a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" class=""><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
