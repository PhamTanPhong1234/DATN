<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .register-container {
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
        <div class="register-container">
            <h2 class="text-center mb-4">Đăng ký</h2>
    
            <form method="POST" action="{{ route('register') }}">
                @csrf
    
                <!-- Tên -->
                <div class="mb-3">
                    <label for="name" class="form-label">Tên</label>
                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    @if($errors->has('name'))
                        <div class="text-danger mt-2">{{ $errors->first('name') }}</div>
                    @endif
                </div>
    
                <!-- Địa chỉ Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required>
                    @if($errors->has('email'))
                        <div class="text-danger mt-2">{{ $errors->first('email') }}</div>
                    @endif
                </div>
    
                <!-- Mật khẩu -->
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input id="password" class="form-control" type="password" name="password" required>
                    @if($errors->has('password'))
                        <div class="text-danger mt-2">{{ $errors->first('password') }}</div>
                    @endif
                </div>
    
                <!-- Xác nhận Mật khẩu -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Xác nhận Mật khẩu</label>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
                    @if($errors->has('password_confirmation'))
                        <div class="text-danger mt-2">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>
    
                <!-- Đã có tài khoản -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a class="text-decoration-none" href="{{ route('login') }}">Đã có tài khoản?</a>
    
                    <button type="submit" class="btn btn-primary">Đăng ký</button>
                </div>
            </form>
        </div>
    </section>
    @endsection

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
