<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Digiland Wedding Registry'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="<?php echo base_url('assets/Digiland.svg'); ?>" sizes="any" type="image/svg+xml" />

    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #F9F8F6;
        }
        h1, h2, h3, .font-serif {
            font-family: 'Playfair Display', serif;
        }
        .hero-bg {
            background-image: url('https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=2080&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="text-gray-800">

    <!-- Main Container -->
    <div class="container mx-auto max-w-4xl p-4 md:p-8">

        <!-- Header -->
        <header class="text-center mb-12">
            <div class="hero-bg h-64 md:h-80 rounded-lg flex items-center justify-center text-white shadow-lg">
                <div class="bg-black bg-opacity-40 p-6 rounded-lg">
                    <h1 class="text-4xl md:text-6xl font-bold">DIGILAND</h1>
                    <p class="mt-2 text-lg md:text-xl tracking-widest">WEDDING REGISTRY</p>
                    <p class="mt-4 text-md md:text-lg bg-white bg-opacity-20 inline-block px-4 py-1 rounded-full">
                        Minggu, 07 September 2025
                    </p>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="text-center">
            <div class="bg-white rounded-lg shadow-md p-8 md:p-12">
                <h2 class="text-3xl font-serif text-gray-700 mb-6"><?php echo isset($title) ? $title : 'Welcome to Our Registry'; ?></h2>
                
                <?php if (isset($message)): ?>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed"><?php echo $message; ?></p>
                <?php endif; ?>

                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-serif text-emerald-800 mb-4">How to Access Your Registry</h3>
                    <p class="text-gray-700 mb-4">To view your personalized wedding registry, please use the link that was sent to you. The link should look like:</p>
                    <div class="bg-white border border-emerald-300 rounded-md p-3 font-mono text-sm text-emerald-800">
                        gift.digiland.space/your-username
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-gray-600 mb-4">If you haven't received your personalized link, please contact the couple.</p>
                    <p class="text-sm text-gray-500">With love,</p>
                    <p class="text-xl font-serif text-gray-700">Gibran & Diyang</p>
                </div>
            </div>
        </main>

    </div>

</body>
</html> 