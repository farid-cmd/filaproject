<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Logbook Magang UPR</title>

    <!-- Gunakan Tailwind CDN agar cepat dan ringan -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-amber-100 via-white to-amber-50 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl p-6">
        <h2 class="text-2xl font-semibold text-center text-amber-700 mb-6">Login SSO UPR</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Username -->
            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
                <input type="text" name="username" id="username" 
                    value="{{ old('username') }}"
                    required autofocus
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" id="password" 
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>

            <!-- Tombol Login -->
            <button type="submit"
                class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2 rounded-lg transition duration-300">
                Login
            </button>
        </form>

        <!-- Pesan Error -->
        @if ($errors->any())
            <div class="mt-4 bg-red-100 text-red-700 px-3 py-2 rounded-lg text-sm text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <p class="mt-6 text-center text-sm text-gray-500">
            Sistem Terintegrasi dengan <strong>SSO Universitas Palangka Raya</strong>
        </p>
    </div>

</body>
</html>
