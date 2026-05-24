<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin Posyandu')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --blue: #2563eb;
      --blue-2: #06b6d4;
      --pink: #ec4899;
      --pink-2: #f472b6;
      --dark: #0f172a;
      --muted: #64748b;
      --bg: #f8fafc;
      --white: #ffffff;
      --line: #e2e8f0;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --shadow: 0 20px 60px rgba(15, 23, 42, .12);
      --radius: 22px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Poppins', sans-serif;
      background: radial-gradient(circle at top left, rgba(37, 99, 235, .18), transparent 34%),
                  radial-gradient(circle at top right, rgba(236, 72, 153, .18), transparent 30%),
                  var(--bg);
      color: var(--dark);
      min-height: 100vh;
    }
    button, input, select, textarea { font: inherit; }
    button { cursor: pointer; border: 0; }
    a { text-decoration: none; color: inherit; }

    .app { display: flex; min-height: 100vh; }

    /* Sidebar Styles */
    .sidebar {
      width: 292px;
      position: fixed;
      inset: 18px auto 18px 18px;
      border-radius: 30px;
      color: white;
      background: linear-gradient(160deg, rgba(37, 99, 235, .98), rgba(124, 58, 237, .94) 50%, rgba(236, 72, 153, .96)),
                  linear-gradient(#111827, #111827);
      box-shadow: var(--shadow);
      padding: 20px;
      z-index: 20;
      overflow: hidden;
    }
    .sidebar::before {
      content: "";
      position: absolute;
      width: 220px;
      height: 220px;
      border-radius: 50%;
      background: rgba(255,255,255,.12);
      right: -80px;
      top: -90px;
    }
    .sidebar::after {
      content: "";
      position: absolute;
      width: 170px;
      height: 170px;
      border-radius: 50%;
      background: rgba(255,255,255,.1);
      left: -80px;
      bottom: -70px;
    }
    .brand {
      position: relative;
      z-index: 1;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 8px 6px 22px;
      border-bottom: 1px solid rgba(255,255,255,.18);
      margin-bottom: 18px;
    }
    .brand-logo {
      width: 48px;
      height: 48px;
      border-radius: 18px;
      display: grid;
      place-items: center;
      background: rgba(255,255,255,.2);
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.18);
      backdrop-filter: blur(10px);
      font-size: 24px;
    }
    .brand h1 {
      font-size: 18px;
      line-height: 1.1;
      letter-spacing: -.02em;
    }
    .brand span {
      display: block;
      font-size: 12px;
      opacity: .8;
      margin-top: 4px;
      font-weight: 500;
    }
    .nav { position: relative; z-index: 1; display: grid; gap: 8px; }
    .nav-btn {
      width: 100%;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 13px 14px;
      border-radius: 18px;
      color: rgba(255,255,255,.86);
      background: transparent;
      transition: .25s ease;
      text-align: left;
      font-weight: 700;
    }
    .nav-btn small {
      display: block;
      font-size: 11px;
      font-weight: 500;
      opacity: .68;
      margin-top: 2px;
    }
    .nav-btn .ico {
      width: 34px;
      height: 34px;
      border-radius: 13px;
      display: grid;
      place-items: center;
      background: rgba(255,255,255,.14);
      flex: 0 0 auto;
    }
    .nav-btn:hover,
    .nav-btn.active {
      background: rgba(255,255,255,.18);
      color: white;
      transform: translateX(4px);
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.16);
    }
    .sidebar-footer {
      position: absolute;
      z-index: 1;
      left: 20px;
      right: 20px;
      bottom: 20px;
      padding: 16px;
      border-radius: 20px;
      background: rgba(255,255,255,.14);
      backdrop-filter: blur(12px);
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.14);
    }
    .sidebar-footer strong {
      display: block;
      font-size: 13px;
      margin-bottom: 5px;
    }
    .sidebar-footer span {
      display: block;
      font-size: 12px;
      opacity: .78;
      line-height: 1.45;
    }

    /* Main Content Styles */
    .main {
      width: 100%;
      margin-left: 328px;
      padding: 22px 28px 42px;
    }
    .topbar {
      position: sticky;
      top: 0;
      z-index: 10;
      margin-bottom: 24px;
      padding: 14px;
      border-radius: 24px;
      background: rgba(255,255,255,.76);
      backdrop-filter: blur(18px);
      box-shadow: 0 12px 40px rgba(15, 23, 42, .08);
      border: 1px solid rgba(226, 232, 240, .8);
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 14px;
    }
    .mobile-menu {
      display: none;
      width: 44px;
      height: 44px;
      border-radius: 15px;
      background: linear-gradient(135deg, var(--blue), var(--pink));
      color: white;
      font-size: 20px;
    }
    .page-title h2 {
      font-size: clamp(22px, 3vw, 34px);
      letter-spacing: -.04em;
      line-height: 1.1;
    }
    .page-title p {
      color: var(--muted);
      font-size: 14px;
      margin-top: 5px;
    }
    .admin-profile {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: max-content;
    }
    .admin-avatar {
      width: 44px;
      height: 44px;
      border-radius: 16px;
      background: linear-gradient(135deg, var(--blue-2), var(--pink));
      display: grid;
      place-items: center;
      color: white;
      font-weight: 900;
      box-shadow: 0 12px 28px rgba(236, 72, 153, .25);
    }
    .admin-profile strong { display: block; font-size: 14px; }
    .admin-profile span { display: block; font-size: 12px; color: var(--muted); }

    /* Card Styles (matching Contoh DB admin) */
    .card {
      background: rgba(255,255,255,.88);
      border: 1px solid rgba(226, 232, 240, .9);
      border-radius: var(--radius);
      box-shadow: 0 16px 48px rgba(15, 23, 42, .08);
      padding: 20px;
      overflow: hidden;
    }
    .card-dark {
      background: #1f2937;
      border: 1px solid #374151;
    }
    .gradient-text {
      background: linear-gradient(135deg, #3b82f6 0%, #ec4899 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    /* Responsive */
    @media (max-width: 860px) {
      .sidebar {
        transform: translateX(-115%);
        transition: .28s ease;
        inset: 12px auto 12px 12px;
        width: min(310px, calc(100vw - 24px));
      }
      body.sidebar-open .sidebar { transform: translateX(0); }
      .overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .35);
        z-index: 15;
      }
      body.sidebar-open .overlay { display: block; }
      .main { margin-left: 0; padding: 12px; }
      .mobile-menu { display: grid; place-items: center; flex: 0 0 auto; }
      .topbar { border-radius: 22px; }
      .admin-profile > div:not(.admin-avatar) { display: none; }
      .sidebar-footer {
        position: relative;
        left: auto;
        right: auto;
        bottom: auto;
        margin-top: 16px;
      }
    }
  </style>
