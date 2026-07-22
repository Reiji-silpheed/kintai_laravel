<nav class="navbar navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark">
    <div class="container-fluid">
        <div class="navbar-brand">勤怠管理システム</div>

        <div class="collapse navbar-collapse" id="Navber">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if((int)session('role_cd')===1)
                    <li class="nav-item">
                        <a class="nav-link @yield('active-kintai_master')" href="/kintai_master">勤怠管理</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link @yield('active-kintai_entry')" href="/kintai_entry_api">勤怠入力</a>
                </li>
                @if((int)session('role_cd')===1)
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle @yield('active-master')" role="button"  data-bs-toggle="dropdown" aria-expanded="false">
                            マスタ管理
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item @yield('active-employee_master')" href="/employee_api">社員マスタ管理</a></li>
                            <li><a class="dropdown-item @yield('active-holiday_master')"  href="/holiday">祝日マスタ管理</a></li>
                        </ul>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        ログアウト
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
