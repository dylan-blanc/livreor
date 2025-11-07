<?php $title = "Profil"; ?>
<style>
    <?php include __DIR__ . '/../../public/assets/css/register.css'; ?>
</style>

<div class="profil-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-info">
                <h2 class="username">@<?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Utilisateur'; ?></h2>
                <ul class="meta">
                    <li>Membre</li>
                    <li>Dernière activité: <?= isset($_SESSION['last_activity']) ? date('d/m/Y H:i', (int)$_SESSION['last_activity']) : '—'; ?></li>
                </ul>
            </div>
        </div>

        <div class="profile-tabs">
            <input type="radio" name="profile-tab" id="tab-edit" class="tab-input" checked>
            <label for="tab-edit" class="tab-label">Modifier le profil</label>

            <input type="radio" name="profile-tab" id="tab-history" class="tab-input">
            <label for="tab-history" class="tab-label">Historique</label>

            <div class="tab-panels">
                <section class="tab-content edit">
                    <h3 class="section-title">Modifier mes informations</h3>
                    <form class="edit-form" method="post">
                        <div class="form-row">
                            <div class="field">
                                <label for="username">Nom d'utilisateur</label>
                                <input type="text" id="username" name="username" class="form-input" placeholder="Votre pseudo" value="<?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '' ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="field">
                                <label for="new-password">Nouveau mot de passe</label>
                                <input type="password" id="password" name="password" class="form-input">
                            </div>
                            <div class="field">
                                <label for="confirm_password">Confirmer le mot de passe</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-input">
                            </div>
                        </div>
                        <div class="actions">
                            <button type="submit" class="register-btn">Enregistrer</button>
                        </div>
                        <p class="helper-text">utilisez un mot de passe d’au moins 6 caractères incluant majuscules, minuscules et chiffres.</p>
                    </form>
                </section>

                <section class="tab-content history">
                    <h3 class="section-title">Historique de vos commentaires</h3>
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $msg): ?>
                            <div class="chat-message">
                                <span class="chat-date">
                                    <?= e('Posté le ' . (isset($msg['date_commentaire']) ? date('d/m/Y à H:i', strtotime($msg['date_commentaire'])) : '')) ?>
                                </span>
                                <p class="historique-message"><?= e($msg['commentaire']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div>Aucun message pour le moment.</div>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>
</div>