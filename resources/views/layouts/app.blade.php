<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Quicksand', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-white">

@if(!isset($no_navbar)) @include('components.navbar') @endif

<main>
    @yield('content')
</main>

@include('components.footer')

</body>
</html>