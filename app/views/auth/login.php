<?php $title = $title ?? 'Login'; ?>

<div class="card card-center">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <p class="alert alert-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="<?= BASEURL; ?>/auth/login" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input class="input" type="text" name="username" id="username" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input class="input" type="password" name="password" id="password" required>
        </div>

        <button class="btn btn-primary" type="submit">Masuk</button>

        <p style="margin-top: 15px; text-align: center; font-size: 0.9em;">
            Belum punya akun? <a href="<?= BASEURL; ?>/auth/register"
                style="text-decoration: underline; font-weight: bold;">Daftar disini</a>
        </p>
    </form>
</div>