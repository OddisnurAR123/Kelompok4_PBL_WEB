    @php
        $activeMenu = $activeMenu ?? '';
    @endphp

    <div class="sidebar" style="background-color: #1e293b; color: white;">
        <!-- Sidebar Search Form -->
        <div class="form-inline mt-2">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar" style="background: #4b5563; color: #ffffff;">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="nav nav-pills nav-sidebar flex-column mt-3" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ ($activeMenu == 'dashboard') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'dashboard') ? '#3b82f6' : '#ffffff' }};">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <!-- Data Master -->
            <li class="nav-item {{ str_contains($activeMenu, 'data-master') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ str_contains($activeMenu, 'data-master') ? 'active' : '' }}" style="color: {{ str_contains($activeMenu, 'data-master') ? '#3b82f6' : '#ffffff' }};">
                    <i class="nav-icon fas fa-database"></i>
                    <p>
                        Data Master
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/jenis_pengguna') }}" class="nav-link {{ ($activeMenu == 'data-master-jenis-pengguna') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'data-master-jenis-pengguna') ? '#3b82f6' : '#ffffff' }};">
                            <i class="nav-icon fas fa-user-tag"></i>
                            <p>Jenis Pengguna</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/pengguna') }}" class="nav-link {{ ($activeMenu == 'data-master-pengguna') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'data-master-pengguna') ? '#3b82f6' : '#ffffff' }};">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Data Pengguna</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/kategori_kegiatan') }}" class="nav-link {{ ($activeMenu == 'data-master-kategori-kegiatan') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'data-master-kategori-kegiatan') ? '#3b82f6' : '#ffffff' }};">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>Kategori Kegiatan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/jabatan_kegiatan') }}" class="nav-link {{ ($activeMenu == 'data-master-jabatan-kegiatan') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'data-master-jabatan-kegiatan') ? '#3b82f6' : '#ffffff' }};">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>Jabatan Kegiatan</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Statistik Kinerja -->
            <li class="nav-item">
                <a href="{{ url('/statistik-kinerja') }}" class="nav-link {{ ($activeMenu == 'statistik-kinerja') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'statistik-kinerja') ? '#3b82f6' : '#ffffff' }};">
                    <i class="nav-icon fas fa-chart-line"></i>
                    <p>Statistik Kinerja</p>
                </a>
            </li>

            <!-- Kegiatan -->
            <li class="nav-item">
                <a href="{{ url('/kegiatan') }}" class="nav-link {{ ($activeMenu == 'kegiatan') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'kegiatan') ? '#3b82f6' : '#ffffff' }};">
                    <i class="nav-icon fas fa-calendar-alt"></i>
                    <p>Kegiatan</p>
                </a>
            </li>
        </ul>
    </div>

