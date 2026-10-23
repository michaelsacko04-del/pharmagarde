<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') : @yield('titre') | PharmaGarde</title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; background: #E8F5EC; color: #1b2b22; text-align: center; padding: 16px; }
        .boite { max-width: 460px; background: #fff; border-radius: 16px; padding: 32px 24px; box-shadow: 0 2px 12px rgba(11, 110, 62, .15); }
        .code { font-size: 4rem; font-weight: 800; color: #0B6E3E; line-height: 1; }
        h1 { margin: 12px 0 8px; font-size: 1.4rem; color: #0B6E3E; }
        p { margin: 0 0 20px; }
        a { display: inline-block; background: #0B6E3E; color: #fff; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 700; }
        a:hover { background: #084f2c; }
    </style>
</head>
<body>
    <main class="boite">
        <div class="code">@yield('code')</div>
        <h1>@yield('titre')</h1>
        <p>@yield('message')</p>
        <a href="{{ url('/') }}">Retour à l'accueil</a>
    </main>
</body>
</html>
