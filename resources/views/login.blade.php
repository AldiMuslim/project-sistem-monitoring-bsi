<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login BSI Inventory - Premium Version</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Public Sans', sans-serif;
            background: radial-gradient(circle at top left, #004d4f, #001a1b);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .premium-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.6);
            border-bottom: 6px solid #F2A900;
        }

        .logo-container {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .bsi-text-main {
            font-size: 4rem;
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
            font-size: 1.8rem;
            filter: drop-shadow(0 0 8px rgba(242, 169, 0, 0.6));
        }

        .bsi-subtext {
            color: #FFFFFF;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-top: 0.5rem;
        }

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

        .btn-bsi-gold {
            background: linear-gradient(135deg, #F2A900, #D19200);
            box-shadow: 0 10px 20px rgba(242, 169, 0, 0.3);
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

    <div class="premium-card w-full max-w-[550px] rounded-[60px] p-12 md:p-16 z-10 text-center">
        
        <div class="mb-10">
            <div class="logo-container">
                <span class="bsi-text-main">BSI</span>
                <i class="fa-solid fa-star bsi-star"></i>
            </div>
            <div class="bsi-subtext">Bank Syariah Indonesia</div>
            
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.4em] mt-8 mb-2">Inventory System</p>
        </div>

        <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
            @csrf

            @if($errors->any())
                <div class="bg-red-500/20 border border-red-500 text-red-200 text-[10px] py-3 px-4 rounded-xl mb-4 uppercase tracking-widest">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="relative text-left">
                <i class="fa-regular fa-envelope absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="email" name="email" required placeholder="Masukkan Email Anda"
                       class="input-premium w-full rounded-2xl pl-14 pr-6 py-4 text-sm">
            </div>

            <div class="relative text-left">
                <i class="fa-solid fa-lock absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="password" name="password" required placeholder="Masukkan Password"
                       class="input-premium w-full rounded-2xl pl-14 pr-14 py-4 text-sm">
                <button type="button" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                    <i class="fa-regular fa-eye-slash"></i>
                </button>
            </div>

            <div class="pt-4">
                <button type="submit" class="btn-bsi-gold w-full py-4 rounded-3xl font-black text-xs uppercase tracking-widest shadow-xl transition">
                    LOGIN SEKARANG
                </button>
            </div>
        </form>

        <div class="text-center mt-12 space-y-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                &copy; 2026 BSI KCP Padang Ulak Karang
            </p>
            
            <div class="h-[1px] w-12 bg-gray-700 mx-auto opacity-30"></div>

            <p class="text-xs text-white font-medium">
                Belum punya akun? 
                <a href="{{ url('/register') }}" class="text-[#F2A900] font-bold hover:text-[#FFC133] transition decoration-2 underline-offset-4 hover:underline">
                    Daftar Sekarang
                </a>
            </p>
            
            <div class="mt-6">
                <a href="{{ url('/') }}" class="text-[10px] text-gray-500 hover:text-white transition uppercase tracking-widest">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

</body>
</html>