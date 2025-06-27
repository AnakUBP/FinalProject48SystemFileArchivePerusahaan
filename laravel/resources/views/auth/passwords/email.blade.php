<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Karyawan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/auth.css'])
</head>
<body>
    <main>
        <div class="auth-container">
            <div class="auth-card">
                <a href="{{ route('welcome') }}" class="back-button"><i class="fas fa-arrow-left"></i></a>
                <h3 class="auth-title">Forgot Password</h3>
                <p class="auth-subtitle">Kirimkan emailmu yang terdaftar untuk mereset passwordmu</p>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>