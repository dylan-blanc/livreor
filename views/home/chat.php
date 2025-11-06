<?php $title = "Chat"; ?>
<style>
    <?php include __DIR__ . '/../../public/assets/css/chat.css'; ?>
</style>


<div class="chat-container">
    <h2>Bonjour, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Visiteur' ?></h2>
    <div class="chat-box" id="chatBox">
        <!-- Les messages du chat seront affichés ici -->
        <div class="chat-form">
            <form method="post" action="<?= url('home/chat'); ?>">
                <textarea name="message" placeholder="Tapez votre message ici, 500 caractères maximum" required></textarea>
                <button type="submit">Envoyer</button>
            </form>
        </div>
    </div>