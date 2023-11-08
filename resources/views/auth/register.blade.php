@extends('layouts.auth')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Register</div>

        <div class="card-body">
          <form method="POST" action="{{ route('post.register') }}" id="register-form">
            @csrf

            <div class="form-group mb-2">
              <label for="name">Nama Lengkap</label>
              <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
              @error('name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
              <div id="name-error" class="error-message"></div> <!-- Menampilkan pesan error nama -->
            </div>

            <div class="form-group mb-2">
              <label for="email">Alamat Email</label>
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
              @error('email')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
              <div id="email-error" class="error-message"></div> <!-- Menampilkan pesan error email -->
            </div>

            <div class="form-group mb-2">
              <label for="password">Kata Sandi</label>
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
              @error('password')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
              <div id="password-error" class="error-message"></div> <!-- Menampilkan pesan error kata sandi -->
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-primary">
                Daftar
              </button>
            </div>

            <hr />

            <div class="mb-2 text-center">
              <span>Sudah memiliki akun? <a href="{{ route('login') }}">masuk disini</a></span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Validasi Form Registrasi
  document.getElementById('register-form').addEventListener('submit', function (e) {
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var nameError = document.getElementById('name-error');
    var emailError = document.getElementById('email-error');
    var passwordError = document.getElementById('password-error');

    if (!name.trim()) {
      e.preventDefault();
      nameError.innerText = 'Nama harus diisi';
    } else {
      nameError.innerText = '';
    }

    if (!email.trim()) {
      e.preventDefault();
      emailError.innerText = 'Alamat Email harus diisi';
    } else {
      emailError.innerText = '';
    }

    if (password.length < 6) {
      e.preventDefault();
      passwordError.innerText = 'Kata Sandi harus memiliki minimal 6 karakter';
    } else {
      passwordError.innerText = '';
    }
  });

  // Validasi Real-time pada Form Registrasi
  document.getElementById('password').addEventListener('input', function () {
    var password = this.value;
    var passwordError = document.getElementById('password-error');

    if (password.length < 6) {
      passwordError.innerText = 'Kata Sandi harus memiliki minimal 6 karakter';
    } else {
      passwordError.innerText = '';
    }
  });
</script>
@endsection
