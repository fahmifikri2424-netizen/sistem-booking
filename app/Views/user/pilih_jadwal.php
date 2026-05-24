<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('user/layanan') ?>" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h4 class="fw-bold">Pilih Jadwal & Barbershop</h4>
    <p class="text-muted">Layanan: <span class="fw-semibold text-primary"><?= esc($service['nama']) ?></span></p>
</div>

<!-- Pilih Tanggal/Jam -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h5 class="card-title mb-0"><i class="bi bi-calendar-check me-2 text-primary"></i>1. Pilih Jadwal Tersedia</h5>
    </div>
    <div class="card-body">
        <?php if (empty($schedules)): ?>
            <div class="alert alert-warning">Mohon maaf, saat ini tidak ada jadwal yang tersedia.</div>
        <?php else: ?>
            <?php 
            // Kelompokkan jadwal berdasarkan tanggal
            $groupedSchedules = [];
            foreach ($schedules as $s) {
                $groupedSchedules[$s['tanggal']][] = $s;
            }
            ?>
            
            <div class="accordion" id="accordionSchedules">
                <?php $i = 0; foreach ($groupedSchedules as $tgl => $slots): ?>
                    <div class="accordion-item border-0 mb-2 shadow-sm rounded">
                        <h2 class="accordion-header" id="heading<?= $i ?>">
                            <button class="accordion-button <?= $i == 0 ? '' : 'collapsed' ?> bg-light fw-semibold rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>">
                                <i class="bi bi-calendar3 me-2"></i> <?= date('l, d F Y', strtotime($tgl)) ?>
                                <span class="badge bg-primary ms-auto me-2"><?= count($slots) ?> slot</span>
                            </button>
                        </h2>
                        <div id="collapse<?= $i ?>" class="accordion-collapse collapse <?= $i == 0 ? 'show' : '' ?>" data-bs-parent="#accordionSchedules">
                            <div class="accordion-body">
                                <div class="row g-2">
                                    <?php foreach ($slots as $slot): ?>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-outline-primary btn-schedule-select" 
                                                    data-id="<?= $slot['id_schedule'] ?>"
                                                    data-waktu="<?= date('d M Y', strtotime($tgl)) . ' | ' . substr($slot['jam_mulai'], 0, 5) . ' - ' . substr($slot['jam_selesai'], 0, 5) ?>">
                                                <i class="bi bi-clock me-1"></i>
                                                <?= substr($slot['jam_mulai'], 0, 5) ?>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $i++; endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Pilih Staff -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h5 class="card-title mb-0"><i class="bi bi-person-badge me-2 text-primary"></i>2. Pilih Barber</h5>
    </div>
    <div class="card-body">
        <?php if (empty($staffs)): ?>
            <div class="alert alert-warning">Data barber belum tersedia.</div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($staffs as $staff): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border border-primary-subtle h-100 staff-card" style="cursor: pointer;" 
                             data-id="<?= $staff['id_staff'] ?>" data-nama="<?= esc($staff['nama']) ?>">
                            <div class="card-body text-center py-4">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3 fw-bold fs-4"
                                     style="width:64px;height:64px;">
                                    <?= strtoupper(substr($staff['nama'], 0, 1)) ?>
                                </div>
                                <h6 class="fw-bold mb-1"><?= esc($staff['nama']) ?></h6>
                                <p class="text-muted small mb-0"><i class="bi bi-stars me-1 text-warning"></i><?= esc($staff['spesialisasi']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Lanjutkan Booking Form -->
<form action="<?= base_url('user/form-booking') ?>" method="get" id="formLanjut" class="d-none">
    <input type="hidden" name="id_service" value="<?= $service['id_service'] ?>">
    <input type="hidden" name="id_schedule" id="input_id_schedule" value="">
    <input type="hidden" name="id_staff" id="input_id_staff" value="">
</form>

<div class="card border-0 shadow-sm bg-primary text-white sticky-bottom mb-4" style="bottom: 20px; z-index: 1000; display: none;" id="lanjutCard">
    <div class="card-body d-flex align-items-center justify-content-between">
        <div>
            <h6 class="mb-1 fw-bold">Siap Lanjut!</h6>
            <div class="small opacity-75" id="ringkasanPilihan"></div>
        </div>
        <button type="button" class="btn btn-light text-primary fw-bold px-4 rounded-pill" onclick="submitFormLanjut()">
            Lanjut <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </div>
</div>

<script>
    let selectedScheduleId = null;
    let selectedScheduleText = '';
    let selectedStaffId = null;
    let selectedStaffName = '';

    document.querySelectorAll('.btn-schedule-select').forEach(btn => {
        btn.addEventListener('click', function() {
            // Reset others
            document.querySelectorAll('.btn-schedule-select').forEach(b => {
                b.classList.remove('btn-primary', 'text-white');
                b.classList.add('btn-outline-primary');
            });
            // Select this
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary', 'text-white');
            
            selectedScheduleId = this.dataset.id;
            selectedScheduleText = this.dataset.waktu;
            checkSelection();
        });
    });

    document.querySelectorAll('.staff-card').forEach(card => {
        card.addEventListener('click', function() {
            // Reset others
            document.querySelectorAll('.staff-card').forEach(c => {
                c.classList.remove('border-primary', 'bg-primary-subtle');
                c.classList.add('border-primary-subtle');
            });
            // Select this
            this.classList.remove('border-primary-subtle');
            this.classList.add('border-primary', 'bg-primary-subtle');
            
            selectedStaffId = this.dataset.id;
            selectedStaffName = this.dataset.nama;
            checkSelection();
        });
    });

    function checkSelection() {
        if (selectedScheduleId && selectedStaffId) {
            document.getElementById('input_id_schedule').value = selectedScheduleId;
            document.getElementById('input_id_staff').value = selectedStaffId;
            document.getElementById('ringkasanPilihan').innerHTML = `<i class="bi bi-calendar-event me-1"></i> ${selectedScheduleText} &nbsp;|&nbsp; <i class="bi bi-person me-1"></i> ${selectedStaffName}`;
            document.getElementById('lanjutCard').style.display = 'block';
        }
    }

    function submitFormLanjut() {
        if (!selectedScheduleId) {
            alert('Silakan pilih jadwal terlebih dahulu.');
            return;
        }
        if (!selectedStaffId) {
            alert('Silakan pilih barber terlebih dahulu.');
            return;
        }
        document.getElementById('formLanjut').submit();
    }
</script>

<?= $this->endSection() ?>
