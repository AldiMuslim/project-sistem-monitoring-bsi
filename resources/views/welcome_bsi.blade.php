<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSI Inventory - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Public Sans', sans-serif;
            margin: 0;
            overflow: hidden;
        }

        .hero {
            background: url('/bg-bsi-clean.png') no-repeat center center;
            background-size: cover;
            height: 100vh;
            width: 100%;
        }

        .gradient-left {
            background: linear-gradient(to right, rgba(255,255,255,1) 40%, rgba(255,255,255,0.2));
        }

        /* --- LOGO BSI (SISTEM IKAT MATI) --- */
        .logo-container {
            display: inline-block;
            margin-top: 3rem;
            margin-bottom: 2rem;
        }

        .bsi-text-main {
            font-size: 4.5rem;
            font-weight: 900;
            color: #00A499;
            line-height: 1;
            letter-spacing: -2px;
            display: flex;
            align-items: flex-end;
        }

        /* Ini kotak khusus buat huruf I dan bintangnya saja */
        .wrapper-i {
            position: relative;
            display: inline-block;
        }

        .bsi-star-fixed {
            position: absolute;
            top: -12px; /* Jarak di atas huruf I */
            left: 50%; /* Di tengah-tengah huruf I */
            transform: translateX(-50%); /* Kunci posisi tengah */
            color: #F2A900;
            font-size: 1.6rem;
            filter: drop-shadow(0 0 5px rgba(242, 169, 0, 0.4));
        }

        .bsi-subtext {
            color: #004d4f;
            font-size: 0.9rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-top: 0.3rem;
            display: block;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;700;900&display=swap" rel="stylesheet">
</head>
<body>

    <div class="hero relative">
        <div class="gradient-left absolute inset-0"></div>
        
        <div class="relative z-10 h-full flex items-center px-16 md:px-24">
            <div class="max-w-4xl">
                
                <div class="logo-container">
                    <div class="bsi-text-main">
                        <span>BS</span>
                        <span class="wrapper-i">
                            I
                            <i class="fa-solid fa-star bsi-star-fixed"></i>
                        </span>
                    </div>
                    <div class="bsi-subtext">Bank Syariah Indonesia</div>
                </div>

                <h1 class="text-5xl font-extrabold text-[#004d4f] leading-tight">
                    Sistem Informasi <br>
                    <span class="text-[#00A499]">Monitoring dan Pengelolaan Persediaan</span>
                </h1>
                
                <p class="mt-6 text-xl text-gray-600 font-medium leading-relaxed">
                    Kartu ATM dan Buku Tabungan Secara Real Time <br> 
                    pada Bank BSI KCP Padang Ulak Karang.
                </p>
                
                <div class="mt-10">
                    <a href="{{ route('login') }}" class="inline-block bg-[#00A499] text-white px-10 py-4 rounded-xl font-bold text-lg shadow-lg hover:bg-[#00897b] transition-all duration-300">
                        AKSES PETUGAS
                    </a>
                </div>

                <div class="mt-16 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    &copy; 2026 BSI KCP Padang Ulak Karang
                </div>
            </div>
        </div>
    </div>

</body>
</html>