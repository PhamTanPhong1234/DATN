<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    @extends('frontend/layouts/app-user')

    @section('main')
    <section>
        <div class="login-container">
            <h2 class="text-center mb-4">Đăng nhập</h2>
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input id="password" class="form-control" type="password" name="password" required>
                    @if($errors->has('password'))
                    <div class="text-danger mt-2">{{ $errors->first('password') }}</div>
                    @endif
                </div>
                <div class="mb-3 form-check">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label class="form-check-label" for="remember_me">Ghi nhớ đăng nhập</label>
                </div>
                <div class="mb-3">
                    <a class="btn btn-link p-0" href="{{ route('register') }}">Bạn chưa có tài khoản?</a>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    @if (Route::has('password.request'))
                    <a class="text-decoration-none" href="{{ route('password.request') }}">Quên mật khẩu?</a>
                    @endif

                    <div>
                        <button type="submit" class="btn btn-primary">Đăng nhập</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    @endsection

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>