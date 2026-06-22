<?php
// ============================================================
//  APLIKASI MAHASISWA BARU USTI
//  Program Entri Data — Software Quality Assurance Project
//  Prodi Teknik Informatika, USTI — TA. 2026
// ============================================================

$nama        = '';
$nim         = '';
$prodi       = '';
$saved       = false;
$error_nama  = '';
$error_nim   = '';
$error_prodi = '';
$timestamp   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = trim($_POST['nama']  ?? '');
    $nim   = trim($_POST['nim']   ?? '');
    $prodi = trim($_POST['prodi'] ?? '');

    // Validasi
    if ($nama === '') {
        $error_nama = 'Nama wajib diisi.';
    }
    if ($nim === '') {
        $error_nim = 'NIM wajib diisi.';
    }
    if ($prodi === '') {
        $error_prodi = 'Program Studi wajib dipilih.';
    }

    if ($error_nama === '' && $error_nim === '' && $error_prodi === '') {
        $saved    = true;
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
    /* ── RESET & BASE ─────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --navy:        #1A2744;
      --navy-mid:    #253359;
      --teal:        #0D9E8A;
      --teal-light:  #12C4AD;
      --teal-dim:    #0a7a6a;
      --white:       #FFFFFF;
      --off-white:   #F4F7FA;
      --muted:       #7A8BAA;
      --border:      #C8D4E8;
      --accent:      #F5A623;
      --error:       #D64045;
      --success:     #0D9E8A;

      /* foreground states — ini yang berubah saat SAVE/Enter */
      --fg-default:  #1A2744;
      --fg-saved:    #0D9E8A;
    }

    html, body {
      min-height: 100vh;
      background: var(--off-white);
      font-family: 'Segoe UI', Arial, sans-serif;
      color: var(--fg-default);
    }

    /* ── HEADER ────────────────────────────────────── */
    .site-header {
      background: var(--navy);
      padding: 0 2rem;
      display: flex;
      align-items: center;
      gap: 1.2rem;
      height: 64px;
      box-shadow: 0 2px 12px rgba(0,0,0,.18);
    }

    .header-logo {
      width: 40px;
      height: 40px;
      background: var(--teal);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      font-weight: 900;
      color: var(--white);
      letter-spacing: -.5px;
      flex-shrink: 0;
    }

    .header-title {
      color: var(--white);
      font-size: 1rem;
      font-weight: 700;
      line-height: 1.2;
    }

    .header-sub {
      color: var(--teal-light);
      font-size: .72rem;
      font-weight: 400;
      letter-spacing: .03em;
    }

    .header-badge {
      margin-left: auto;
      background: rgba(255,255,255,.08);
      border: 1px solid rgba(255,255,255,.15);
      color: var(--muted);
      font-size: .68rem;
      padding: .25rem .7rem;
      border-radius: 99px;
      letter-spacing: .04em;
    }

    /* ── LAYOUT ────────────────────────────────────── */
    .page-wrap {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 2.5rem 1rem 4rem;
      gap: 1.5rem;
    }

    .page-intro {
      text-align: center;
      max-width: 480px;
    }

    .page-intro h1 {
      font-size: 1.35rem;
      font-weight: 800;
      color: var(--navy);
      letter-spacing: -.02em;
    }

    .page-intro p {
      margin-top: .4rem;
      font-size: .82rem;
      color: var(--muted);
    }

    /* ── CARD ──────────────────────────────────────── */
    .card {
      width: 100%;
      max-width: 520px;
      background: var(--white);
      border-radius: 16px;
      box-shadow: 0 4px 32px rgba(26,39,68,.10);
      overflow: hidden;
    }

    .card-head {
      background: var(--navy);
      padding: 1.1rem 1.6rem;
      display: flex;
      align-items: center;
      gap: .75rem;
    }

    .card-head-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: var(--teal);
      flex-shrink: 0;
    }

    .card-head-title {
      color: var(--white);
      font-size: .9rem;
      font-weight: 700;
      letter-spacing: .01em;
    }

    .card-head-ver {
      margin-left: auto;
      color: var(--muted);
      font-size: .68rem;
    }

    .card-body {
      padding: 1.8rem 1.6rem;
    }

    /* ── FORM ──────────────────────────────────────── */
    .form-group {
      margin-bottom: 1.3rem;
    }

    .form-label {
      display: block;
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .06em;
      text-transform: uppercase;
      margin-bottom: .45rem;
      /* foreground label — BERUBAH setelah SAVE */
      color: var(--fg-default);
      transition: color .35s ease;
    }

    .form-label .req {
      color: var(--error);
      margin-left: 2px;
    }

    .form-input,
    .form-select {
      width: 100%;
      padding: .72rem .95rem;
      border: 2px solid var(--border);
      border-radius: 8px;
      font-size: .9rem;
      font-family: inherit;
      color: var(--navy);
      background: var(--off-white);
      outline: none;
      transition: border-color .2s, box-shadow .2s;
    }

    .form-input:focus,
    .form-select:focus {
      border-color: var(--teal);
      box-shadow: 0 0 0 3px rgba(13,158,138,.15);
      background: var(--white);
    }

    .form-input.is-error,
    .form-select.is-error {
      border-color: var(--error);
      box-shadow: 0 0 0 3px rgba(214,64,69,.12);
    }

    .error-msg {
      margin-top: .35rem;
      font-size: .75rem;
      color: var(--error);
      display: flex;
      align-items: center;
      gap: .3rem;
    }

    /* ── BUTTON ────────────────────────────────────── */
    .btn-save {
      width: 100%;
      padding: .85rem 1rem;
      background: var(--teal);
      border: none;
      border-radius: 8px;
      font-size: .95rem;
      font-weight: 700;
      font-family: inherit;
      letter-spacing: .03em;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .55rem;
      transition: background .2s, transform .1s, box-shadow .2s;
      /* foreground teks tombol — BERUBAH setelah SAVE */
      color: var(--white);
    }

    .btn-save:hover {
      background: var(--teal-dim);
      box-shadow: 0 4px 16px rgba(13,158,138,.30);
    }

    .btn-save:active {
      transform: scale(.98);
    }

    .btn-icon {
      font-size: 1rem;
    }

    /* ── DIVIDER ────────────────────────────────────── */
    .divider {
      border: none;
      border-top: 1px solid var(--border);
      margin: 1.5rem 0 1.2rem;
    }

    /* ── HASIL SIMPAN ───────────────────────────────── */
    /*
      FOREGROUND CHANGE — setelah klik SAVE / tekan Enter:
      Semua elemen teks utama berganti warna menjadi teal (--fg-saved).
      Background TIDAK berubah — tetap putih / off-white.
    */
    .result-card {
      display: none;                  /* hidden by default */
      border: 2px solid var(--border);
      border-radius: 10px;
      padding: 1.2rem 1.1rem;
      margin-top: 1.4rem;
      background: var(--white);       /* background TETAP putih */
    }

    .result-card.show {
      display: block;
    }

    .result-title {
      font-size: .72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: .9rem;
      /* FOREGROUND berubah ke teal */
      color: var(--fg-saved);
      transition: color .35s ease;
    }

    .result-row {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      padding: .42rem 0;
      border-bottom: 1px solid var(--off-white);
      font-size: .84rem;
    }

    .result-row:last-child {
      border-bottom: none;
    }

    .result-key {
      color: var(--muted);
      font-size: .75rem;
      font-weight: 600;
      letter-spacing: .04em;
      text-transform: uppercase;
      flex-shrink: 0;
      margin-right: 1rem;
    }

    .result-val {
      font-weight: 700;
      text-align: right;
      /* FOREGROUND berubah ke teal setelah SAVE */
      color: var(--fg-saved);
      transition: color .35s ease;
    }

    /* ── SAVED STATE — foreground shift ────────────── */
    /*
      Class .state-saved ditambahkan ke <body> via PHP ketika
      form berhasil disimpan. Semua selector di bawah mengubah
      warna TEKS (foreground) saja — background tidak disentuh.
    */
    body.state-saved .form-label {
      color: var(--fg-saved);           /* label → teal */
    }

    body.state-saved .page-intro h1 {
      color: var(--fg-saved);           /* judul halaman → teal */
    }

    body.state-saved .card-head-title {
      color: var(--teal-light);         /* judul card → teal-light */
    }

    body.state-saved .card-head-dot {
      background: var(--accent);        /* dot → amber sebagai penanda */
    }

    body.state-saved .btn-save {
      color: var(--navy);               /* teks tombol → navy */
      background: var(--teal-light);    /* hanya shade lebih terang */
    }

    body.state-saved .form-input,
    body.state-saved .form-select {
      color: var(--fg-saved);           /* nilai input → teal */
      border-color: var(--teal);
    }

    body.state-saved .result-card {
      border-color: var(--teal);
    }

    /* ── STATUS BADGE ───────────────────────────────── */
    .status-bar {
      display: flex;
      align-items: center;
      gap: .5rem;
      margin-bottom: 1.3rem;
      padding: .65rem .95rem;
      border-radius: 8px;
      font-size: .8rem;
      font-weight: 600;
    }

    .status-bar.success {
      background: rgba(13,158,138,.08);
      border: 1px solid rgba(13,158,138,.25);
      /* teks status — FOREGROUND teal */
      color: var(--teal-dim);
    }

    .status-bar.error {
      background: rgba(214,64,69,.07);
      border: 1px solid rgba(214,64,69,.2);
      color: var(--error);
    }

    .status-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      flex-shrink: 0;
    }

    .status-bar.success .status-dot { background: var(--teal); }
    .status-bar.error   .status-dot { background: var(--error); }

    /* ── HINT KEYBOARD ──────────────────────────────── */
    .kbd-hint {
      text-align: center;
      font-size: .72rem;
      color: var(--muted);
      margin-top: .65rem;
    }

    kbd {
      background: var(--off-white);
      border: 1px solid var(--border);
      border-radius: 4px;
      padding: .1rem .35rem;
      font-family: monospace;
      font-size: .7rem;
      color: var(--navy);
    }

    /* ── FOOTER ─────────────────────────────────────── */
    .page-footer {
      text-align: center;
      font-size: .72rem;
      color: var(--muted);
      margin-top: .5rem;
    }

    /* ── RESPONSIVE ─────────────────────────────────── */
    @media (max-width: 480px) {
      .card-body { padding: 1.4rem 1.1rem; }
      .site-header { padding: 0 1rem; }
    }
  </style>
