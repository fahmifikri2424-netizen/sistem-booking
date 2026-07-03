<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; color: #333; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 24px; font-weight: 700; margin-bottom: 6px; }
        .header p { color: #a0aec0; font-size: 14px; }
        .badge-success { display: inline-block; background: #22c55e; color: white; padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 600; margin-top: 12px; }
        .body { padding: 36px 30px; }
        .greeting { font-size: 16px; color: #4a5568; margin-bottom: 20px; line-height: 1.6; }
        .greeting strong { color: #1a1a2e; }
        .card-info { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 24px; margin-bottom: 24px; }
        .card-info h3 { font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; color: #718096; margin-bottom: 16px; font-weight: 600; }
        .info-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #edf2f7; }
        .info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .info-label { font-size: 13px; color: #718096; }
        .info-value { font-size: 14px; color: #2d3748; font-weight: 600; text-align: right; max-width: 60%; }
        .total-row { background: #1a1a2e; border-radius: 8px; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; margin-top: 16px; }
        .total-label { color: #a0aec0; font-size: 14px; }
        .total-amount { color: #22c55e; font-size: 22px; font-weight: 700; }
        .kode-booking { background: #eef2ff; border: 2px dashed #6366f1; border-radius: 8px; padding: 16px; text-align: center; margin: 20px 0; }
        .kode-booking p { font-size: 12px; color: #6366f1; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
        .kode-booking span { font-size: 22px; font-weight: 700; color: #1a1a2e; font-family: 'Courier New', monospace; letter-spacing: 2px; }
        .info-note { background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 0 8px 8px 0; padding: 14px 18px; margin: 20px 0; }
        .info-note p { font-size: 13px; color: #92400e; line-height: 1.6; }
        .btn-cta { display: block; text-align: center; margin: 24px 0; }
        .btn-cta a { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; padding: 14px 36px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px; display: inline-block; }
        .footer { background: #f8fafc; padding: 24px 30px; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer p { font-size: 12px; color: #a0aec0; line-height: 1.8; }
        .footer strong { color: #718096; }
        @media (max-width: 600px) {
            .body { padding: 24px 16px; }
            .info-row { flex-direction: column; align-items: flex-start; gap: 4px; }
            .info-value { text-align: left; max-width: 100%; }
            .total-amount { font-size: 18px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">

        <!-- Header -->
        <div class="header">
            <h1>✂️ Barbershop Booking</h1>
            <p>Pembayaran berhasil dikonfirmasi</p>
            <span class="badge-success">✅ LUNAS</span>
        </div>

        <!-- Body -->
        <div class="body">

            <p class="greeting">
                Halo, <strong><?= esc($booking['nama_pelanggan']) ?></strong>! 👋<br><br>
                Terima kasih! Pembayaran Anda untuk booking barbershop telah berhasil kami terima dan dikonfirmasi.
                Berikut adalah detail transaksi Anda:
            </p>

            <!-- Kode Booking -->
            <div class="kode-booking">
                <p>Kode Booking Anda</p>
                <span><?= esc($booking['kode_booking']) ?></span>
            </div>

            <!-- Detail Layanan -->
            <div class="card-info">
                <h3>🗓️ Detail Jadwal</h3>
                <div class="info-row">
                    <span class="info-label">Layanan</span>
                    <span class="info-value"><?= esc($booking['nama_service']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Barber</span>
                    <span class="info-value"><?= esc($booking['nama_staff']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal</span>
                    <span class="info-value"><?= date('l, d F Y', strtotime($booking['tanggal'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jam</span>
                    <span class="info-value"><?= substr($booking['jam_mulai'], 0, 5) ?> – <?= substr($booking['jam_selesai'], 0, 5) ?> WIB</span>
                </div>
                <?php if (!empty($booking['catatan'])): ?>
                <div class="info-row">
                    <span class="info-label">Catatan</span>
                    <span class="info-value"><?= esc($booking['catatan']) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Total Pembayaran -->
            <div class="total-row">
                <span class="total-label">Total Pembayaran</span>
                <span class="total-amount">Rp <?= number_format($booking['harga'], 0, ',', '.') ?></span>
            </div>

            <!-- Catatan penting -->
            <div class="info-note">
                <p>
                    ⏰ Harap datang <strong>10-15 menit sebelum jadwal</strong> Anda.<br>
                    Jika Anda memerlukan bantuan, hubungi kami dengan menyertakan kode booking di atas.
                </p>
            </div>

            <!-- CTA Button -->
            <div class="btn-cta">
                <a href="<?= base_url('user/riwayat') ?>">Lihat Riwayat Booking</a>
            </div>

        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                Email ini dikirim otomatis oleh sistem.<br>
                Jangan balas email ini.<br><br>
                <strong>Barbershop Booking System</strong><br>
                &copy; <?= date('Y') ?> — Hak cipta dilindungi.
            </p>
        </div>

    </div>
</body>
</html>
