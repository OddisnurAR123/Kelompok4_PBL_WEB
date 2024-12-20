<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Pengguna</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

  <style>
    /* Background image for the login page */
    body {
      background-image: url('../public/login.jpg'); /* Ganti dengan path gambar Anda */
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    /* .login-box {
      background: rgba(255, 255, 255, 0.8); /* Menambahkan sedikit transparansi agar konten tetap terlihat jelas */
      border-radius: 10px;
      padding: 20px;
    } */
  </style>
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="card card-outline card-primary" style="border-radius: 15px; overflow: hidden;">
      <!-- Tambahkan elemen ini di bagian atas -->
      <div class="brand-logo text-center" style="background-color: #f0f0f0; padding: 20px; border-top-left-radius: 10px; border-top-right-radius: 10px;">
          <span style="font-size: 34px; font-family: 'Poppins', sans-serif; font-weight: 700; color: #333333;">IN</span>
          <span style="font-size: 34px; font-family: 'Poppins', sans-serif; font-weight: 700; color: #FF4500;">FOR</span>
          <span style="font-size: 34px; font-family: 'Poppins', sans-serif; font-weight: 700; color: #FFD700;">MS</span>
        </a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Silahkan masuk dengan akun yang telah terdaftar</p>
        <form action="{{ url('/login') }}" method="POST" id="form-login">
          @csrf
          <div class="input-group mb-3">
            <input type="text" id="username" name="username" class="form-control" placeholder="Username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
            <small id="error-username" class="error-text text-danger"></small>
          </div>
          <div class="input-group mb-3">
            <input type="password" id="password" name="password" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
            <small id="error-password" class="error-text text-danger"></small>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">Ingat Saya</label>
              </div>
            </div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </div>
          </div>
        </form>
      </div>
    </div>    
  </div>

  <!-- jQuery -->
  <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- jquery-validation -->
  <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
  <!-- SweetAlert2 -->
  <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      $("#form-login").validate({
        rules: {
          username: { required: true, minlength: 4, maxlength: 20 },
          password: { required: true, minlength: 5, maxlength: 20 }
        },
        submitHandler: function(form) {
          $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize(),
            success: function(response) {
              if (response.status) {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: response.message,
                }).then(function() {
                  window.location = response.redirect;
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Gagal',
                  text: response.message,
                });
              }
            }
          });
        }
      });
    });
  </script>
</body>
</html>
