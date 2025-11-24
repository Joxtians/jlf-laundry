<?php 
$pageTitle = "JLF & CO Laundry - Premium Care";
include 'includes/header.php'; 
include 'includes/navbar.php'; 
?>

<div id="beranda"></div> <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>
    
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="assets/images/slide1.jpeg" alt="JLF Premium Laundry">
            <div class="carousel-caption text-center">
                <h1 class="text-warning">JLF & CO Premium Laundry</h1>
                <p class="text-light mb-4">Perawatan terbaik untuk pakaian kesayangan Anda dengan teknologi modern.</p>
                <a href="login.php" class="btn btn-primary-custom btn-lg px-5 rounded-pill shadow">Pesan Sekarang</a>
            </div>
        </div>

        <div class="carousel-item">
            <img src="assets/images/slide2.jpeg" alt="Layanan Antar Jemput">
            <div class="carousel-caption text-center">
                <h1 class="text-white">Sibuk? Kami Jemput!</h1>
                <p class="text-light mb-4">Layanan antar jemput gratis khusus untuk Member VIP.</p>
                <a href="#layanan" class="btn btn-outline-light btn-lg px-5 rounded-pill">Lihat Layanan</a>
            </div>
        </div>

        <div class="carousel-item">
            <img src="assets/images/slide3.jpeg" alt="Profesional Dry Clean">
            <div class="carousel-caption text-center">
                <h1 class="text-white">Professional Dry Clean</h1>
                <p class="text-light mb-4">Ahlinya merawat jas, gaun pesta, dan bahan sensitif lainnya.</p>
                <a href="login.php" class="btn btn-accent btn-lg px-5 rounded-pill shadow">Gabung Member</a>
            </div>
        </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
</div>

<section class="py-5 bg-white position-relative" style="margin-top: -50px; z-index: 10;">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-box text-center">
                    <div class="icon-circle"><i class="bi bi-stopwatch"></i></div>
                    <h5 class="fw-bold">Tepat Waktu</h5>
                    <p class="text-muted small">Pilihan durasi pengerjaan yang fleksibel sesuai kebutuhan Anda.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box text-center">
                    <div class="icon-circle"><i class="bi bi-gem"></i></div>
                    <div class="badge bg-warning text-dark mb-2">Best Quality</div>
                    <h5 class="fw-bold">Perawatan Premium</h5>
                    <p class="text-muted small">Menggunakan deterjen ramah lingkungan yang menjaga serat kain.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box text-center">
                    <div class="icon-circle"><i class="bi bi-truck"></i></div>
                    <h5 class="fw-bold">Gratis Antar Jemput</h5>
                    <p class="text-muted small">Khusus member, nikmati layanan delivery tanpa biaya tambahan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="layanan" class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary-custom fw-bold ls-2">PRICELIST</h6>
            <h2 class="fw-bold display-6">Pilihan Paket Laundry</h2>
            <p class="text-muted">Harga terbaik untuk kualitas premium</p>
        </div>

        <div class="row g-4 justify-content-center">
            
            <div class="col-lg-3 col-md-6">
                <div class="card service-card border-0 h-100 p-3 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-light rounded-circle d-inline-flex p-3 mb-3 text-primary-custom">
                            <i class="bi bi-basket2 fs-2"></i>
                        </div>
                        <h4 class="fw-bold mt-2">Standard</h4>
                        <p class="text-muted small">Pengerjaan 2 Hari. Cocok untuk pakaian santai.</p>
                        <hr>
                        <h5 class="text-primary-custom fw-bold">Rp 6.000 <small class="text-muted fw-normal">/kg</small></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card service-card border-0 h-100 p-3 shadow-sm position-relative overflow-hidden">
                    <div class="badge bg-danger position-absolute top-0 end-0 m-3">POPULER</div>
                    <div class="card-body text-center">
                        <div class="bg-warning rounded-circle d-inline-flex p-3 mb-3 text-white">
                            <i class="bi bi-lightning-charge fs-2"></i>
                        </div>
                        <h4 class="fw-bold mt-2">Express</h4>
                        <p class="text-muted small">Pengerjaan 1 Hari. Solusi cepat untuk Anda yang sibuk.</p>
                        <hr>
                        <h5 class="text-primary-custom fw-bold">Rp 10.000 <small class="text-muted fw-normal">/kg</small></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card service-card border-0 h-100 p-3 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-primary-custom rounded-circle d-inline-flex p-3 mb-3 text-white">
                            <i class="bi bi-stopwatch fs-2"></i>
                        </div>
                        <h4 class="fw-bold mt-2">Kilat 6 Jam</h4>
                        <p class="text-muted small">Super cepat! Pagi taruh, sore bisa langsung dipakai.</p>
                        <hr>
                        <h5 class="text-primary-custom fw-bold">Rp 15.000 <small class="text-muted fw-normal">/kg</small></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card service-card border-0 h-100 p-3 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-dark rounded-circle d-inline-flex p-3 mb-3 text-white">
                            <i class="bi bi-suit-club fs-2"></i>
                        </div>
                        <h4 class="fw-bold mt-2">Dry Clean</h4>
                        <p class="text-muted small">Perawatan satuan untuk Jas, Gaun, dan bahan khusus.</p>
                        <hr>
                        <h5 class="text-primary-custom fw-bold">Rp 20.000 <small class="text-muted fw-normal">/pcs</small></h5>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="py-5 position-relative" style="background: linear-gradient(135deg, var(--primary-color) 0%, #0f172a 100%); color: white; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: var(--secondary-color); opacity: 0.1; border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -50px; left: -50px; width: 200px; height: 200px; background: white; opacity: 0.05; border-radius: 50%;"></div>

    <div class="container py-4 position-relative z-2">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h5 class="text-warning fw-bold ls-2 mb-3">KEISTIMEWAAN MEMBER</h5>
                <h2 class="display-5 fw-bold mb-3">Jadi Bagian Dari Keluarga Kami</h2>
                <p class="lead opacity-90 mb-4" style="font-style: italic;">
                    "Dengan Anda menjadi bagian kami, nikmati kelebihan yang kami berikan dengan leluasa."
                </p>
                <div class="row g-4 mt-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-white text-primary rounded-circle p-2 me-3"><i class="bi bi-percent"></i></div>
                            <span>Diskon Voucher Spesial</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-white text-primary rounded-circle p-2 me-3"><i class="bi bi-truck"></i></div>
                            <span>Prioritas Antar Jemput</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="card border-0 bg-white/10 p-4 rounded-4" style="backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                    <h4 class="fw-bold text-white mb-3">Gabung Sekarang</h4>
                    <p class="text-white-50 small mb-4">Dapatkan pengalaman laundry premium tanpa ribet.</p>
                    <a href="register.php" class="btn btn-warning w-100 fw-bold py-3 shadow">Daftar Member</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="cs" class="py-5 bg-white text-center">
    <div class="container">
        <h3 class="fw-bold mb-4">Siap Mencuci Hari Ini?</h3>
        
        <a href="https://wa.me/6283124932816" 
           target="_blank" 
           class="btn btn-success btn-lg rounded-pill px-5 shadow transition-hover">
            <i class="bi bi-whatsapp me-2"></i> Hubungi Kami via WhatsApp
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>