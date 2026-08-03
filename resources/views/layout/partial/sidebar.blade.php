<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">

            {{-- DASHBOARD: ADMIN DAN DOKTER BOLEH AKSES --}}
            <li>
                <a href="{{ Route('dashboard') }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-networking"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            {{-- MENU ADMIN --}}
            @if(auth()->user()->role == 1)

                <li>
                    <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-television"></i>
                        <span class="nav-text">Data Pasien</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ Route('pasien') }}">Daftar Pasien</a></li>
                        <li><a href="{{ Route('pasien.add') }}">Tambah Pasien</a></li>
                    </ul>
                </li>

                {{-- REKAM MEDIS ADMIN --}}
                <li>
                    <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-notepad"></i>
                        <span class="nav-text">Rekam Medis</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ Route('rekam') }}">Data Rekam Medis</a></li>
                        <li><a href="{{ Route('rekam.add') }}">Tambah Rekam Medis</a></li>
                    </ul>
                </li>

                <li>
                    <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-diamond"></i>
                        <span class="nav-text">Pembayaran</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ Route('pembayaran') }}">Data Pembayaran</a></li>
                        <li><a href="{{ Route('pembayaran.laporan') }}">Laporan Pembayaran</a></li>
                    </ul>
                </li>

                <li>
                    <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-battery"></i>
                        <span class="nav-text">Obat & Resep</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ Route('obat') }}">Data Obat</a></li>
                        <li><a href="{{ Route('obat.resep') }}">Resep & Pemberian Obat</a></li>
                        <li><a href="{{ Route('obat.riwayat') }}">Riwayat Keluar Obat</a></li>
                    </ul>
                </li>

                <li>
                    <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-notepad"></i>
                        <span class="nav-text">Master Data</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ Route('tindakan') }}">Data Tindakan</a></li>

                        {{-- Data Poli disembunyikan karena sistem fokus pada rekam medis klinik gigi --}}
                        {{-- <li><a href="{{ Route('poli') }}">Data Poli</a></li> --}}

                        <li><a href="{{ Route('dokter') }}">Data Dokter</a></li>
                        <li><a href="{{ Route('icd') }}">Data ICD</a></li>
                        <li><a href="{{ Route('petugas') }}">Data Pengguna</a></li>
                    </ul>
                </li>

            @endif


           {{-- MENU DOKTER --}}
            @if(auth()->user()->role == 3)

                <li>
                    <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-notepad"></i>
                        <span class="nav-text">Rekam Medis</span>
                    </a>
                    <ul aria-expanded="false">
                        <li>
                            <a href="{{ Route('rekam', ['tab' => 2]) }}">
                                Perlu Diperiksa
                            </a>
                        </li>
                        <li>
                            <a href="{{ Route('rekam', ['tab' => 5]) }}">
                                Selesai Diperiksa
                            </a>
                        </li>
                    </ul>
                </li>

            @endif

        </ul>

        <div class="copyright">
            <p><strong>OQ Clinic Dentist</strong> © 2026 All Rights Reserved</p>
        </div>
    </div>
</div>