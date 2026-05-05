<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f9fafb;
        }
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }
        .status-hadir {
            background-color: #22c55e;
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
        }
        .status-tidak-hadir {
            background-color: #ef4444;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

@if(!isset($no_navbar)) @include('components.navbar') @endif

<main>
    @yield('content')
</main>

@include('components.footer')

</body>
</html>
