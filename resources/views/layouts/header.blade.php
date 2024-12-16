<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
  
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto d-flex align-items-center">
      <!-- User Name, Role, and Profile Picture -->
      <li class="nav-item d-flex align-items-center">
          <a href="{{ url('/profile') }}" class="nav-link d-flex align-items-center" style="text-decoration: none;">
              <span class="mr-2" style="font-size: 14px;">
                  {{ Auth::user()->nama_pengguna }} | {{ Auth::user()->jenisPengguna->nama_jenis_pengguna }}
              </span>
              <!-- Profile Picture -->
              @if (auth()->user()->foto_profil)
                  <img 
                      src="{{ asset('storage/profile/' . auth()->user()->foto_profil) }}" 
                      alt="Foto Profil" 
                      class="rounded-circle" 
                      style="width: 30px; height: 20px; object-fit: cover;">
              @else
                  <div class="placeholder-profile text-white d-flex align-items-center justify-content-center rounded-circle shadow-sm mb-3" 
                       style="width: 30px; height: 30px; background-color: #01274E;">
                      <span class="text-center" style="font-size: 14px;">{{ strtoupper(substr(Auth::user()->nama_pengguna, 0, 1)) }}</span>
                  </div>
              @endif
          </a>
      </li>
    </ul>
  </nav>  