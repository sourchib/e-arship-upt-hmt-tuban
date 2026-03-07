<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - E-Arsip</title>
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
                <h1>Reset Password</h1>
                <p>Silakan buat password baru untuk akun Anda.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" style="color: #dc2626; background: #fef2f2; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: left; border: 1px solid #ef4444;">
                    @foreach ($errors->all() as $error)
                        <p><i data-lucide="alert-circle" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 8px;"></i> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required readonly>
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password baru" required autofocus>
                        <i data-lucide="eye" class="input-icon-right toggle-password" data-target="password"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <div class="input-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                        <i data-lucide="eye" class="input-icon-right toggle-password" data-target="password_confirmation"></i>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    <i data-lucide="save"></i>
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Password visibility toggle
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);
                const type = targetInput.getAttribute('type') === 'password' ? 'text' : 'password';
                targetInput.setAttribute('type', type);
                
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
</body>
</html>
