<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NutriChef IA - Gestion de Recettes')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
          --glass: rgba(255, 255, 255, 0.07);
          --glass-border: rgba(255, 255, 255, 0.12);
          --blur: blur(16px);
          --primary: #2ECC9A;
          --accent: #F4A261;
          --bg: #0D1B2A;
        }

        body {
          background-color: var(--bg);
          color: #F0F4F8;
          font-family: 'Inter', sans-serif;
          min-height: 100vh;
          background-image: radial-gradient(circle at top right, rgba(46, 204, 154, 0.15), transparent 40%),
                            radial-gradient(circle at bottom left, rgba(244, 162, 97, 0.1), transparent 40%);
        }

        .glass-card {
          background: var(--glass);
          backdrop-filter: var(--blur);
          -webkit-backdrop-filter: var(--blur);
          border: 1px solid var(--glass-border);
          border-radius: 20px;
          padding: 1.5rem;
          transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .glass-card:hover {
          transform: translateY(-4px);
          box-shadow: 0 8px 32px rgba(46, 204, 154, 0.15);
        }

        @keyframes fadeSlideUp {
          from { opacity: 0; transform: translateY(20px); }
          to   { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-slide {
          animation: fadeSlideUp 0.4s ease forwards;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased pb-20">
    <x-nav />
    
    <main class="container mx-auto px-6 pt-32">
        @yield('content')
    </main>
</body>
</html>
