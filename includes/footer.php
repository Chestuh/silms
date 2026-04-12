</main>
<footer class="bg-dark text-light py-4 mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h6><?= htmlspecialchars(APP_NAME) ?></h6>
                <p class="small text-secondary mb-0"><?= htmlspecialchars(APP_TAGLINE) ?></p>
            </div>
            <div class="col-md-6 text-md-end small text-secondary">
                &copy; <?= date('Y') ?> CSP Learning Portal. All rights reserved.
            </div>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/app.js"></script>
<?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