</head>
<body>
  <div class="overlay" id="overlay"></div>
  <div class="app">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div class="brand">
        <div class="brand-logo">💙</div>
        <div>
          <h1>Admin Posyandu</h1>
          <span>Sistem Manajemen Kesehatan</span>
        </div>
      </div>
      <nav class="nav" id="nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-btn {{ request()->is('admin/dashboard') ? 'active' : '' }}">
          <span class="ico">🏠</span>
          <span>Dashboard<small>/admin/dashboard</small></span>
        </a>
        <a href="{{ route('admin.jadwal') }}" class="nav-btn {{ request()->is('admin/jadwal*') ? 'active' : '' }}">
          <span class="ico">📅</span>
          <span>Kelola Jadwal<small>Schedule Management</small></span>
        </a>
        <a href="{{ route('admin.kader') }}" class="nav-btn {{ request()->is('admin/kader*') ? 'active' : '' }}">
          <span class="ico">👩‍⚕️</span>
          <span>Kelola Kader<small>Kader Management</small></span>
        </a>
        <a href="{{ route('admin.informasi') }}" class="nav-btn {{ request()->is('admin/informasi*') ? 'active' : '' }}">
          <span class="ico">🔍</span>
          <span>Cari Informasi<small>Data Anak & Ibu Hamil</small></span>
        </a>
        <a href="{{ route('admin.kms') }}" class="nav-btn {{ request()->is('admin/kms*') ? 'active' : '' }}">
          <span class="ico">📊</span>
          <span>KMS Analytics<small>Analisis Status Gizi</small></span>
        </a>
        <a href="{{ route('admin.artikel') }}" class="nav-btn {{ request()->is('admin/artikel*') ? 'active' : '' }}">
          <span class="ico">📰</span>
          <span>Kelola Artikel<small>Article Management</small></span>
        </a>
      </nav>
      <div class="sidebar-footer">
        <strong>Login sebagai Admin</strong>
        <span>{{ Auth::user()->name ?? 'Admin Posyandu' }}</span>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main">
      <header class="topbar">
        <button class="mobile-menu" id="mobileMenu">☰</button>
        <div class="page-title">
          <h2>@yield('page_title', 'Dashboard Admin')</h2>
          <p>@yield('page_description', 'Overview seluruh data sistem posyandu.')</p>
        </div>
        <div class="admin-profile">
          <div>
            <strong>{{ Auth::user()->name ?? 'Admin Posyandu' }}</strong>
            <span>{{ request()->path() }}</span>
          </div>
          <div class="admin-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
        </div>
      </header>

      @yield('admin_content')
    </main>
  </div>

  <script>
    document.getElementById('mobileMenu').addEventListener('click', function() {
      document.body.classList.add('sidebar-open');
    });
    document.getElementById('overlay').addEventListener('click', function() {
      document.body.classList.remove('sidebar-open');
    });
  </script>

  @yield('scripts')
</body>
</html>
