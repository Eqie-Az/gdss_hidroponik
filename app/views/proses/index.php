<?php $title = $title ?? 'Proses AHP & BORDA'; ?>

<div class="card">
    <h2>Proses AHP & BORDA</h2>
    <p>Tekan tombol berikut untuk memproses penilaian semua decision maker menjadi hasil konsensus.</p>

    <form action="/Proses/jalankan" method="post"
          onsubmit="return confirm('Yakin memproses AHP & BORDA sekarang?')">
        <button class="btn btn-primary" type="submit">Jalankan Proses</button>
    </form>
</div>
