<?php $title = $title ?? 'Login'; ?>

<div class="card card-center">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <p class="alert alert-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="/Auth/login" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input class="input" type="text" name="username" id="username">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input class="input" type="password" name="password" id="password">
        </div>

        <button class="btn btn-primary" type="submit">Masuk</button>
    </form>
</div>
