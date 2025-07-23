<?php require_once '../app/views/inc/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center mb-0">Kontak Kami</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Informasi Kontak</h4>
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-map-marker-alt fa-2x text-primary me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Alamat</h6>
                                        <p class="mb-0">Jl. Contoh No. 123<br>Jakarta Pusat, DKI Jakarta 10110</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-phone fa-2x text-primary me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Telepon</h6>
                                        <p class="mb-0">(021) 123-4567</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-envelope fa-2x text-primary me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Email</h6>
                                        <p class="mb-0">info@pengaduan.com</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-clock fa-2x text-primary me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Jam Operasional</h6>
                                        <p class="mb-0">Senin - Jumat: 08:00 - 17:00<br>Sabtu: 08:00 - 12:00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h4>Kirim Pesan</h4>
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subjek</label>
                                    <input type="text" class="form-control" id="subject" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="message" class="form-label">Pesan</label>
                                    <textarea class="form-control" id="message" rows="5" required></textarea>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Frequently Asked Questions (FAQ)</h4>
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faq1">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                            Bagaimana cara membuat pengaduan?
                                        </button>
                                    </h2>
                                    <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Untuk membuat pengaduan, Anda perlu mendaftar terlebih dahulu, kemudian login dan klik "Buat Pengaduan" di dashboard Anda.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faq2">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                            Berapa lama pengaduan akan diproses?
                                        </button>
                                    </h2>
                                    <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Pengaduan akan diverifikasi dalam 1x24 jam dan ditindaklanjuti sesuai dengan tingkat urgensi dan kompleksitas masalah.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faq3">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                            Apakah saya bisa melacak status pengaduan?
                                        </button>
                                    </h2>
                                    <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Ya, Anda dapat melacak status pengaduan melalui dashboard setelah login. Status akan diperbarui secara real-time.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/inc/footer.php'; ?>
