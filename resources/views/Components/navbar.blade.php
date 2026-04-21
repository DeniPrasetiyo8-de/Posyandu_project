<nav class="fixed top-0 w-full z-50 bg-slate-950/80 border-b border-slate-800">
    <div class="max-w-9xl mx-auto pl-2 pr-6 py-4 flex justify-between items-center">

       <div class="flex items-center gap-5">
    <img src="{{ asset('images/LPG1.PNG') }}" alt="Logo" class="w-10 h-10 object-contain">
    <span class="font-bold text-white">SIPANDU</span>
</div>

        <div class="flex gap-6 items-center">
            @auth
                <span class="text-white font-semibold">{{ Auth::user()->name }}</span>
                <form method="POST" action="/logout" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-xl font-bold transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                        <i class="fas fa-sign-out-alt mr-1"></i>Keluar
                    </button>
                </form>
            @else
                <a href="/login" class="bg-gradient-to-r from-emerald-500 to-blue-500 hover:from-emerald-600 hover:to-blue-600 text-white px-6 py-2 rounded-xl font-bold transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center space-x-2">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk</span>
                </a>
            @endauth
        </div>

</nav>
