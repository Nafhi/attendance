<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel d-flex">
        <div class="image">
            {{-- <img src="https://avatars.dicebear.com/api/bottts/example.svg?options%5Bcolors%5D%5B%5D=cyan"
                class="img-circle elevation-2" alt="User Image"> --}}
        </div>
        {{-- <div class="info">
            <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div> --}}
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="{{ url('/home') }}" class="nav-link {{ request()->routeIs('home') ? 'custom-active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/attendance') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'custom-active' : '' }}">
                    <i class="nav-icon far fa-calendar-alt"></i>
                    <p>
                        Attendance
                    </p>
                </a>
            </li>
            {{-- <li class="nav-item">
                <a href="{{ url('/shift') }}" class="nav-link {{ request()->routeIs('shift.*') ? 'custom-active' : '' }}">
                    <i class="nav-icon far fa-clock"></i>
                    <p>
                        Shift
                    </p>
                </a>
            </li> --}}
            <li class="nav-item">
                <a href="{{ url('/user') }}" class="nav-link {{ request()->routeIs('user.*') ? 'custom-active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                        Users
                    </p>
                </a>
            </li>
            <li class="nav-header">OTHER</li>
            <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                    <i class="nav-icon far fa-circle text-danger"></i>
                    <p class="text">Logout</p>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
