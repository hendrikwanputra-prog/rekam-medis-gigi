<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>OQ Clinic Dentist</title>

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/logooq.png')}}">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('vendor/toastr/css/toastr.min.css')}}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url("{{ asset('images/bg-login.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Poppins', sans-serif;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            background: #213266;
            border-radius: 18px;
            padding: 42px 40px;
            box-shadow: 0 25px 60px rgba(33, 50, 102, 0.25);
        }

        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 34px;
        }

        .login-logo .logo-icon {
            width: 62px;
            height: auto;
        }

        .login-logo .logo-text {
            width: 118px;
            height: auto;
        }

        .login-label {
            color: #FFFFFF;
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .login-input {
            height: 50px;
            border-radius: 8px;
            border: none;
            background: #FFFFFF;
            color: #1E293B;
            font-weight: 500;
            padding: 12px 15px;
        }

        .login-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
            border: none;
        }

        .login-button {
            width: 100%;
            height: 52px;
            border-radius: 30px;
            border: none;
            background: #2F52B5;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 15px;
            margin-top: 10px;
            transition: 0.2s ease-in-out;
        }

        .login-button:hover {
            background: #243F8F;
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(47, 82, 181, 0.35);
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="h-100">
    <div class="login-wrapper">
        <div class="login-card">

            <div class="login-logo">
                <img class="logo-icon" src="{{asset('images/logooq.png')}}" alt="OQ Clinic">
                <img class="logo-text" src="{{asset('images/logotextoq.png')}}" alt="OQ Clinic Dentist">
            </div>

            <form action="{{Route('login.auth')}}" method="POST">
                {{ csrf_field() }}

                <div class="form-group">
                    <label class="login-label">No.Telp / HP</label>
                    <input type="number" class="form-control login-input" placeholder="Masukkan nomor HP" value="" required name="phone">
                </div>

                <div class="form-group">
                    <label class="login-label">Password</label>
                    <input type="password" class="form-control login-input" placeholder="Masukkan password" value="" required name="password">
                </div>

                <div class="text-center">
                    <button type="submit" class="login-button">Masuk</button>
                </div>
            </form>

        </div>
    </div>

    <script src="{{asset('vendor/global/global.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('js/custom.min.js')}}"></script>
    <script src="{{asset('js/deznav-init.js')}}"></script>
    <script src="{{asset('vendor/toastr/js/toastr.min.js')}}"></script>

    <script>
        @if(Session::has('sukses'))
            toastr.success("{{Session::get('sukses')}}", "Sukses",{timeOut: 5000})
        @endif
        @if(Session::has('gagal')) 
            toastr.error("{{Session::get('gagal')}}", "Gagal",{timeOut: 5000})
        @endif
    </script>

</body>

</html>