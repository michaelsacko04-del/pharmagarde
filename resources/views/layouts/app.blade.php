<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PharmaGarde : médicaments et pharmacies de garde</title>
    <style>
        :root { --vert: #0F9D58; --foret: #0B6E3E; --menthe: #E8F5EC; --corail: #E8543E; --texte: #1b2b22; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; color: var(--texte); background: #fff; line-height: 1.5; }
        header { background: var(--foret); color: #fff; padding: 14px 16px; font-weight: 700; font-size: 1.1rem; }
        main { max-width: 760px; margin: 0 auto; padding: 16px; }
        .hero { background: var(--menthe); border-radius: 12px; padding: 20px 16px; margin-bottom: 20px; }
        .hero h1 { margin: 0 0 6px; font-size: 1.4rem; color: var(--foret); }
        .hero p { margin: 0 0 14px; }
        .recherche { display: grid; gap: 10px; }
        input, select, button { font: inherit; padding: 12px; border-radius: 8px; border: 1px solid #b7d4c2; width: 100%; }
        button { background: var(--foret); color: #fff; border: 0; font-weight: 700; cursor: pointer; }
        button:hover { background: #084f2c; }
        .carte { border: 1px solid #d5e6dc; border-radius: 12px; padding: 14px; margin-bottom: 12px; }
        .carte h2 { margin: 0 0 4px; font-size: 1.1rem; color: var(--foret); }
        .badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: .8rem; font-weight: 700; background: var(--foret); color: #fff; }
        .vide { background: #fdecea; color: #8a2a1b; padding: 14px; border-radius: 12px; }
        .erreur { color: #b3261e; font-size: .95rem; font-weight: 700; }
        ul { padding-left: 20px; margin: 8px 0 0; }
        .pagination { list-style: none; display: flex; gap: 10px; padding: 0; justify-content: center; }
        .pagination li { padding: 6px 12px; border: 1px solid #b7d4c2; border-radius: 8px; }
        .pagination a { color: var(--foret); text-decoration: none; font-weight: 700; }
        .disabled { color: #9bb3a5; }
        .entete { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .entete a { color: #fff; text-decoration: none; }
        .entete nav { display: flex; align-items: center; gap: 12px; font-weight: 400; font-size: .95rem; }
        .inline { margin: 0; }
        .lien { width: auto; background: transparent; border: 1px solid #fff; padding: 6px 12px; color: #fff; }
        .lien:hover { background: rgba(255, 255, 255, .15); }
        .formulaire { display: grid; gap: 10px; }
        label { font-weight: 700; }
        input[type=checkbox] { width: auto; margin-right: 6px; }
        .case { display: block; margin: 10px 0; }
        .ligne-stock label { display: block; margin: 8px 0; }
        .succes { background: var(--menthe); color: var(--foret); padding: 12px; border-radius: 8px; font-weight: 700; }
        .bouton { display: inline-block; background: var(--foret); color: #fff; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 700; }
        .bouton:hover { background: #084f2c; }
        .badge.attente { background: #b3261e; }
        #carte { height: 320px; border-radius: 12px; margin-bottom: 16px; z-index: 0; }
        @media (max-width: 480px) { .entete { flex-wrap: wrap; } main { padding: 12px; } .hero { padding: 16px 12px; } }
        @media (min-width: 640px) { .recherche { grid-template-columns: 2fr 1fr auto; } button { width: auto; } }
    </style>
    @stack('styles')
</head>
<body>
    <header class="entete">
        <a href="{{ route('recherche') }}" class="logo">PharmaGarde</a>
        <nav>
            @auth
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="lien">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}">Connexion</a>
            @endauth
        </nav>
    </header>
    <main>
        @yield('contenu')
    </main>
    @stack('scripts')
</body>
</html>
