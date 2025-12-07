<?php

class PenilaianController extends Controller
{
    /**
     * Menampilkan Form Input Nilai
     */
    public function form()
    {
        // 1. IZINKAN ROLE FARMER
        // Agar farmer2 & farmer3 bisa masuk
        $this->requireRole(['admin', 'ketua', 'farmer']);

        $data['judul'] = 'Input Penilaian';

        // Ambil data untuk membangun form matriks (Baris: Alternatif, Kolom: Kriteria)
        $data['alternatif'] = $this->model('AlternatifModel')->getAllAlternatif();
        $data['kriteria'] = $this->model('KriteriaModel')->getAllKriteria();

        $this->view('penilaian/form', $data);
    }

    /**
     * Memproses Penyimpanan Nilai dari Form
     */
    public function simpan()
    {
        $this->requireRole(['admin', 'ketua', 'farmer']);
        $this->startSession();

        $id_pengguna = $_SESSION['user_id'];
        $input_nilai = $_POST['nilai'] ?? []; // Struktur: [id_alternatif][id_kriteria] = nilai

        if (empty($input_nilai)) {
            header('Location: ' . BASEURL . '/penilaian/form');
            exit;
        }

        $penilaianModel = $this->model('PenilaianModel');

        // Loop setiap Alternatif yang dikirim form
        foreach ($input_nilai as $id_alternatif => $data_kriteria) {
            // Simpan per alternatif (Model akan menghapus data lama dulu/reset)
            $penilaianModel->simpanPenilaian($id_pengguna, $id_alternatif, $data_kriteria);
        }

        // Redirect ke halaman sukses
        header('Location: ' . BASEURL . '/penilaian/sukses');
        exit;
    }

    public function sukses()
    {
        $this->requireRole(['admin', 'ketua', 'farmer']);
        $data['judul'] = 'Berhasil Disimpan';
        $this->view('penilaian/sukses', $data);
    }
}