<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - BSI Inventory Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Public Sans', sans-serif;
            background: radial-gradient(circle at top left, #004d4f, #001a1b);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            padding: 20px;
        }

        /* Card Efek Kaca */
        .premium-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.6);
            border-bottom: 6px solid #F2A900;
        }

        /* Logo BSI */
        .logo-container {
            position: relative;
            display: inline-block;
        }
        .bsi-text-main {
            font-size: 3.5rem;
            font-weight: 900;
            color: #00A499;
            line-height: 1;
            letter-spacing: -2px;
        }
        .bsi-star {
            position: absolute;
            top: -5px;
            right: -5px;
            color: #F2A900;
            font-size: 1.5rem;
            filter: drop-shadow(0 0 8px rgba(242, 169, 0, 0.6));
        }
        .bsi-subtext {
            color: #FFFFFF;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-top: 0.5rem;
        }

        /* Input Styling */
        .input-premium {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            color: white;
        }
        .input-premium:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #F2A900;
            box-shadow: 0 0 15px rgba(242, 169, 0, 0.2);
            outline: none;
        }

        /* Button Gold */
        .btn-bsi-gold {
            background: linear-gradient(135deg, #F2A900, #D19200);
            transition: all 0.3s ease;
            color: #003335;
        }
        .btn-bsi-gold:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
            box-shadow: 0 15px 30px rgba(242, 169, 0, 0.4);
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;700;900&display=swap" rel="stylesheet">
</head>
<body>

    <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#F2A900] rounded-full blur-[150px] opacity-10"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-500 rounded-full blur-[150px] opacity-10"></div>

    <div class="premium-card w-full max-w-2xl rounded-[50px] p-10 md:p-14 z-10">
        <div class="text-center mb-10">
            <div class="logo-container">
                <span class="bsi-text-main">BSI</span>
                <i class="fa-solid fa-star bsi-star"></i>
            </div>
            <div class="bsi-subtext">Bank Syariah Indonesia</div>
            <h2 class="text-white text-xl font-bold italic mt-6">Registrasi Petugas Baru</h2>
        </div>

        <form action="{{ route('register.submit') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf

            @if ($errors->any())
                <div class="md:col-span-2 bg-red-500/20 border border-red-500 text-red-200 text-[10px] py-3 px-4 rounded-xl mb-2 uppercase tracking-widest">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="md:col-span-2 text-left">
                <label class="text-[10px] font-bold text-gray-400 uppercase ml-4 mb-2 block">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Nama Lengkap Anda" value="{{ old('name') }}"
                       class="input-premium w-full rounded-2xl px-6 py-4 text-sm">
            </div>

            <div class="text-left">
                <label class="text-[10px] font-bold text-gray-400 uppercase ml-4 mb-2 block">Username (Email)</label>
                <input type="email" name="email" required placeholder="email@gmail.com" value="{{ old('email') }}"
                       class="input-premium w-full rounded-2xl px-6 py-4 text-sm">
            </div>

            <div class="text-left">
                <label class="text-[10px] font-bold text-gray-400 uppercase ml-4 mb-2 block">Jabatan</label>
                <select name="jabatan" class="input-premium w-full rounded-2xl px-6 py-4 text-sm font-bold text-[#F2A900] appearance-none">
                    <option value="Admin" class="bg-[#003335]">Admin</option>
                    <option value="Petugas" class="bg-[#003335]">Petugas</option>
                </select>
            </div>

            <div class="text-left">
                <label class="text-[10px] font-bold text-gray-400 uppercase ml-4 mb-2 block">Password</label>
                <input type="password" name="password" required placeholder="••••••••" 
                       class="input-premium w-full rounded-2xl px-6 py-4 text-sm">
            </div>

            <div class="text-left">
                <label class="text-[10px] font-bold text-gray-400 uppercase ml-4 mb-2 block">Konfirmasi</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••" 
                       class="input-premium w-full rounded-2xl px-6 py-4 text-sm">
            </div>

            <div class="md:col-span-2 pt-6">
                <button type="submit" class="btn-bsi-gold w-full py-5 rounded-3xl font-black text-xs uppercase tracking-widest shadow-xl">
                    Daftar Sekarang
                </button>
                <p class="text-center text-xs text-gray-400 mt-8">
                    Sudah punya akses? <a href="{{ route('login') }}" class="text-[#F2A900] font-bold hover:underline">Masuk Kembali</a>
                </p>
            </div>
        </form>

        <div class="mt-8">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest text-center">
                &copy; 2026 BSI KCP Padang Ulak Karang
            </p>
        </div>
    </div>

</body>
</html>