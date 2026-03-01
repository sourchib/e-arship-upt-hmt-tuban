<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login E-Arsip - UPT PT dan HMT Tuban</title>
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
    <div class="auth-container">
        <div class="auth-card">
            <img src="{{ asset('assets/img/upt_logo.png') }}" alt="UPT Logo" class="auth-logo">
            <div class="auth-header">
                <h1>Login E-Arsip</h1>
                <p>UPT PT dan HMT Tuban</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success" style="color: green; margin-bottom: 
15px;">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" style="color: red; margin-bottom: 15px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        <i data-lucide="eye" class="input-icon-right" id="togglePassword"></i>
                    </div>
                </div>

                <div class="form-options">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat saya</label>
                    </div>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>

                <button type="submit" class="btn-primary">
                    <i data-lucide="log-in"></i>
                    Login
                </button>
            </form>

            <div class="auth-footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jwt-decode/build/jwt-decode.min.js"></script>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Password visibility toggle
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        if (togglePassword) {
            togglePassword.addEventListener('click', function (e) {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Update icon
                if (type === 'text') {
                    this.setAttribute('data-lucide', 'eye-off');
                } else {
                    this.setAttribute('data-lucide', 'eye');
                }
                lucide.createIcons();
            });
        }
    </script>

    @if (session('token'))
    <script>
        try {
            const token = "{{ session('token') }}";
            const decoded = jwt_decode(token);
            console.log('Decoded JWT:', decoded);
            
            Swal.fire({
                icon: 'info',
                title: 'JWT Token Decoded',
                html: `
                    <div style="text-align: left; font-family: monospace; font-size: 12px; background: #f4f4f4; padding: 10px; border-radius: 5px;">
                        <pre>${JSON.stringify(decoded, null, 2)}</pre>
                    </div>
                `,
                confirmButtonColor: '#00c853',
            });
        } catch (error) {
            console.error('Error decoding token:', error);
        }
    </script>
    @endif

    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000
        });
    </script>
    @endif

    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: "{{ $errors->first() }}",
            confirmButtonColor: '#d33',
        });
    </script>
    @endif
</body>
</html>
