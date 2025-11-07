<?php $title = "Chat"; ?>
<style>
    <?php include __DIR__ . '/../../public/assets/css/chat.css'; ?>
</style>


<div class="chat-container">
    <h2>Bonjour, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Visiteur' ?></h2>
    <div class="chat-box" id="chatBox">
        <div class="chat-messages">
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="chat-message">
                        <span class="chat-date"><?= e('Posté le ' . $msg['date_commentaire_formatee']) . ' Par : ' ?></span>
                        <strong><?= e($msg['login']) ?> :</strong>
                        <p class="historique-message"><?= e($msg['commentaire']) ?></p>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div>Aucun message pour le moment.</div>
            <?php endif; ?>
        </div>
        <div class="chat-form">
            <form method="post" action="<?= url('home/chat'); ?>">
                <textarea name="message" id="messageTextarea" maxlength="800" placeholder="Tapez votre message ici, 800 caractères maximum" required></textarea>
                <div id="charCount" style="color: white">0 / 800 caractères</div>
                <button type="submit">Envoyer</button>
            </form>
        </div>
    </div>

    <script>
        const textarea = document.getElementById('messageTextarea');
        const charCount = document.getElementById('charCount');
        textarea.addEventListener('input', function() {
            charCount.textContent = `${textarea.value.length} / 800 caractères`;
        });
    </script>