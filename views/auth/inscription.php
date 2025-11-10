<?php $title = "Inscription"; ?>
<style>
    <?php include __DIR__ . '/../../public/assets/css/register.css'; ?>
</style>

<div class="register-container">
    <h2 class="register-title">Créer un compte</h2>
    <form class="register-form" action="" method="post">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input class="form-input" type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input class="form-input" type="password" id="password" name="password" placeholder="1 majuscule, 1 minuscule, 1 chiffre, 6 caractères minimum" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input class="form-input" type="password" id="confirm_password" name="confirm_password" placeholder="Répétez le mot de passe" required>
        </div>
        <button class="register-btn" type="submit">S'inscrire</button>
    </form>
</div>