</head>
<body class="<?= $saved ? 'state-saved' : '' ?>">

<!-- ═══════════════════════════════════════════ HEADER -->
<header class="site-header">
  <div class="header-logo">U</div>
  <div>
    <div class="header-title">USTI</div>
    <div class="header-sub">Universitas Sains Dan Teknologi Indonesia</div>
  </div>
  <span class="header-badge">SQA Project · Versi 2026-2</span>
</header>

<!-- ═══════════════════════════════════════════ CONTENT -->
<main class="page-wrap">

  <div class="page-intro">
    <h1>Entri Data Mahasiswa Baru</h1>
    <p>Isi formulir di bawah lalu klik tombol <strong>SAVE</strong> atau tekan <kbd>Enter</kbd>.<br>
       Perhatikan perubahan <em>foreground</em> setelah data tersimpan.</p>
  </div>

  <div class="card">
    <div class="card-head">
      <div class="card-head-dot"></div>
      <span class="card-head-title">Form Pendaftaran Mahasiswa Baru</span>
      <span class="card-head-ver">Prodi TI</span>
    </div>

    <div class="card-body">

      <?php if ($saved): ?>
        <!-- STATUS: BERHASIL DISIMPAN -->
        <div class="status-bar success">
          <span class="status-dot"></span>
          Data berhasil disimpan pada <?= htmlspecialchars($timestamp) ?> — foreground berubah ke teal.
        </div>
      <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !$saved): ?>
        <!-- STATUS: ADA ERROR -->
        <div class="status-bar error">
          <span class="status-dot"></span>
          Terdapat kesalahan pada formulir. Periksa kembali isian Anda.
        </div>
      <?php endif; ?>

      <!-- FORM -->
      <form method="POST" action="">

        <!-- Nama -->
        <div class="form-group">
          <label class="form-label" for="nama">
            Nama Calon Mahasiswa <span class="req">*</span>
          </label>
          <input
            type="text"
            id="nama"
            name="nama"
            class="form-input <?= $error_nama ? 'is-error' : '' ?>"
            placeholder="Contoh: Budi Santoso"
            value="<?= htmlspecialchars($nama) ?>"
            autocomplete="off"
          >
          <?php if ($error_nama): ?>
            <div class="error-msg">⚠ <?= htmlspecialchars($error_nama) ?></div>
          <?php endif; ?>
        </div>

        <!-- NIM -->
        <div class="form-group">
          <label class="form-label" for="nim">
            NIM (Nomor Induk Mahasiswa) <span class="req">*</span>
          </label>
          <input
            type="text"
            id="nim"
            name="nim"
            class="form-input <?= $error_nim ? 'is-error' : '' ?>"
            placeholder="Contoh: 2026001234"
            value="<?= htmlspecialchars($nim) ?>"
            autocomplete="off"
          >
          <?php if ($error_nim): ?>
            <div class="error-msg">⚠ <?= htmlspecialchars($error_nim) ?></div>
          <?php endif; ?>
        </div>

        <!-- Program Studi -->
        <div class="form-group">
          <label class="form-label" for="prodi">
            Program Studi <span class="req">*</span>
          </label>
          <select
            id="prodi"
            name="prodi"
            class="form-select <?= $error_prodi ? 'is-error' : '' ?>"
          >
            <option value="" disabled <?= $prodi === '' ? 'selected' : '' ?>>— Pilih Program Studi —</option>
            <option value="Teknik Informatika" <?= $prodi === 'Teknik Informatika' ? 'selected' : '' ?>>Teknik Informatika</option>
            <option value="Sistem Informasi"   <?= $prodi === 'Sistem Informasi'   ? 'selected' : '' ?>>Sistem Informasi</option>
            <option value="Teknik Elektro"     <?= $prodi === 'Teknik Elektro'     ? 'selected' : '' ?>>Teknik Elektro</option>
            <option value="Manajemen"          <?= $prodi === 'Manajemen'          ? 'selected' : '' ?>>Manajemen</option>
          </select>
          <?php if ($error_prodi): ?>
            <div class="error-msg">⚠ <?= htmlspecialchars($error_prodi) ?></div>
          <?php endif; ?>
        </div>

        <hr class="divider">

        <button type="submit" name="action" value="save" class="btn-save">
          <span class="btn-icon">💾</span>
          SAVE
        </button>

        <p class="kbd-hint">atau tekan <kbd>Enter</kbd> pada keyboard</p>

      </form>

      <!-- HASIL — ditampilkan setelah SAVE berhasil -->
      <?php if ($saved): ?>
        <div class="result-card show">
          <div class="result-title">✔ Data Tersimpan</div>

          <div class="result-row">
            <span class="result-key">Nama</span>
            <span class="result-val"><?= htmlspecialchars($nama) ?></span>
          </div>
          <div class="result-row">
            <span class="result-key">NIM</span>
            <span class="result-val"><?= htmlspecialchars($nim) ?></span>
          </div>
          <div class="result-row">
            <span class="result-key">Prodi</span>
            <span class="result-val"><?= htmlspecialchars($prodi) ?></span>
          </div>
          <div class="result-row">
            <span class="result-key">Disimpan pada</span>
            <span class="result-val"><?= htmlspecialchars($timestamp) ?></span>
          </div>
        </div>
      <?php endif; ?>

    </div><!-- /.card-body -->
  </div><!-- /.card -->

  <p class="page-footer">
    Prodi Teknik Informatika · Fakultas Teknik dan Informatika · USTI TA. 2026
  </p>

</main>

</body>
</html>
