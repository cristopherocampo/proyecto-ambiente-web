        </main>

<?php if (!empty($_SESSION['user_id'])): ?>
        <footer class="app-footer">
            <strong>BookCycle</strong>
            <span>Plataforma de intercambio académico · Comunidad · Soporte</span>
        </footer>
    </div>
<?php else: ?>
    <footer class="guest-footer">BookCycle · Intercambio académico responsable</footer>
<?php endif; ?>

<script src="<?= BASE_URL ?>/public/js/custom-selects.js" defer></script>
</body>
</html>
