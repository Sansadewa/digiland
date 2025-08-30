<!DOCTYPE html>
<html lang="id">
<head>
    <base href="<?= (ENVIRONMENT === 'production') ? '/' : '/wedding/' ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIGILAND - Wedding Invitation</title>
    <meta name="title" content="DIGILAND - Wedding Invitation">
    <meta name="description" content="Undangan pernikahan Gibran & Diyang">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:title" content="DIGILAND - Wedding Invitation">
    <meta property="og:description" content="Undangan pernikahan Gibran & Diyang">
    <meta property="og:image" content="<?= base_url('assets/cover2.webp') ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= current_url() ?>">
    <meta property="twitter:title" content="DIGILAND - Wedding Invitation">
    <meta property="twitter:description" content="Undangan pernikahan Gibran & Diyang">
    <meta property="twitter:image" content="<?= base_url('assets/cover2.webp') ?>">
    
    <link rel="icon" href="assets/Digiland.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'warm-brown': '#8b7355',
                        'light-brown': '#a0917d',
                        'dark-brown': '#6b5b47',
                        'cream': '#f8f4ec',
                        'light-cream': '#faf8f5'
                    },
                    fontFamily: {
                        'sans': ['DM Sans', 'sans-serif'],
                        'serif': ['Playfair Display', 'serif'],
                        'display': ['Playfair Display', 'serif']
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="font-sans overflow-x-hidden bg-cream">
    <h1 class="sr-only">DIGILAND - Wedding Invitation</h1>
    <img src="assets/cover2.webp" alt="DIGILAND" class="sr-only" />
    <!-- Puzzle Section -->
    <section id="puzzleSection" class="relative w-full h-screen invitation-bg flex justify-center items-center overflow-hidden">
        <div class="w-full max-w-md mx-auto h-screen relative">
            <!-- Video Background -->
            <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover z-1" style="filter: grayscale(100%)">
                <source src="assets/video.m4v" type="video/mp4">
                <img src="assets/cover1.webp" alt="Background" class="w-full h-full object-cover" style="filter: grayscale(100%)">
            </video>
            
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/15 z-10"></div>
 
            <div class="relative z-20 h-full px-6 py-8 text-center">
                <!-- Logo fixed di atas -->
                <div class="absolute top-20 left-1/2 transform -translate-x-1/2">
                    <img src="assets/digiland-logo3.png" alt="DIGILAND" class="digiland-logo w-86 mx-auto" />
                </div>
                
                <!-- Content container di tengah -->
                <div class="flex flex-col justify-center items-center h-full pt-24">
                    <!-- Greeting -->
                    <div class="text-sm text-white/90 mb-2 font-light tracking-wide">Halo,</div>

                    <!-- Title -->
                    <h1 class="font-display text-2xl text-white leading-tight mb-6 font-light tracking-wide">
                        <?php echo $this->session->userdata('name'); ?>
                    </h1>

                    <!-- Puzzle Container -->
                    <div id="puzzleContainer" class="w-full opacity-100 transition-all duration-500">
                        <div class="text-base text-white mb-3 text-center font-medium leading-relaxed">
                        Kami seperti puzzle, berbeda tetapi saling melengkapi. yuk rangkai puzzle kami!
                        </div>
                        
                        <div class="text-sm text-white/80 mb-4 text-center font-normal italic">
                            Seret potongan gambar ke posisi yang tepat
                        </div>
                        
                        <canvas id="puzzle-canvas" class="w-full max-w-xs h-80 mx-auto block cursor-pointer"></canvas>
                        
                        <div id="puzzleSuccess" class="text-center text-white text-base mb-3 opacity-0 transition-opacity duration-500 font-medium leading-relaxed">
                        Terimakasih telah menyatukan kami! 💕
                        </div>
                    </div>
                    
                    <!-- Open Button -->
                    <button id="openButton" onclick="openInvitation()" class="bg-transparent hover:bg-white/10 text-white border border-white/60 px-6 py-2 text-sm font-light tracking-wider backdrop-blur-sm relative overflow-hidden mt-4 opacity-0 translate-y-5 pointer-events-none transition-all duration-500 hover:transform hover:-translate-y-1 group">
                        <span class="relative z-10">Masuk ke DIGILAND</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-500"></div>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Wedding Invitation Sections (Hidden Initially) -->
    <div id="weddingInvitation" class="hidden">
        <!-- Hero Section -->
        <section class="relative h-screen bg-cream flex items-center justify-center" id="hero">
            <div class="w-full max-w-md mx-auto h-screen relative">
                <div class="hero-frame" id="heroFrame">
                    <div class="hero-image-inner" id="heroImage"></div>
                    
                    <div class="hero-content" id="heroContent">
                        
                        <!-- The Wedding of -->
                        <p class="text-sm font-light tracking-widest text-white absolute top-16 left-1/2 transform -translate-x-1/2">The Wedding of</p>

                        <!-- Nama pengantin di tengah -->
                        <h1 class="font-display text-4xl font-normal leading-tight text-white">
                            DIYANG<br>
                            <span class="text-4xl font-light">&</span> GIBRAN
                        </h1>
                            
                        <!-- Tanggal di bawah -->
                        <p class="text-sm font-light tracking-wider text-white absolute bottom-16 left-1/2 transform -translate-x-1/2">06 • 09 • 2025</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quranic Verse Section -->
        <section class="bg-gradient-to-b from-light-cream to-cream py-8 flex items-center justify-center">
            <div class="w-full max-w-md mx-auto px-6 text-center">
                <div class="mb-6 fade-in">
                    <h3 class="font-display text-base text-warm-brown mb-4 tracking-wide">Q.S. Ar Rum:21</h3>
                </div>
                
                <div class="mb-8 fade-in">
                    <blockquote class="font-display text-xs text-gray-700 italic leading-relaxed max-w-xs mx-auto">
                        "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari (jenis) dirimu sendiri agar kamu merasa tenteram kepadanya. Dia menjadikan di antaramu rasa kasih dan sayang. Sungguh, pada yang demikian itu benar-benar terdapat tanda-tanda (kebesaran Allah) bagi kaum yang berpikir."
                    </blockquote>
                </div>
                
                <div class="mb-8 fade-in">
                    <img src="assets/Bismillah.png" alt="Bismillah" class="mx-auto max-w-32 opacity-80" />
                </div>
                
                <div class="fade-in">
                    <p class="font-display text-sm text-warm-brown mb-1">Maha Suci Allah SWT,</p>
                    <p class="font-display text-sm text-warm-brown mb-1">yang telah mempersatukan kami</p>
                </div>
            </div>
        </section>

        <!-- Couple Info Section -->
        <section class="min-h-screen bg-cream flex items-center justify-center">
            <div class="w-full max-w-md mx-auto">
                <!-- Diyang Profile -->
                <div class="mb-8 fade-in">
                    <div class="couple-frame" data-couple="diyang">
                        <div class="couple-image-container relative mx-auto w-full h-screen">
                            <div class="couple-photo w-full h-full overflow-hidden relative">
                                <div class="couple-image w-full h-full" style="background-image: url('assets/CroppedDyg.webp')"></div>
                                
                                <div class="absolute bg-gradient-to-t from-black/30 from-5% to-transparent to-20% inset-0 flex flex-col justify-end items-center text-center p-6 z-20">
                                    <div class="p-4 text-white ">
                                        <h3 class="font-display text-2xl mb-1">DIYANG GITA CENDEKIA</h3>
                                        <p class="text-xs opacity-90 italic mb-1">binti</p>
                                        <p class="text-sm font-medium mb-2">Fathul Arifin & Eka Rustika</p>
                                        <a href="https://instagram.com/diyangdyg" target="_blank" rel="noopener noreferrer" 
                                        class="inline-flex items-center justify-center bg-transparent border border-white/60 hover:bg-white/10 text-white text-xs px-3 py-1.5 rounded-md transition-all duration-300 hover:scale-105 hover:border-white">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                            </svg>
                                            @diyangdyg
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-4 fade-in">
                    <div class="flex items-center justify-center space-x-4">
                        <div class="w-8 h-[1px] bg-warm-brown"></div>
                        <span class="font-display text-sm text-warm-brown mb-1">dengan</span>
                        <div class="w-8 h-[1px] bg-warm-brown"></div>
                    </div>
                </div>
                
                <!-- Gibran Profile -->
                <div class="mb-8 fade-in">
                    <div class="couple-frame" data-couple="gibran">
                        <div class="couple-image-container relative mx-auto w-full h-screen">
                            <div class="couple-photo w-full h-full overflow-hidden relative">
                                <div class="couple-image w-full h-full" style="background-image: url('assets/CroppedGib.webp')"></div>
                                
                                <div class="absolute bg-gradient-to-t from-black/40 from-5% to-transparent to-20% inset-0 flex flex-col justify-end items-center text-center p-6 z-20">
                                    <div class="p-4 text-white">
                                        <h3 class="font-display text-2xl mb-1">GIBRAN SANSADEWA ASSHADIQI</h3>
                                        <p class="text-xs opacity-90 italic mb-1">bin</p>
                                        <p class="text-sm font-medium mb-2">Agus Hariyanto & Silvy Madonna Liani</p>
                                        <a href="https://instagram.com/gibransansa" target="_blank" rel="noopener noreferrer" 
                                        class="inline-flex items-center justify-center bg-transparent border border-white/60 hover:bg-white/10 text-white text-xs px-3 py-1.5 rounded-md transition-all duration-300 hover:scale-105 hover:border-white">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                            </svg>
                                            @gibransansa
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Wedding Details Section -->
        <section class="bg-cream py-16 flex justify-center">
            <div class="w-full max-w-md mx-auto px-6">
                <div class="text-center mb-8 fade-in">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Kami mengundang <span class="font-medium"><?php echo $this->session->userdata('name'); ?></span><br>
                        untuk hadir dalam acara pernikahan kami pada:
                    </p>
                </div>
                
                <div class="text-center mb-12 fade-in">
                    <p class="inline-flex font-display text-2xl text-gray-600 mb-1">Minggu, </p>
                    <h2 class="inline-flex font-display text-3xl text-warm-brown mb-2">07 September</h2>
                    <p class="inline-flex font-display text-2xl text-gray-600 mb-1"> 2025</p>
                    <p class="text-lg text-warm-brown font-medium">09.00 - 14.00 WITA</p>
                </div>
                
                <div class="text-center mb-12 fade-in">
                    <p class="text-sm text-gray-700 font-medium mb-4">Gedung Serbaguna<br>Universitas Lambung Mangkurat</p>
                    <p class="text-xs text-gray-600 mb-6">Banjarbaru, Kalimantan Selatan</p>
                    
                    <div class="flex space-x-3 mb-6">
                        <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text=Wedding%20Diyang%20%26%20Gibran&dates=20250907T010000Z/20250907T060000Z&details=Acara%20pernikahan%20Diyang%20dan%20Gibran&location=Gedung%20Serbaguna%20Universitas%20Lambung%20Mangkurat%2C%20Banjarbaru%2C%20Kalimantan%20Selatan" 
                        target="_blank" rel="noopener noreferrer"
                        class="flex-1 bg-transparent border border-warm-brown text-warm-brown py-2 px-4 text-sm font-medium hover:bg-warm-brown hover:text-white transition-colors duration-300 text-center">
                            ADD TO CALENDAR
                        </a>
                        
                        <a href="https://maps.app.goo.gl/sT5idf9A3jp5hbU47" 
                        target="_blank" rel="noopener noreferrer"
                        class="flex-1 bg-warm-brown text-white py-2 px-4 text-sm font-medium hover:bg-dark-brown transition-colors duration-300 text-center">
                            GOOGLE MAPS
                        </a>
                    </div>
                </div>
                
                <div class="w-24 h-0.5 bg-warm-brown mx-auto mb-12 fade-in"></div>
                
                <div class="fade-in mb-12 text-center">
                    <div class="mb-4">
                        <h4 class="font-display text-xl text-warm-brown mb-2">AKAD NIKAH</h4>
                        <p class="text-sm text-gray-600 mb-1">Sabtu, 6 September 2025</p>
                        <p class="text-sm text-warm-brown font-medium mb-3">09.00 - 11.00 WITA</p>
                    </div>
                        
                    <p class="text-sm text-gray-700 font-medium mb-1">Rumah Kediaman - Komplek Cahaya Ratu Elok, Jl. Puput No.257, Banjarbaru Selatan.</p>
                </div>

                <div class="w-24 h-0.5 bg-warm-brown mx-auto mb-12 fade-in"></div>

                <div class="fade-in text-center">
                    <div class="text-center text-xs text-gray-600 leading-relaxed mt-6">
                        <p class="italic">
                            Kedatanganmu akan sangat berarti dalam awalan perjalanan kami.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Gallery Section -->
        <section class="bg-gradient-to-b from-light-cream to-warm-brown/10 py-16 flex justify-center">
            <div class="w-full max-w-md mx-auto px-6">
                <div class="text-center mb-12 fade-in">
                    <h2 class="font-display text-3xl text-warm-brown mb-4">Our Moments</h2>
                    <div class="w-24 h-0.5 bg-warm-brown mx-auto"></div>
                </div>
                
                <div class="fade-in">
                    <div id="galleryLoading" class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-warm-brown mx-auto mb-2"></div>
                        <p class="text-sm text-gray-500">Loading photos...</p>
                    </div>
                    
                    <div id="galleryContainer" class="hidden">
                        <div class="overflow-x-scroll scrollbar-hidden pb-4">
                            <div id="galleryGrid" class="flex space-x-4" style="width: max-content;">
                                <!-- Photos akan di-inject oleh JavaScript -->
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="text-xs text-gray-500 italic">← Swipe →</p>
                        </div>
                    </div>
                    
                    <div id="galleryError" class="hidden text-center py-8">
                        <p class="text-sm text-gray-500">Failed to load photos</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Video Section -->
        <section class="bg-gradient-to-b from-warm-brown/10 to-cream py-8 flex items-center justify-center">
            <div class="w-full max-w-md mx-auto px-6">
                <div class="fade-in">
                    <div class="relative w-full mb-6" style="padding-bottom: 56.25%;">
                        <!-- <iframe 
                            class="absolute top-0 left-0 w-full h-full"
                            src="https://www.youtube.com/embed/TWu0z58iIg4?rel=0&showinfo=0&modestbranding=1"
                            title="Wedding Video"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe> -->
                        <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/reel/DN66tbZk7iI/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);"><div style="padding:16px;"> <a href="https://www.instagram.com/reel/DN66tbZk7iI/?utm_source=ig_embed&amp;utm_campaign=loading" style=" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;" target="_blank"> <div style=" display: flex; flex-direction: row; align-items: center;"> <div style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;"></div> <div style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center;"> <div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;"></div> <div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;"></div></div></div><div style="padding: 19% 0;"></div> <div style="display:block; height:50px; margin:0 auto 12px; width:50px;"><svg width="50px" height="50px" viewBox="0 0 60 60" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g transform="translate(-511.000000, -20.000000)" fill="#000000"><g><path d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631"></path></g></g></g></svg></div><div style="padding-top: 8px;"> <div style=" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;">View this post on Instagram</div></div><div style="padding: 12.5% 0;"></div> <div style="display: flex; flex-direction: row; margin-bottom: 14px; align-items: center;"><div> <div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(0px) translateY(7px);"></div> <div style="background-color: #F4F4F4; height: 12.5px; transform: rotate(-45deg) translateX(3px) translateY(1px); width: 12.5px; flex-grow: 0; margin-right: 14px; margin-left: 2px;"></div> <div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(9px) translateY(-18px);"></div></div><div style="margin-left: 8px;"> <div style=" background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 20px; width: 20px;"></div> <div style=" width: 0; height: 0; border-top: 2px solid transparent; border-left: 6px solid #f4f4f4; border-bottom: 2px solid transparent; transform: translateX(16px) translateY(-4px) rotate(30deg)"></div></div><div style="margin-left: auto;"> <div style=" width: 0px; border-top: 8px solid #F4F4F4; border-right: 8px solid transparent; transform: translateY(16px);"></div> <div style=" background-color: #F4F4F4; flex-grow: 0; height: 12px; width: 16px; transform: translateY(-4px);"></div> <div style=" width: 0; height: 0; border-top: 8px solid #F4F4F4; border-left: 8px solid transparent; transform: translateY(-4px) translateX(8px);"></div></div></div> <div style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center; margin-bottom: 24px;"> <div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 224px;"></div> <div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 144px;"></div></div></a><p style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;"><a href="https://www.instagram.com/reel/DN66tbZk7iI/?utm_source=ig_embed&amp;utm_campaign=loading" style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none;" target="_blank">A post shared by Diyang &amp; Gibran Land (@digiland.space)</a></p></div></blockquote>
