  @php
      $activeMenu = $activeMenu ?? '';
  @endphp

  <div class="sidebar" style="background: linear-gradient(180deg, #1e293b, #1e293b); color: white;">
    
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
      
      <!-- Manajemen Kategori Kegiatan -->
        <li class="nav-item">
          <a href="{{ url('/kategori_kegiatan') }}" class="nav-link {{ ($activeMenu == 'manajemen-kategori-kegiatan') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'manajemen-kategori-kegiatan') ? '#3b82f6' : '#ffffff' }};">
            <i class="nav-icon fas fa-tasks"></i>
            <p>Kategori Kegiatan</p>
          </a>
        </li>

        <!-- Manajemen Jenis Pengguna -->
        <li class="nav-item">
          <a href="{{ url('/jenis_pengguna') }}" class="nav-link {{ ($activeMenu == 'manajemen-jenis-pengguna') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'manajemen-jenis-pengguna') ? '#3b82f6' : '#ffffff' }};">
            <i class="nav-icon fas fa-user-tag"></i>
            <p>Jenis Pengguna</p>
          </a>
        </li>

        <!-- Manajemen Jabatan Kegiatan -->
        <li class="nav-item">
          <a href="{{ url('/jabatan_kegiatan') }}" class="nav-link {{ ($activeMenu == 'manajemen-jabatan-kegiatan') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'manajemen-jabatan-kegiatan') ? '#3b82f6' : '#ffffff' }};">
            <i class="nav-icon fas fa-user-shield"></i>
            <p>Jabatan Kegiatan</p>
          </a>
        </li>

        <!-- Manajemen Data Pengguna -->
        <li class="nav-item">
          <a href="{{ url('/pengguna') }}" class="nav-link {{ ($activeMenu == 'pengguna') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'pengguna') ? '#3b82f6' : '#ffffff' }};">
            <i class="nav-icon fas fa-users"></i>
            <p>Data Pengguna</p>
          </a>
        </li>
        
        <!-- Manajemen Kegiatan -->
        <li class="nav-item {{ ($activeMenu == 'manajemen-kegiatan') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ ($activeMenu == 'manajemen-kegiatan') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'manajemen-kegiatan') ? '#3b82f6' : '#ffffff' }};">
            <i class="nav-icon fas fa-tasks"></i>
            <p>Kegiatan<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/kegiatan') }}" class="nav-link {{ ($activeMenu == 'kegiatan') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'kegiatan') ? '#3b82f6' : '#ffffff' }};">
                <i class="far fa-calendar-alt nav-icon"></i>
                <p>Tambah Kegiatan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/agenda') }}" class="nav-link {{ ($activeMenu == 'agenda-kegiatan') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'agenda-kegiatan') ? '#3b82f6' : '#ffffff' }};">
                <i class="far fa-calendar-alt nav-icon"></i>
                <p>Agenda Kegiatan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/detail_kegiatan') }}" class="nav-link {{ ($activeMenu == 'progres-kegiatan') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'progres-kegiatan') ? '#3b82f6' : '#ffffff' }};">
                <i class="far fa-chart-bar nav-icon"></i>
                <p>Laporan Progres Kegiatan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/kegiatan_eksternal') }}" class="nav-link {{ ($activeMenu == 'kegiatan_eksternal') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'kegiatan_eksternal') ? '#3b82f6' : '#ffffff' }};">
                <i class="fas fa-external-link-alt nav-icon"></i>
                <p>Kegiatan Eksternal</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Daftar Kegiatan -->
        <li class="nav-item {{ ($activeMenu == 'daftar-kegiatan') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ ($activeMenu == 'daftar-kegiatan') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'daftar-kegiatan') ? '#3b82f6' : '#ffffff' }};">
            <i class="nav-icon fas fa-list"></i>
            <p>Daftar Kegiatan<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/laporkan-progres-agenda') }}" class="nav-link {{ ($activeMenu == 'laporkan-progres-agenda') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'laporkan-progres-agenda') ? '#3b82f6' : '#ffffff' }};">
                <i class="fas fa-clipboard-check nav-icon"></i>
                <p>Laporan Progres Agenda</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/detail-kegiatan') }}" class="nav-link {{ ($activeMenu == 'detail-kegiatan') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'detail-kegiatan') ? '#3b82f6' : '#ffffff' }};">
                <i class="fas fa-info-circle nav-icon"></i>
                <p>Detail Kegiatan</p>
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

        <!-- Dokumen Draft Surat Tugas -->
        <li class="nav-item">
          <a href="{{ url('/draft_surat_tugas') }}" class="nav-link {{ ($activeMenu == 'draft_surat_tugas') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'draft_surat_tugas') ? '#3b82f6' : '#ffffff' }};">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>Draft Surat Tugas</p>
          </a>
        </li>

        <!-- Unggah Surat Tugas -->
        <li class="nav-item">
          <a href="{{ url('/unggah-surat-tugas') }}" class="nav-link {{ ($activeMenu == 'unggah-surat-tugas') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'unggah-surat-tugas') ? '#3b82f6' : '#ffffff' }};">
            <i class="nav-icon fas fa-upload"></i>
            <p>Unggah Surat Tugas</p>
          </a>
        </li>

        <!-- Pengaturan Profil -->
        <li class="nav-item">
          <a href="{{ url('/pengaturan-profil') }}" class="nav-link {{ ($activeMenu == 'pengaturan-profil') ? 'active' : '' }}" style="color: {{ ($activeMenu == 'pengaturan-profil') ? '#3b82f6' : '#ffffff' }};">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>Pengaturan Profil</p>
          </a>
        </li>

        <!-- Keluar -->
        <li class="nav-item">
          <a href="{{ url('/logout') }}" class="nav-link" style="color: #f87171;">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Keluar</p>
          </a>
        </li>

    </ul>
  </div>
  <style>
    .nav-link:hover {
      background-color: #4b5563;
      color: #3b82f6 !important;
      transition: all 0.3s ease;
  }

  .modal {
      z-index: 1050; /* Atur z-index lebih besar jika diperlukan */
  }
  .modal-backdrop {
      z-index: 1049; /* Background modal */
  }
  </style>