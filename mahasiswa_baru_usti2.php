<?php

$nama       = '';
$saved      = false;
$error_nama = '';
$timestamp  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');

    if ($nama === '') {
        $error_nama = 'Nama calon mahasiswa wajib diisi.';
    } else {
        $saved     = true;
        $timestamp = date('d-m-Y H:i:s');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mahasiswa Baru USTI</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --navy:   #1A2744;
      --teal:   #ffa600;
      --white:  #FFFFFF;
      --gray:   #F4F7FA;
      --border: #C8D4E8;
      --muted:  #7A8BAA;
      --error:  #D64045;

      /* Foreground default & setelah SAVE */
      --fg-default: #1A2744;
      --fg-saved:   #483d09;
    }

    body {
      min-height: 100vh;
      background: var(--gray);
      font-family: 'Segoe UI', Arial, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    /* ── CARD ── */
    .card {
      width: 100%;
      max-width: 420px;
      background: var(--white);
      border-radius: 14px;
      box-shadow: 0 4px 28px rgba(26,39,68,.12);
      overflow: hidden;
    }

    .card-head {
      background: var(--navy);
      padding: 1rem 1.5rem;
    }

    .card-head-title {
      color: var(--white);
      font-size: .95rem;
      font-weight: 700;
    }

    .card-head-sub {
      color: var(--muted);
      font-size: .72rem;
      margin-top: .2rem;
    }

    .card-body {
      padding: 1.8rem 1.5rem;
    }

    /* ── LABEL — foreground berubah setelah SAVE ── */
    .form-label {
      display: block;
      font-size: .75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .07em;
      margin-bottom: .45rem;
      color: var(--fg-default);
      transition: color .3s ease;
    }

    /* ── INPUT ── */
    .form-input {
      width: 100%;
      padding: .72rem .9rem;
      border: 2px solid var(--border);
      border-radius: 8px;
      font-size: .92rem;
      font-family: inherit;
      outline: none;
      transition: border-color .2s, color .3s ease;
      color: var(--fg-default);
      background: var(--gray);
    }

    .form-input:focus {
      border-color: var(--teal);
      box-shadow: 0 0 0 3px rgba(13,158,138,.15);
      background: var(--white);
    }

    .form-input.is-error {
      border-color: var(--error);
    }

    .error-msg {
      margin-top: .35rem;
      font-size: .76rem;
      color: var(--error);
    }

    /* ── BUTTON ── */
    .btn-save {
      width: 100%;
      margin-top: 1.3rem;
      padding: .82rem;
      background: var(--teal);
      border: none;
      border-radius: 8px;
      font-size: .95rem;
      font-weight: 700;
      font-family: inherit;
      letter-spacing: .04em;
      cursor: pointer;
      /* foreground teks tombol */
      color: var(--white);
      transition: background .2s, color .3s ease;
    }

    .btn-save:hover { background: #ffa600; }
    .btn-save:active { transform: scale(.98); }

    /* ── HASIL SIMPAN ── */
    .result {
      margin-top: 1.4rem;
      padding: 1rem;
      border: 2px solid var(--border);
      border-radius: 8px;
      background: var(--white); /* background TETAP putih */
      display: none;
      transition: border-color .3s;
    }

    .result.show { display: block; }

    .result-label {
      font-size: .7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .07em;
      /* foreground berubah setelah SAVE */
      color: var(--fg-saved);
    }

    .result-value {
      font-size: .95rem;
      font-weight: 700;
      margin-top: .3rem;
      /* foreground berubah setelah SAVE */
      color: var(--fg-saved);
    }

    .result-time {
      font-size: .72rem;
      color: var(--muted);
      margin-top: .4rem;
    }

    /* ══════════════════════════════════════════════════════
       STATE-SAVED — class ditambahkan ke <body> oleh PHP
       Hanya warna TEKS (foreground) yang berubah.
       Background tidak disentuh sama sekali.
    ══════════════════════════════════════════════════════ */
    body.state-saved .form-label {
      color: var(--fg-saved);     /* label → teal */
    }

    body.state-saved .form-input {
      color: var(--fg-saved);     /* teks input → teal */
      border-color: var(--teal);
    }

    body.state-saved .btn-save {
      color: var(--navy);         /* teks tombol → navy */
    }

    body.state-saved .result {
      border-color: var(--teal);
    }

    /* ── FOOTER ── */
    .footer {
      margin-top: 1.2rem;
      font-size: .7rem;
      color: var(--muted);
      text-align: center;
    }
  </style>
</head>
<body class="<?= $saved ? 'state-saved' : '' ?>">

  <div class="card">
    <div class="card-head">
      <div class="card-head-title">Aplikasi Mahasiswa Baru USTI</div>
      <div class="card-head-sub">Prodi Teknik Informatika — Versi 2026-2</div>
    </div>

    <div class="card-body">
      <form method="POST" action="">

        <label class="form-label" for="nama">Nama Calon Mahasiswa</label>
        <input
          type="text"
          id="nama"
          name="nama"
          class="form-input <?= $error_nama ? 'is-error' : '' ?>"
          placeholder="Masukkan nama lengkap..."
          value="<?= htmlspecialchars($nama) ?>"
          autocomplete="off"
        >
        <?php if ($error_nama): ?>
          <div class="error-msg">⚠ <?= htmlspecialchars($error_nama) ?></div>
        <?php endif; ?>

        <button type="submit" class="btn-save">SAVE</button>

      </form>

      <!-- Hasil simpan — muncul setelah SAVE berhasil -->
      <?php if ($saved): ?>
        <div class="result show">
          <div class="result-label">✔ Data Tersimpan</div>
          <div class="result-value"><?= htmlspecialchars($nama) ?></div>
          <div class="result-time"><?= htmlspecialchars($timestamp) ?></div>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <div class="footer">Fakultas Teknik dan Informatika · USTI · TA. 2026</div>

</body>
</html>
