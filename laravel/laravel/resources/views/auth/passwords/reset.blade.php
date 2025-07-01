<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Karyawan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Memuat CSS khusus halaman autentikasi --}}
    @vite(['resources/css/auth.css'])
</head>
<body>
    <main>
<div class="auth-container">
    <div class="auth-card">
        <h3 class="auth-title">Reset Password</h3>
        <p class="auth-subtitle">Please set your new password.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            {{-- Token dan email ini wajib ada --}}
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

            <div class="form-group">
                <label for="password">New Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary">
                Create
            </button>
        </form>
    </div>
</div>
    </main>
</body>
</html>