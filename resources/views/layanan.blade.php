@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    <!-- Header Layanan -->
    <div class="row align-items-center mb-5 bg-white rounded-4 shadow-sm p-4 border-start border-primary border-5 mt-2">
        <div class="col-md-8">
            <h2 class="fw-bold mb-2">Katalog Layanan Cetak</h2>
            <p class="text-muted mb-0">Pesan sekarang, pilih opsi Pengantaran, biar kurir kami yang antar sampai ke rumah Anda!</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold">
                <i class="bi bi-truck me-1"></i> Support Delivery
            </span>
        </div>
    </div>

    <div class="row g-4 pb-5">
        @foreach($products as $p)
        <div class="col-md-6 col-lg-4 mb-2">
            <div class="card h-100 shadow-sm border rounded-4 overflow-hidden" style="transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 0.125rem 0.25rem rgba(0,0,0,0.075)';">
                <div class="position-relative">
                    @if($p->image)
                        <img src="{{ asset('images/' . $p->image) }}" class="card-img-top" alt="{{ $p->name }}" style="height: 220px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/logo_sky.png') }}" class="card-img-top" alt="{{ $p->name }}" style="height: 220px; object-fit: cover;">
                    @endif
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill shadow-sm">
                            Rp {{ number_format($p->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="card-body d-flex flex-column p-4">
                    <h5 class="fw-bold text-dark mb-2">{{ $p->name }}</h5>
                    <p class="text-muted small mb-4 flex-grow-1">{{ $p->description ?? 'Layanan cetak profesional berkualitas tinggi.' }}</p>
                    <button class="btn btn-primary w-100 shadow-sm rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#orderModal{{ $p->id }}">
                        <i class="bi bi-cart-plus me-1"></i> Pesan Sekarang
                    </button>
                </div>
            </div>
        </div>

            <div class="modal fade" id="orderModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Pesan {{ $p->name }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('cart.add') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body p-4">
                                <input type="hidden" name="product_id" value="{{ $p->id }}">

                                @if($p->name === 'Pas Foto')
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Variasi</label>
                                        <select name="variant" class="form-control" required>
                                            <option value="warna">Warna</option>
                                            <option value="hitam_putih">Hitam Putih</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Ukuran</label>
                                        <select name="size" class="form-control" required>
                                            <option value="2x3">2x3</option>
                                            <option value="3x4">3x4</option>
                                            <option value="4x6">4x6</option>
                                        </select>
                                        <small class="text-muted d-block mt-1">1 paket mendapatkan 5 foto (warna maupun hitam-putih).</small>
                                    </div>
                                @endif

                                @if($p->name === 'Fotocopy & Print')
                                    <div class="alert alert-info py-2">
                                        <strong>Fotocopy:</strong> Silahkan antarkan berkas ke toko untuk difotocopy.
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Variasi</label>
                                        <select name="variant" class="form-control" required>
                                            <option value="warna">Warna</option>
                                            <option value="hitam_putih">Hitam Putih</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Rangkap Berapa</label>
                                        <input type="number" name="copies" class="form-control" value="1" min="1" required>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jumlah Pesanan</label>
                                    <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                                </div>

                                @if($p->name === 'Jilid')
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Variasi Warna Jilid</label>
                                        <select name="binding_color" class="form-control" required>
                                            <option value="Hitam">Hitam</option>
                                            <option value="Biru">Biru</option>
                                            <option value="Merah">Merah</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Catatan</label>
                                        <textarea name="note" class="form-control" rows="3" placeholder="Contoh: file_undangan.pdf" required></textarea>
                                        <small class="text-muted d-block mt-1">Tuliskan nama file yang akan dijilid.</small>
                                    </div>
                                @elseif($p->name === 'Undangan')
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Jenis Undangan</label>
                                        <input type="text" name="jenis_undangan" class="form-control" placeholder="Contoh: Pernikahan / Ulang Tahun" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nama yang Mengundang</label>
                                        <input type="text" name="nama_pengundang" class="form-control" placeholder="Contoh: Romeo & Juliet" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Tanggal Acara</label>
                                            <input type="date" name="tanggal_acara" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Waktu Acara</label>
                                            <input type="time" name="waktu_acara" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Tempat / Alamat Acara</label>
                                        <textarea name="tempat_acara" class="form-control" rows="2" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Keterangan Tambahan</label>
                                        <textarea name="keterangan_tambahan" class="form-control" rows="2" placeholder="Detail khusus lainnya..."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Upload Referensi Konsep Undangan</label>
                                        <input type="file" name="document_file" id="upload-{{ $p->id }}" class="form-control" required onchange="previewFileGeneric(this, 'preview-global-{{ $p->id }}')">
                                        <div id="preview-global-{{ $p->id }}" class="mt-2 d-flex flex-wrap gap-2"></div>
                                        <small class="text-muted d-block mt-1">Format: PDF, DOCX, JPG, PNG (Maks. 10MB)</small>
                                    </div>
                                @elseif($p->name === 'Polaroid & Photostrip')
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Pilih Tipe</label>
                                        <select name="variant" class="form-control" onchange="togglePhotostrip(this, {{ $p->id }})" required>
                                            <option value="Polaroid">Polaroid (1 Paket = 10 foto) - Rp20.000</option>
                                            <option value="Photostrip">Photostrip (1 foto) - Rp5.000</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Photostrip Templates Section -->
                                    <div id="photostrip-templates-{{ $p->id }}" style="display: none;">
                                        <div class="alert alert-warning py-2 small">
                                            <strong>Khusus Photostrip:</strong> Silakan pilih 1 template di bawah. Note: Photostrip menggunakan 3 foto. Upload 3 foto berurutan (untuk 1 lembar) atau 6 foto (untuk bolak-balik).
                                        </div>
                                        <label class="form-label fw-bold">Pilih Template Photostrip</label>
                                        <div class="row g-2 mb-3 overflow-auto custom-scroll" style="max-height: 250px;">
                                            @foreach($templates ?? [] as $idx => $template)
                                                <div class="col-4 col-md-3">
                                                    <label class="d-block w-100 turn-cursor">
                                                        <input type="radio" name="photostrip_template" value="{{ basename($template) }}" class="d-none template-radio">
                                                        <div class="border rounded p-1 text-center template-card">
                                                            <img src="{{ asset($template) }}" class="img-fluid rounded border mb-1" style="height: 100px; object-fit: contain;">
                                                            <div class="small fw-semibold text-truncate">{{ basename($template, '.png') }}</div>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div id="polaroid-upload-{{ $p->id }}">
                                        <label class="form-label fw-bold">Upload Foto (Polaroid)</label>
                                        <div class="alert alert-info py-2 small">
                                            Kirim maksimal 10 foto (boleh kurang, minimal 1 foto).
                                        </div>
                                        <div class="row g-2 mb-3">
                                            @for($i = 1; $i <= 10; $i++)
                                                <div class="col-4 col-md-3">
                                                    <label class="d-block text-center border rounded p-2" style="cursor: pointer; border-style: dashed !important; background-color: #f8f9fa;">
                                                        <div id="preview-polaroid-{{ $p->id }}-{{ $i }}" style="height: 80px; display: flex; align-items: center; justify-content: center;">
                                                            <div class="text-muted small">
                                                                <i class="bi bi-camera d-block fs-4"></i>
                                                                Foto {{ $i }}
                                                            </div>
                                                        </div>
                                                        <input type="file" name="document_file[]" class="d-none polaroid-file-input" accept="image/*" onchange="previewImage(this, 'preview-polaroid-{{ $p->id }}-{{ $i }}')" {{ $i == 1 ? 'required' : '' }}>
                                                    </label>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>

                                    <div id="photostrip-upload-{{ $p->id }}" style="display: none;">
                                        <label class="form-label fw-bold">Upload Foto (Photostrip)</label>
                                        <div class="alert alert-info py-2 small">
                                            Kirim minimal 3 foto, maksimal 6 foto jika buat 2 photostrip.
                                        </div>
                                        <div class="row g-2 mb-3">
                                            @for($i = 1; $i <= 6; $i++)
                                                <div class="col-4">
                                                    <label class="d-block text-center border rounded p-2" style="cursor: pointer; border-style: dashed !important; background-color: #f8f9fa;">
                                                        <div id="preview-container-{{ $p->id }}-{{ $i }}" style="height: 80px; display: flex; align-items: center; justify-content: center;">
                                                            <div class="text-muted small">
                                                                <i class="bi bi-camera d-block fs-4"></i>
                                                                Foto {{ $i }}
                                                            </div>
                                                        </div>
                                                        <input type="file" name="photostrip_files[]" class="d-none photostrip-file-input" accept="image/*" onchange="previewImage(this, 'preview-container-{{ $p->id }}-{{ $i }}')" {{ $i <= 3 ? '' : '' }}>
                                                    </label>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                @elseif($p->name === 'Gantungan Kunci Photostrip')
                                    <div class="alert alert-success py-3 small">
                                        <strong><i class="bi bi-info-circle"></i> Petunjuk Gantungan Kunci Photostrip:</strong>
                                        <ul class="mb-2 mt-2 ps-3">
                                            <li><strong>Satu Sisi (3 Foto):</strong> Upload 3 foto. Kedua sisi gantungan kunci akan menampilkan foto-foto yang sama (mirrored).</li>
                                            <li><strong>Dua Sisi (6 Foto):</strong> Upload 6 foto. Sisi 1 menggunakan foto 1-3, sisi 2 menggunakan foto 4-6 (foto berbeda di setiap sisinya).</li>
                                        </ul>
                                        <strong>Tips:</strong> Gunakan template dan foto yang sama dengan produk Photostrip untuk konsistensi desain.
                                    </div>
                                    <label class="form-label fw-bold">Pilih Template Gantungan Kunci Photostrip</label>
                                    <div class="row g-2 mb-3 overflow-auto custom-scroll" style="max-height: 250px;">
                                        @foreach($keychain_templates ?? [] as $idx => $template)
                                            <div class="col-4 col-md-3">
                                                <label class="d-block w-100 turn-cursor">
                                                    <input type="radio" name="keychain_template" value="{{ basename($template) }}" class="d-none template-radio" required>
                                                    <div class="border rounded p-1 text-center template-card">
                                                        <img src="{{ asset($template) }}" class="img-fluid rounded border mb-1" style="height: 100px; object-fit: contain;">
                                                        <div class="small fw-semibold text-truncate">{{ basename($template, '.png') }}</div>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Pilih Tipe Gantungan Kunci</label>
                                        <select name="keychain_type" class="form-control" onchange="toggleKeychainPhotos(this, {{ $p->id }})" required>
                                            <option value="">-- Pilih Tipe --</option>
                                            <option value="3">Satu Sisi (3 Foto) - Kedua sisinya foto yang sama</option>
                                            <option value="6">Dua Sisi (6 Foto) - Masing-masing sisi berbeda foto</option>
                                        </select>
                                    </div>

                                    <label class="form-label fw-bold">Upload Foto Gantungan Kunci Photostrip</label>
                                    <div id="keychain-upload-{{ $p->id }}" style="display: none;">
                                        <div id="alert-keychain-{{ $p->id }}" class="alert alert-info py-2 small mb-3">
                                            <i class="bi bi-exclamation-circle"></i> Silakan pilih tipe gantungan kunci terlebih dahulu.
                                        </div>
                                        <div id="keychain-files-container-{{ $p->id }}" class="row g-2 mb-3">
                                            @for($i = 1; $i <= 6; $i++)
                                                <div class="col-4 keychain-file-col keychain-col-{{ $p->id }}" style="display: none;">
                                                    <label class="d-block text-center border rounded p-2" style="cursor: pointer; border-style: dashed !important; background-color: #f8f9fa;">
                                                        <div id="preview-keychain-{{ $p->id }}-{{ $i }}" style="height: 80px; display: flex; align-items: center; justify-content: center;">
                                                            <div class="text-muted small">
                                                                <i class="bi bi-camera d-block fs-4"></i>
                                                                Foto {{ $i }}
                                                            </div>
                                                        </div>
                                                        <input type="file" name="keychain_files[]" class="d-none keychain-file-input" accept="image/*" onchange="previewImage(this, 'preview-keychain-{{ $p->id }}-{{ $i }}')" required>
                                                    </label>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Upload File / Foto yang Ingin Dicetak</label>
                                        <input type="file" name="document_file" id="upload-{{ $p->id }}" class="form-control" required onchange="previewFileGeneric(this, 'preview-global-{{ $p->id }}')">
                                        <div id="preview-global-{{ $p->id }}" class="mt-2 d-flex flex-wrap gap-2"></div>
                                        <small class="text-muted d-block mt-1">Format: PDF, DOCX, JPG, PNG (Maks. 10MB)</small>
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary px-4 shadow">Tambah ke Keranjang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .template-card {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .template-card:hover {
        border-color: #0d6efd !important;
        background-color: #f8f9fa;
    }
    .template-radio:checked + .template-card {
        border-color: #0d6efd !important;
        background-color: #e9ecef;
        box-shadow: 0 0 0 2px #0d6efd;
    }
</style>

<script>
    function togglePhotostrip(selectElem, id) {
        const val = selectElem.value;
        const tmplSection = document.getElementById('photostrip-templates-' + id);
        const polaroidUpload = document.getElementById('polaroid-upload-' + id);
        const photostripUpload = document.getElementById('photostrip-upload-' + id);
        
        if (val === 'Photostrip') {
            tmplSection.style.display = 'block';
            polaroidUpload.style.display = 'none';
            photostripUpload.style.display = 'block';
            
            // Atur required form
            const tmplRadios = tmplSection.querySelectorAll('.template-radio');
            if(tmplRadios.length > 0) tmplRadios[0].required = true;
            
            const polInputs = polaroidUpload.querySelectorAll('.polaroid-file-input');
            polInputs.forEach(i => i.required = false);
            
            // Set 3 file photostrip pertama dlm satu paket wajib
            const pInputs = photostripUpload.querySelectorAll('.photostrip-file-input');
            for(let i=0; i<3; i++){
                if(pInputs[i]) pInputs[i].required = true;
            }
        } else {
            tmplSection.style.display = 'none';
            polaroidUpload.style.display = 'block';
            photostripUpload.style.display = 'none';
            
            const tmplRadios = tmplSection.querySelectorAll('.template-radio');
            tmplRadios.forEach(r => r.required = false);
            
            const polInputs = polaroidUpload.querySelectorAll('.polaroid-file-input');
            if(polInputs.length > 0) polInputs[0].required = true;
            
            const pInputs = photostripUpload.querySelectorAll('.photostrip-file-input');
            pInputs.forEach(i => i.required = false);
        }
    }

    function toggleKeychainPhotos(selectElem, id) {
        const val = selectElem.value;
        const keychainUpload = document.getElementById('keychain-upload-' + id);
        const alertKeychain = document.getElementById('alert-keychain-' + id);
        const keychainContainer = document.getElementById('keychain-files-container-' + id);
        
        if (val === '3') {
            keychainUpload.style.display = 'block';
            alertKeychain.innerHTML = '<i class="bi bi-info-circle"></i> Upload 3 foto untuk satu sisi. Kedua sisi gantungan kunci akan menampilkan foto yang sama.';
            
            // Show only 3 input boxes
            const cols = keychainContainer.querySelectorAll('.keychain-col-' + id);
            cols.forEach((col, index) => {
                col.style.display = index < 3 ? 'block' : 'none';
                const input = col.querySelector('.keychain-file-input');
                input.required = true;
            });
        } else if (val === '6') {
            keychainUpload.style.display = 'block';
            alertKeychain.innerHTML = '<i class="bi bi-info-circle"></i> Upload 6 foto. Foto 1-3 untuk sisi 1, foto 4-6 untuk sisi 2 (foto berbeda di setiap sisinya).';
            
            // Show all 6 input boxes
            const cols = keychainContainer.querySelectorAll('.keychain-col-' + id);
            cols.forEach((col, index) => {
                col.style.display = 'block';
                const input = col.querySelector('.keychain-file-input');
                input.required = true;
            });
        } else {
            keychainUpload.style.display = 'none';
            
            const cols = keychainContainer.querySelectorAll('.keychain-col-' + id);
            cols.forEach(col => {
                col.style.display = 'none';
                const input = col.querySelector('.keychain-file-input');
                input.required = false;
            });
        }
    }

    let currentReplacerAction = null;
    const globalReplacerInput = document.createElement('input');
    globalReplacerInput.type = 'file';
    globalReplacerInput.style.display = 'none';
    document.body.appendChild(globalReplacerInput);

    globalReplacerInput.onchange = function(e) {
        if (this.files && this.files[0] && currentReplacerAction) {
            const { input, index } = currentReplacerAction;
            const dt = new DataTransfer();
            for (let i = 0; i < input.files.length; i++) {
                if (i === index) dt.items.add(this.files[0]);
                else dt.items.add(input.files[i]);
            }
            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
        }
        this.value = '';
        currentReplacerAction = null;
    };

    window.triggerReplaceFile = function(inputId, index) {
        const input = document.getElementById(inputId);
        // Copy accept attr if Polaroid
        globalReplacerInput.accept = input.accept || '';
        currentReplacerAction = { input, index };
        globalReplacerInput.click();
    };

    window.removeFileFromInput = function(inputId, index) {
        const input = document.getElementById(inputId);
        const dt = new DataTransfer();
        for (let i = 0; i < input.files.length; i++) {
            if (i !== index) {
                dt.items.add(input.files[i]);
            }
        }
        input.files = dt.files;
        input.dispatchEvent(new Event('change'));
    };

    window.openFileTab = function(url, name) {
        if(url.startsWith('data:image')){
            showPreview(url);
        } else {
            const link = document.createElement('a');
            link.href = url;
            link.download = name;
            link.click();
        }
    };

    function toggleKeychainPhotos(selectElem, id) {
        const val = selectElem.value;
        const uploadSection = document.getElementById('keychain-upload-' + id);
        const alertBox = document.getElementById('alert-keychain-' + id);
        const container = document.getElementById('keychain-files-container-' + id);
        
        if (val === '3' || val === '6') {
            uploadSection.style.display = 'block';
            const numPhotos = parseInt(val);
            
            // Update alert text
            if (numPhotos === 3) {
                alertBox.innerHTML = '<strong>Satu Sisi (3 Foto):</strong> Upload 3 foto yang sama untuk kedua sisi gantungan kunci.';
            } else {
                alertBox.innerHTML = '<strong>Dua Sisi (6 Foto):</strong> Upload 6 foto berbeda (3 untuk sisi depan, 3 untuk sisi belakang).';
            }
            
            // Show/hide columns based on selection
            const allCols = container.querySelectorAll('.keychain-file-col');
            allCols.forEach((col, index) => {
                if (index < numPhotos) {
                    col.style.display = 'block';
                    const input = col.querySelector('.keychain-file-input');
                    input.required = true;
                } else {
                    col.style.display = 'none';
                    const input = col.querySelector('.keychain-file-input');
                    input.required = false;
                    // Clear file
                    input.value = '';
                    const previewId = col.querySelector('[id^="preview-keychain"]').id;
                    document.getElementById(previewId).innerHTML = `<div class="text-muted small"><i class="bi bi-camera d-block fs-4"></i>Foto</div>`;
                }
            });
        } else {
            uploadSection.style.display = 'none';
            alertBox.innerHTML = 'Silakan pilih tipe gantungan kunci terlebih dahulu.';
            
            // Reset all inputs
            const allInputs = container.querySelectorAll('.keychain-file-input');
            allInputs.forEach(input => {
                input.required = false;
                input.value = '';
            });
        }
    }

    function previewImage(input, previewContainerId) {
        const container = document.getElementById(previewContainerId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                container.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px; cursor:pointer;" onclick="showPreview('${e.target.result}')" title="Klik untuk lihat besar">`;
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            container.innerHTML = `<div class="text-muted small"><i class="bi bi-camera d-block fs-4"></i>Foto</div>`;
        }
    }

    function previewFileGeneric(input, containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        if (input.files) {
            Array.from(input.files).forEach((file, index) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'border shadow-sm rounded bg-white p-1 d-flex flex-column mb-2 me-2';
                let isImg = file.type.startsWith('image/');
                wrapper.style.cssText = isImg ? 'width: 80px;' : 'width: 80px; text-align: center; overflow: hidden;';
                container.appendChild(wrapper);

                const reader = new FileReader();
                reader.onload = function(e){
                    let displayHtml = isImg 
                        ? `<img src="${e.target.result}" style="height: 60px; width: 100%; object-fit: contain;" class="rounded" title="${file.name}">` 
                        : `<i class="bi bi-file-earmark-text fs-1 text-primary"></i>
                           <div class="small text-truncate mt-1 px-1" style="width: 70px;" title="${file.name}">${file.name}</div>`;
                    
                    wrapper.innerHTML = `
                        <div style="height: 60px; display: flex; align-items: center; justify-content: center;" class="turn-cursor" onclick="openFileTab('${e.target.result}', '${file.name}')">
                            ${displayHtml}
                        </div>
                        <div class="d-flex justify-content-between mt-1 pt-1 border-top">
                            <button type="button" class="btn btn-sm text-warning p-0 turn-cursor" onclick="triggerReplaceFile('${input.id}', ${index})" title="Ganti Berkas / Foto"><i class="bi bi-pencil-square"></i></button>
                            <button type="button" class="btn btn-sm text-danger p-0 turn-cursor" onclick="removeFileFromInput('${input.id}', ${index})" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    `;
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>

@endsection