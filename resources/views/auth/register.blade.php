<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun - E-Arsip UPT PT dan HMT Tuban</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/upt_logo.png') }}">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="auth-container register">
        <div class="auth-card">
            <img src="{{ asset('assets/img/upt_logo.png') }}" alt="UPT Logo" class="auth-logo">
            <div class="auth-header">
                <h1>Registrasi Akun</h1>
                <p>Ajukan Akses E-Arsip UPT PT dan HMT Tuban</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" style="color: red; margin-bottom: 20px; padding: 15px; background: #fee2e2; border-radius: 8px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap *</label>
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('nama') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="instansi">Instansi/Unit Kerja *</label>
                        <input type="text" id="instansi" name="instansi" class="form-control" placeholder="Nama instansi" value="{{ old('instansi') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role *</label>
                        <select id="role" name="role" class="form-control" required>
                            <!-- <option value="Operator" {{ old('role') == 'Operator' ? 'selected' : '' }}>Operator</option> -->
                            <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <!-- <option value="Pimpinan" {{ old('role') == 'Pimpinan' ? 'selected' : '' }}>Pimpinan</option> -->
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                            <i data-lucide="eye" class="input-icon-right toggle-password" data-target="password"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password *</label>
                        <div class="input-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                            <i data-lucide="eye" class="input-icon-right toggle-password" data-target="password_confirmation"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms" class="terms-text">Saya menyetujui <a href="#">syarat dan ketentuan</a> penggunaan sistem E-Arsip</label>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    <i data-lucide="user-plus"></i>
                    Daftar Sekarang
                </button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Password visibility toggle
        document.querySelectorAll('.toggle-password').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Update icon
                if (type === 'text') {
                    this.setAttribute('data-lucide', 'eye-off');
                } else {
                    this.setAttribute('data-lucide', 'eye');
                }
                lucide.createIcons();
            });
        });
    </script>
    </script>

    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6',
        });
    </script>
    @endif

    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ $errors->first() }}",
            confirmButtonColor: '#d33',
        });
    </script>
    @endif
</body>
</html>
