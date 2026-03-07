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

                <div class="form-group">
                    <label for="captcha">Verifikasi Keamanan</label>
                    <div class="captcha-box" style="display: flex; gap: 10px; align-items: center; margin-bottom: 8px;">
                        <div class="captcha-img-wrapper" id="captcha-img" style="flex: 1; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: #fff; height: 50px; display: flex; align-items: center; justify-content: center;">
                            {!! captcha_img('flat') !!}
                        </div>
                        <button type="button" class="btn-refresh" id="refresh-captcha" style="width: 45px; height: 50px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                            <i data-lucide="refresh-cw" style="width: 18px; height: 18px;"></i>
                        </button>
                    </div>
                    <input type="text" id="captcha" name="captcha" class="form-control" placeholder="Ketik kode di atas" required>
                    @error('captcha')
                        <small class="text-danger" style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</small>
                    @enderror
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

        // Captcha Refresh
        const refreshBtn = document.querySelector('#refresh-captcha');
        const captchaBox = document.querySelector('#captcha-img');

        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                icon.classList.add('loading-spin');
                this.style.opacity = '0.5';
                this.style.pointerEvents = 'none';

                fetch('/captcha/api/flat')
                    .then(response => response.json())
                    .then(data => {
                        captchaBox.innerHTML = `<img src="${data.img}" alt="captcha" style="max-height: 100%; width: auto;">`;
                    })
                    .catch(err => console.error('Error refreshing captcha:', err))
                    .finally(() => {
                        icon.classList.remove('loading-spin');
                        this.style.opacity = '1';
                        this.style.pointerEvents = 'all';
                    });
            });
        }
    </script>
    <style>
        .captcha-img-wrapper img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .loading-spin {
            animation: spin 1s linear infinite;
        }
        .btn-refresh:hover {
            background: #f1f5f9 !important;
            color: #0f172a !important;
            border-color: #cbd5e1 !important;
        }
    </style>

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
