<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - E-Arsip</title>
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
                <h1>Lupa Password?</h1>
                <p>Masukkan email Anda untuk menerima instruksi pemulihan.</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success" style="color: #059669; background: #ecfdf5; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: left; border: 1px solid #10b981;">
                    <i data-lucide="check-circle" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 8px;"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" style="color: #dc2626; background: #fef2f2; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: left; border: 1px solid #ef4444;">
                    @foreach ($errors->all() as $error)
                        <p><i data-lucide="alert-circle" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 8px;"></i> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                </div>

                <button type="submit" class="btn-primary">
                    <i data-lucide="send"></i>
                    Kirim Email Pemulihan
                </button>
            </form>

            <div class="auth-footer" style="margin-top: 30px;">
                <a href="{{ route('login') }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; color: #64748b; font-weight: 500;">
                    <i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i>
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>
