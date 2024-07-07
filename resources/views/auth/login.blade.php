<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login System</title>
    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}" />
    <link rel="icon" type="image/png" sizes="2x3" href="assets/images/logo/icon.png">
</head>

<body>
    <div id="auth" class="d-flex justify-content-center align-items-center">
        <div class="row text-center">
            <div id="auth-left">
                <img class="w-25" src="{{ asset('assets/images/logo/icon.png') }}" alt="">
                <br><br><br><br>

                <div class="container text-center">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            @if (Session::has('error'))
                            <div class="alert alert-danger">
                                <strong>{{ Session::get('error') }}</strong>
                            </div>
                            @endif
                            <form action="{{ route('loginPost') }}" method="POST">
                                @csrf
                                <div class="form-group position-relative has-icon-left mb-4">
                                    <input type="email" class="form-control form-control-l" placeholder="Email"
                                        name="email" />
                                    <div class="form-control-icon">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <div class="form-group position-relative has-icon-left mb-4">
                                    <input type="password" class="form-control form-control-l" placeholder="Password"
                                        name="password" />
                                    <div class="form-control-icon">
                                        <i class="bi bi-shield-lock"></i>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-block btn-l shadow-lg mt-3" type="submit">
                                    Log in
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session()->has('success'))
<script>
    Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            showConfirmButton: true,
            timer: 2000
        });
</script>
@endif

</html>