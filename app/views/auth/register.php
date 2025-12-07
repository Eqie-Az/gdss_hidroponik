<?php $title = $title ?? 'Registrasi'; ?>

<div class="card card-center">
    <h2>Registrasi Pengguna</h2>

    <?php if (isset($error)): ?>
        <p class="alert alert-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="<?= BASEURL; ?>/auth/doRegister" method="post">
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input class="input" type="text" name="nama" required>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input class="input" type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input class="input" type="password" name="password" required>
        </div>

        <button class="btn btn-primary" type="submit">Daftar Sekarang</button>

        <p style="margin-top: 15px; text-align: center; font-size: 0.9em;">
            Sudah punya akun? <a href="<?= BASEURL; ?>/auth"
                style="text-decoration: underline; font-weight: bold;">Login disini</a>
        </p>
    </form>
</div>