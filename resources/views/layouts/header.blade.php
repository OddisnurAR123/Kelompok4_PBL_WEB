<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
      <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
          <a href="{{ url('/') }}" class="nav-link">Dashboard</a>
      </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-bell"></i>
              <span class="badge badge-warning navbar-badge">
                  {{ count($newKegiatan ?? []) + count($newAgenda ?? []) }}
              </span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              @if(count($newKegiatan ?? []) + count($newAgenda ?? []) > 0)
                  <span class="dropdown-item dropdown-header">
                      {{ count($newKegiatan ?? []) + count($newAgenda ?? []) }} Notifikasi
                  </span>

                  <!-- Notifikasi Kegiatan -->
                  @if(!empty($newKegiatan))
                      @foreach($newKegiatan as $kegiatan)
                          <a href="#" class="dropdown-item">
                              <i class="fas fa-calendar-alt mr-2"></i> {{ $kegiatan->nama_kegiatan }}
                              <span class="float-right text-muted text-sm">{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->diffForHumans() }}</span>
                          </a>
                          <div class="dropdown-divider"></div>
                      @endforeach
                  @endif

                  <!-- Notifikasi Agenda -->
                  @if(!empty($newAgenda))
                      @foreach($newAgenda as $agenda)
                          <a href="#" class="dropdown-item">
                              <i class="fas fa-calendar-check mr-2"></i> {{ $agenda->nama_agenda }}
                              <span class="float-right text-muted text-sm">{{ \Carbon\Carbon::parse($agenda->tanggal_agenda)->diffForHumans() }}</span>
                          </a>
                          <div class="dropdown-divider"></div>
                      @endforeach
                  @endif

                  <a href="{{ url('/notifikasi') }}" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
              @else
                  <span class="dropdown-item dropdown-header">Tidak Ada Notifikasi Terbaru</span>
              @endif
          </div>
      </li>

      <!-- User Profile Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            @if(Auth::user() && Auth::user()->foto_profil)
                <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" 
                     alt="Profile Picture" 
                     class="img-circle elevation-2" 
                     style="width: 30px; height: 30px;">
            @else
                <i class="far fa-user"></i>
            @endif
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ route('profile.profil') }}" class="dropdown-item">
                <i class="fas fa-user-cog mr-2"></i> Pengaturan Profil
            </a>
            <div class="dropdown-divider"></div>
            <a href="{{ url('/logout') }}" class="dropdown-item">
                <i class="fas fa-sign-out-alt mr-2"></i> Keluar
            </a>
        </div>
    </li>    
  </ul>
</nav>

