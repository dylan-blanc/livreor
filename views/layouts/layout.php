<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? e($title) . ' - ' . APP_NAME : APP_NAME; ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css'); ?>">
    <style>
        /* Navigation améliorée pour le MVC Starter */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .nav-brand a {
            color: white;
            text-decoration: none;
            font-size: 1.5em;
            font-weight: bold;
            padding: 15px 0;
            display: block;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            align-items: center;
        }

        .nav-menu li {
            position: relative;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
            transition: background-color 0.3s ease;
            border-radius: 5px;
            margin: 0 5px;
        }

        .nav-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-menu .dropdown {
            position: relative;
        }

        .nav-menu .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            border-radius: 5px;
            top: 100%;
            left: 0;
            overflow: hidden;
        }

        .nav-menu .dropdown:hover .dropdown-content {
            display: block;
        }

        .nav-menu .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            margin: 0;
            border-radius: 0;
            transition: background-color 0.3s ease;
        }

        .nav-menu .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .nav-menu .dropdown-content .dropdown-header {
            padding: 8px 16px;
            font-size: 0.9em;
            font-weight: bold;
            color: #666;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5em;
            cursor: pointer;
            padding: 10px;
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                align-items: stretch;
            }

            .mobile-menu-toggle {
                display: block;
                position: absolute;
                right: 20px;
                top: 15px;
            }

            .nav-menu {
                display: none;
                flex-direction: column;
                width: 100%;
                background-color: rgba(0, 0, 0, 0.1);
                margin-top: 10px;
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-menu li {
                width: 100%;
            }

            .nav-menu a {
                margin: 0;
                border-radius: 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .nav-menu .dropdown-content {
                position: static;
                display: block;
                box-shadow: none;
                background-color: rgba(0, 0, 0, 0.1);
                margin-left: 20px;
            }

            .nav-menu .dropdown-content a {
                color: rgba(255, 255, 255, 0.8);
                background-color: transparent;
            }
        }

        /* Flash messages styling */
        .flash-messages {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .flash-message {
            padding: 12px 20px;
            margin-bottom: 10px;
            border-radius: 5px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .flash-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .flash-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .flash-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .flash-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .flash-close {
            background: none;
            border: none;
            font-size: 1.2em;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
            margin-left: 10px;
        }

        .flash-close:hover {
            opacity: 1;
        }
    </style>
</head>


<!-- TEST AFFICHAGE ID SESSION  -->
<?php 
echo "Session : " . session_id();

if (isset($_SESSION['user_id'])) {
    echo " --- \n ID de Session : " . $_SESSION['user_id'];
} else {
    echo "  --- \n Utilisateur non connecté";
}

if (isset($_SESSION['username'])) {
    echo " --- \n Nom d'utilisateur : " . $_SESSION['username'];
}
?>

<body>
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-brand">
                    <a href="<?= url(); ?>"><?= APP_NAME; ?></a>
                </div>

                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>

                <ul class="nav-menu" id="navMenu">
                    <li><a href="<?= url(); ?>">🏠 Accueil</a></li>
                    <li><a href="<?= url('auth/inscription'); ?>">📝 Inscription</a></li>
                    <li><a href="<?= url('auth/connexion'); ?>">📝 Connexion</a></li>
                    <?php if (is_logged_in()): ?>
                        <li><a href="<?= sess_destroy(); ?>">🔒 Déconnexion</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <?php
        // Affichage des messages flash
        $flash_messages = get_flash_messages();
        if (!empty($flash_messages)):
        ?>
            <div class="flash-messages">
                <?php foreach ($flash_messages as $type => $messages): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="flash-message flash-<?= e($type); ?>">
                            <span><?= e($message); ?></span>
                            <button class="flash-close" onclick="this.parentElement.remove()" title="Fermer">×</button>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?= $content ?? ''; ?>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div style="max-width: 1200px; margin: 0 auto; margin-top: 40px; text-align: center; color: white;">
                <p>&copy; <?= date('d/m/Y'); ?> <?= APP_NAME; ?> o7 </p>
            </div>
        </div>
    </footer>

    <script src="<?= url('assets/js/app.js'); ?>"></script>
    <script>
        // Menu mobile toggle
        function toggleMobileMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }

        // Fermer le menu mobile quand on clique sur un lien
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                const navMenu = document.getElementById('navMenu');
                navMenu.classList.remove('active');
            });
        });

        // Auto-hide flash messages après 5 secondes
        document.querySelectorAll('.flash-message').forEach(message => {
            setTimeout(() => {
                message.style.opacity = '0';
                setTimeout(() => {
                    message.remove();
                }, 300);
            }, 5000);
        });
    </script>
</body>

</html>