<script async src="//www.instagram.com/embed.js"></script>
                    </div>
                </div>
            </div>
        </section>

        <!-- RSVP Section -->
        <section class="bg-gradient-to-b from-warm-brown/10 to-cream py-16 flex items-center justify-center">
            <div class="w-full max-w-md mx-auto px-6">
                <div class="bg-white shadow-xl p-8 fade-in max-w-sm mx-auto">
                    <div class="text-center mb-8">
                        <h2 class="font-display text-2xl text-warm-brown mb-2 tracking-wide">RSVP</h2>
                        <div class="w-16 h-0.5 bg-warm-brown mx-auto"></div>
                    </div>
                    
                    <form id="rsvpForm" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-600 mb-3 tracking-wide">NAMA</label>
                            <input type="text" id="name" placeholder="Nama" required
                                class="w-full px-0 py-3 border-0 border-b border-gray-200 focus:ring-0 focus:border-warm-brown bg-transparent text-sm placeholder-gray-400 transition-colors" value="<?php echo ($this->session->userdata('name')=='Teman dan Keluarga' ? '' : $this->session->userdata('name')); ?>">
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-600 mb-3 tracking-wide">UCAPAN</label>
                            <textarea id="message" rows="3" placeholder="Ucapan untuk pengantin" required
                                class="w-full px-0 py-3 border-0 border-b border-gray-200 focus:ring-0 focus:border-warm-brown bg-transparent text-sm placeholder-gray-400 resize-none transition-colors"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-3 tracking-wide">KEHADIRAN SAYA:</label>
                            <select name="attendance" id="attendance" required class="w-full px-0 py-3 border-0 border-b border-gray-200 focus:ring-0 focus:border-warm-brown bg-transparent text-sm text-gray-600 appearance-none cursor-pointer transition-colors">
                                <option value="">Pilih</option>
                                <option value="hadir">Ya, saya akan hadir</option>
                                <option value="tidak-hadir">Maaf, saya tidak dapat hadir</option>
                            </select>
                        </div>
                        <input type="hidden" name="username" id="username" value="<?php echo $this->session->userdata('username'); ?>">
                        <div class="pt-4">
                            <button type="submit" class="w-full bg-dark-brown hover:bg-warm-brown text-white py-3 px-6 text-sm font-medium tracking-wide transition-colors duration-300 transform hover:scale-[1.02]">
                                Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Messages Section -->
        <section class="bg-gradient-to-b from-cream to-light-cream py-16 flex items-center justify-center">
            <div class="w-full max-w-md mx-auto px-6">
                <div class="text-center mb-12 fade-in">
                    <h2 class="font-display text-3xl text-warm-brown mb-4">UCAPAN & DOA</h2>
                    <div class="w-24 h-0.5 bg-warm-brown mx-auto"></div>
                </div>
                
                <div class="fade-in">
                    <div id="messagesContainer" class="h-80 overflow-y-auto scrollbar-thin space-y-4 pr-2">
                        <!-- Messages akan ditampilkan di sini -->
                    </div>
                    
                    <div class="text-center mt-4">
                        <p class="text-xs text-gray-500 italic">↑ Scroll ↓</p>
                    </div>
                </div>
            </div>
        </section>

        <?php if ($this->session->userdata('show_gift_section') == 1): ?>
        <!-- Wedding Gift Section -->
        <section class="bg-gradient-to-b from-light-cream to-cream py-16 flex items-center justify-center">
            <div class="w-full max-w-md mx-auto px-6">
                <div class="text-center mb-8 fade-in">
                    <h2 class="font-display text-3xl text-warm-brown mb-4">Wedding Gift</h2>
                    <div class="w-24 h-0.5 bg-warm-brown mx-auto mb-6"></div>
                    <p class="text-sm text-gray-600 italic leading-relaxed">
                        Your love is the greatest gift. Any contributions are gratefully accepted.
                    </p>
                </div>
                
                <div class="fade-in">
                    <div id="giftInitial" class="bg-white border-2 border-gray-300 p-12 text-center">
                        <button onclick="showGiftDetails()" class="bg-dark-brown hover:bg-warm-brown text-white px-6 py-2 text-sm font-medium tracking-wide transition-colors duration-300">
                            SEND GIFT
                        </button>
                    </div>
                    
                    <div id="giftDetails" class="bg-white border-2 border-gray-300 p-8 text-center hidden">
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-700 mb-2 text-sm tracking-wide">Mandiri</h4>
                            <p class="font-mono text-lg font-medium text-gray-800 mb-1">310013802254</p>
                            <p class="text-sm text-gray-600">a.n Gibran Sansadewa Asshadiqi</p>
                        </div>
                        
                        <button onclick="copyAccountNumber()" id="copyBtn" class="bg-dark-brown hover:bg-warm-brown text-white px-6 py-2 text-sm font-medium tracking-wide transition-colors duration-300">
                            COPY
                        </button>
                        <button onclick="window.location.href = 'https://gift.digiland.space/<?php echo $this->session->userdata('username'); ?>'" id="copyBtn" class="bg-green-700 hover:bg-green-800 text-white px-6 py-2 text-sm font-medium tracking-wide transition-colors duration-300">
                            Gift List
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- See You There Section -->
        <section class="relative h-screen bg-cream flex items-center justify-center">
            <div class="w-full max-w-md mx-auto h-screen relative">
                <div class="absolute inset-0 w-full h-full">
                    <img src="assets/farewell.webp" alt="See You There" class="w-full h-full object-cover" />
                </div>
                
                <div class="absolute inset-0 bg-black/40"></div>
                
                <div class="absolute top-16 left-1/2 transform -translate-x-1/2 text-center z-10">
                    <div class="text-white font-light text-sm tracking-widest">
                        07 . 09 . 2025
                    </div>
                </div>
                
                <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white z-10">
                    <h2 class="font-display text-4xl font-light mb-2 leading-tight">Sampai Ketemu</h2>
                    <h2 class="font-display text-4xl font-light mb-6 leading-tight">Nanti</h2>
                    <p class="text-sm font-light tracking-wide opacity-90">
                        Terima kasih atas doa dan restunya ya:)
                    </p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="w-full max-w-md mx-auto px-6 text-center">
                <div class="flex items-center justify-center space-x-8">
                    <p class="text-sm font-light">crafted by</p>
                    
                    <div class="text-right">
                        <p class="text-sm font-light tracking-wide">DIGILAND Space &centerdot; @dylusiya &centerdot; @gibransansa</p>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Audio Player - Fixed Bottom Left -->
        <div id="audioPlayer" class="fixed bottom-6 left-6 z-50 hidden">
            <button id="audioToggle" class="bg-warm-brown hover:bg-dark-brown text-white p-3 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110">
                <!-- Play Icon -->
                <svg id="playIcon" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"/>
                </svg>
                <!-- Pause Icon (hidden initially) -->
                <svg id="pauseIcon" class="w-6 h-6 hidden" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                </svg>
            </button>
            
            <!-- Audio Element -->
            <audio id="backgroundMusic" loop preload="auto">
                <source src="assets/salpriadi.mp3" type="audio/mpeg">
            </audio>
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>
