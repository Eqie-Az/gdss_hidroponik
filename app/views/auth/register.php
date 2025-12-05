<?php $title = $title ?? 'Registrasi'; ?>

<div class="card card-center">
    <h2>Registrasi Pengguna</h2>

    <form action="/Auth/doRegister" method="post">
        <div class="form-group">
            <label>Nama</label>
            <input class="input" type="text" name="nama">
        </div>

        <div class="form-group">
            <label>Username</label>
            <input class="input" type="text" name="username">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input class="input" type="password" name="password">
        </div>

        <div class="form-group">
            <label>Peran</label>
            <select class="input" name="peran">
                <option value="dm">Decision Maker</option>
                <option value="ketua">Ketua</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button class="btn btn-primary" type="submit">Simpan</button>
    </form>
</div>
