<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Point Of Sales System</title>
    <link rel="stylesheet" href="{{('css/app.css')}}">
    <script src="{{asset('js/app.js')}}"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{('css/bootstrap.min.css')}}">

    <!-- jQuery library -->
    <script src="{{asset('js/jquery.min.js')}}"></script>

    <!-- Latest compiled JavaScript -->
    <script src="{{asset('js/bootstrap.min.js')}}"></script>

    <!-- Select2 js ans css -->
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet" />
    <script src="{{asset('js/select2.min.js')}}"></script>

<body>
        <div>
               @yield('content')
        </div>
</body>
</html>