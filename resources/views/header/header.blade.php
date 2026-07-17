<nav class="navbar navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark">
    <div class="container-fluid">
        <div class="navbar-brand">勤怠管理システム</div>

        <div class="collapse navbar-collapse" id="Navber">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link @yield('active-kintai_master')" href="#">勤怠管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @yield('active-kintai_entry')" href="#">勤怠管理</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle @yield('active-master')" role="button"  data-bs-toggle="dropdown" aria-expanded="false">
                        マスタ管理
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item @yield('active-employee_master')" href="/employee_api">社員マスタ管理</a></li>
                        <li><a class="dropdown-item @yield('active-holiday_master')"  href="#">祝日マスタ管理</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/login">
                        ログアウト
                    </a>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
