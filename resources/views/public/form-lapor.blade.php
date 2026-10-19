<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGADUBOSE - Buat Laporan Pengaduan Baru</title>
    
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#F0F7FF] text-slate-800 font-sans min-h-screen flex flex-col">

    @include('components.navbar')

    <main class="max-w-4xl w-full mx-auto px-6 py-12 flex-grow">
        
        @if(session('sukses'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl text-emerald-800 text-sm font-medium shadow-sm flex items-start gap-3">
                <i class="fas fa-check-circle text-emerald-600 mt-0.5 text-lg"></i>
                <div>
                    <p class="font-bold">Berhasil Terkirim!</p>
                    <p class="text-xs text-emerald-700 mt-0.5">{{ session('sukses') }}</p>
                    <a href="{{ url('/lacak-status') }}" class="text-xs text-emerald-600 underline font-semibold mt-2 inline-block hover:text-emerald-800">
                        Lacak Status Progres Laporan Anda &rarr;
                    </a>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl text-red-800 text-sm font-medium shadow-sm">
                <p class="font-bold">Terjadi Kesalahan Validasi!</p>
                <ul class="list-disc list-inside text-xs mt-1 text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-[#002B5B] p-6 text-white text-center">
                <h2 class="text-xl font-bold">Formulir Layanan Whistleblowing System</h2>
                <p class="text-xs text-blue-200 mt-1">Sampaikan laporan, aspirasi, atau saran Anda secara valid, aman, dan rahasia.</p>
            </div>

            <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Klasifikasi Layanan <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <label class="klasifikasi-card border-2 rounded-xl p-4 flex items-start gap-3 cursor-pointer transition relative border-slate-200 has-[:checked]:border-[#002B5B] has-[:checked]:bg-blue-50/50">
                            <input type="radio" name="klasifikasi" value="Pengaduan" {{ old('klasifikasi', 'Pengaduan') == 'Pengaduan' ? 'checked' : '' }} class="mt-1 accent-[#002B5B] klasifikasi-radio">
                            <div>
                                <span class="block text-sm font-bold text-slate-800">Pengaduan</span>
                                <span class="block text-[11px] text-slate-500 leading-tight mt-0.5">Laporan dugaan pelanggaran hukum/disiplin pegawai BPS Bantul.</span>
                            </div>
                        </label>

                        <label class="klasifikasi-card border-2 rounded-xl p-4 flex items-start gap-3 cursor-pointer transition relative border-slate-200 has-[:checked]:border-[#002B5B] has-[:checked]:bg-blue-50/50">
                            <input type="radio" name="klasifikasi" value="Aspirasi" {{ old('klasifikasi') == 'Aspirasi' ? 'checked' : '' }} class="mt-1 accent-[#002B5B] klasifikasi-radio">
                            <div>
                                <span class="block text-sm font-bold text-slate-800">Aspirasi</span>
                                <span class="block text-[11px] text-slate-500 leading-tight mt-0.5">Ide, harapan, atau masukan demi kemajuan organisasi BPS Bantul.</span>
                            </div>
                        </label>

                        <label class="klasifikasi-card border-2 rounded-xl p-4 flex items-start gap-3 cursor-pointer transition relative border-slate-200 has-[:checked]:border-[#002B5B] has-[:checked]:bg-blue-50/50">
                            <input type="radio" name="klasifikasi" value="Informasi / Saran" {{ old('klasifikasi') == 'Informasi / Saran' ? 'checked' : '' }} class="mt-1 accent-[#002B5B] klasifikasi-radio">
                            <div>
                                <span class="block text-sm font-bold text-slate-800">Informasi / Saran</span>
                                <span class="block text-[11px] text-slate-500 leading-tight mt-0.5">Permintaan info umum atau saran perbaikan pelayanan publik.</span>
                            </div>
                        </label>

                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1" id="label-judul">Judul Laporan / Ringkasan Masalah <span class="text-red-500">*</span></label>
                    <input type="text" name="judul_laporan" value="{{ old('judul_laporan') }}" required placeholder="Contoh: Dugaan penyimpangan anggaran atau saran fasilitas" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#002B5B]">
                    <span class="text-[11px] text-gray-400 mt-1 block" id="sublabel-judul">Tuliskan judul atau inti ringkasan laporan Anda secara singkat.</span>
                </div>

                <hr class="border-slate-100">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Pelapor</label>
                        <input type="text" name="nama_pelapor" value="{{ old('nama_pelapor') }}" placeholder="Kosongkan jika ingin Anonim" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#002B5B]">
                        <span class="text-[11px] text-gray-400 mt-1 block">Identitas Anda dijamin aman & rahasia 100% oleh sistem.</span>
                    </div>
                    <div id="field-terlapor">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Terlapor / Oknum <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_terlapor" id="input_terlapor" value="{{ old('nama_terlapor') }}" placeholder="Nama pegawai BPS yang dilaporkan" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#002B5B]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div id="field-jenis-pengaduan">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Pengaduan / Topik Laporan <span class="text-red-500">*</span></label>
                        <select name="jenis_pengaduan" id="select_jenis_pengaduan" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:border-[#002B5B]">
                            <option value="">-- Pilih Jenis Pengaduan --</option>
                            <option value="gratifikasi" {{ old('jenis_pengaduan') == 'gratifikasi' ? 'selected' : '' }}>Gratifikasi / Kasus Suap</option>
                            <option value="benturan_kepentingan" {{ old('jenis_pengaduan') == 'benturan_kepentingan' ? 'selected' : '' }}>Benturan Kepentingan (Conflict of Interest)</option>
                            <option value="korupsi" {{ old('jenis_pengaduan') == 'korupsi' ? 'selected' : '' }}>Tindak Pidana Korupsi</option>
                            <option value="pelanggaran_aturan" {{ old('jenis_pengaduan') == 'pelanggaran_aturan' ? 'selected' : '' }}>Pelanggaran Aturan / Disiplin Kerja</option>
                            <option value="lainnya" {{ old('jenis_pengaduan') == 'lainnya' ? 'selected' : '' }}>Lainnya / Masalah Umum</option>
                        </select>
                    </div>
                    <div id="field-tanggal-kejadian">
                        <label class="block text-sm font-semibold text-slate-700 mb-1" id="label-tanggal">Tanggal Kejadian <span class="text-red-500" id="star-tanggal">*</span></label>
                        <input type="date" name="tanggal_kejadian" id="input_tanggal" value="{{ old('tanggal_kejadian') }}" max="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#002B5B]">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1" id="label-uraian">Uraian Kronologi Kejadian <span class="text-red-500">*</span></label>
                    <textarea name="uraian_kronologi" rows="5" required placeholder="Tuliskan uraian.." class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#002B5B] leading-relaxed">{{ old('uraian_kronologi') }}</textarea>
                </div>
                
                <div id="field-bukti-pendukung">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Bukti Pendukung <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>

                    <div class="border border-slate-200 rounded-xl p-5 bg-slate-50">
                        <input
                            type="file"
                            name="bukti_pelaporan[]"
                            accept=".jpg,.jpeg,.png,.pdf"
                            multiple
                            class="block w-full text-sm text-slate-700
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-lg file:border-0
                                file:bg-[#002B5B] file:text-white
                                hover:file:bg-[#003b7a]"
                        >
                        <div id="preview-container" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-3"></div>

                        <p class="text-xs text-gray-500 mt-2">
                            Maksimal 5 MB (JPG, JPEG, PNG, PDF)
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    @error('g-recaptcha-response')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-md shadow-emerald-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                        </svg>
                        Kirim Pengaduan Ke Sistem
                    </button>
                </div>

            </form>
        </div>
    </main>

    @include('components.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const radioKlasifikasi = document.querySelectorAll('.klasifikasi-radio');
            const fieldTerlapor = document.getElementById('field-terlapor');
            const fieldJenisPengaduan = document.getElementById('field-jenis-pengaduan');
            const fieldTanggalKejadian = document.getElementById('field-tanggal-kejadian');
            const fieldBuktiPendukung = document.getElementById('field-bukti-pendukung'); // Tambahan variabel bukti pendukung
            
            const inputTerlapor = document.getElementById('input_terlapor');
            const selectJenisPengaduan = document.getElementById('select_jenis_pengaduan');
            
            const labelJudul = document.getElementById('label-judul');
            const sublabelJudul = document.getElementById('sublabel-judul');
            const labelUraian = document.getElementById('label-uraian');
            
            const inputTanggal = document.getElementById('input_tanggal');
            const labelTanggal = document.getElementById('label-tanggal');
            const starTanggal = document.getElementById('star-tanggal');

            function updateFormVisibility() {
                const checkedRadio = document.querySelector('.klasifikasi-radio:checked');
                if (!checkedRadio) return;
                
                const selectedValue = checkedRadio.value;

                if (selectedValue === 'Pengaduan') {
                    fieldTerlapor.style.display = 'block';
                    fieldJenisPengaduan.style.display = 'block';
                    fieldTanggalKejadian.style.display = 'block';
                    fieldBuktiPendukung.style.display = 'block'; // Tampilkan bukti pendukung
                    
                    inputTerlapor.setAttribute('required', 'required');
                    selectJenisPengaduan.setAttribute('required', 'required');

                    inputTanggal.setAttribute('required', 'required');
                    labelTanggal.innerHTML = 'Tanggal Kejadian <span class="text-red-500" id="star-tanggal">*</span>';

                    labelJudul.innerHTML = 'Judul Laporan / Ringkasan Masalah <span class="text-red-500">*</span>';
                    sublabelJudul.innerText = 'Tuliskan judul atau inti ringkasan laporan Anda secara singkat.';
                    labelUraian.innerHTML = 'Uraian Kronologi Kejadian <span class="text-red-500">*</span>';
                
                } else if (selectedValue === 'Aspirasi') {
                    fieldTerlapor.style.display = 'none';
                    fieldJenisPengaduan.style.display = 'none';
                    fieldTanggalKejadian.style.display = 'none';
                    fieldBuktiPendukung.style.display = 'none'; // Sembunyikan bukti pendukung

                    inputTerlapor.removeAttribute('required');
                    selectJenisPengaduan.removeAttribute('required');
                    inputTanggal.removeAttribute('required');

                    labelJudul.innerHTML = 'Judul Aspirasi / Gagasan <span class="text-red-500">*</span>';
                    sublabelJudul.innerText = 'Tuliskan judul atau inti gagasan/ide yang ingin Anda sampaikan.';
                    labelUraian.innerHTML = 'Uraian Aspirasi atau Gagasan <span class="text-red-500">*</span>';
                
                } else {
                    fieldTerlapor.style.display = 'none';
                    fieldJenisPengaduan.style.display = 'none';
                    fieldTanggalKejadian.style.display = 'none';
                    fieldBuktiPendukung.style.display = 'none'; 

                    inputTerlapor.removeAttribute('required');
                    selectJenisPengaduan.removeAttribute('required');
                    inputTanggal.removeAttribute('required');

                    labelJudul.innerHTML = 'Judul Informasi / Saran <span class="text-red-500">*</span>';
                    sublabelJudul.innerText = 'Tuliskan judul atau pokok informasi/saran yang ingin disampaikan.';
                    labelUraian.innerHTML = 'Uraian Informasi atau Saran <span class="text-red-500">*</span>';
                }
            }

            radioKlasifikasi.forEach(radio => {
                radio.addEventListener('change', updateFormVisibility);
            });

            updateFormVisibility();
        });

        const input = document.querySelector('input[name="bukti_pelaporan[]"]');
        const previewContainer = document.getElementById('preview-container');
        let selectedFiles = [];

        if (input) {
            input.addEventListener('change', function () {
                const files = Array.from(this.files);
                selectedFiles = selectedFiles.concat(files);

                if (selectedFiles.length > 5) {
                    alert('Maksimal 5 file yang dapat diunggah.');
                    selectedFiles = selectedFiles.slice(0, 5);
                }

                renderPreview();
                updateInputFiles();
            });
        }

        function renderPreview() {
            previewContainer.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = function (e) {
                    let previewContent = '';
                    if (file.type === 'application/pdf') {
                        previewContent = `
                            <div class="w-full h-24 bg-red-50 flex flex-col items-center justify-center border rounded-lg">
                                <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                <span class="text-[10px] text-slate-600 mt-1 px-1 truncate max-w-full">${file.name}</span>
                            </div>
                        `;
                    } else {
                        previewContent = `<img src="${e.target.result}" class="w-full h-24 object-cover">`;
                    }

                    let html = `
                        <div class="relative border rounded-lg overflow-hidden bg-white shadow-xs">
                            ${previewContent}
                            <button type="button"
                                onclick="removeFile(${index})"
                                class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">
                                &times;
                            </button>
                        </div>
                    `;

                    previewContainer.innerHTML += html;
                };

                reader.readAsDataURL(file);
            });
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            renderPreview();
            updateInputFiles();
        }

        function updateInputFiles() {
            if (!input) return;
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            input.files = dataTransfer.files;
        }
    </script>
</body>
</html>
