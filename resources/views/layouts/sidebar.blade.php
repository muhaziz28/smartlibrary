<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li>
                <a class="ai-icon" href="{{ route('home') }}" aria-expanded="false">
                    <i class="flaticon-381-networking"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            @if (Auth::user()->role_id == 1)
            <li>
                <a class="ai-icon" href="{{ route('role.index') }}" aria-expanded="false">
                    <i class="flaticon-381-television"></i>
                    <span class="nav-text">Master Role</span>
                </a>
            </li>
            <li>
                <a class="ai-icon" href="{{ route('periode.index') }}" aria-expanded="false">
                    <i class="flaticon-381-television"></i>
                    <span class="nav-text">Master Periode</span>
                </a>
            </li>
            <li>
                <a class="ai-icon" href="{{ route('mata_kuliah.index') }}" aria-expanded="false">
                    <i class="flaticon-381-television"></i>
                    <span class="nav-text">Master Mata Kuliah</span>
                </a>
            </li>
            <li>
                <a class="ai-icon" href="{{ route('periode_mata_kuliah.index') }}" aria-expanded="false">
                    <i class="flaticon-381-television"></i>
                    <span class="nav-text">Master Periode Mata Kuliah</span>
                </a>
            </li>
            <li>
                <a class="ai-icon" href="{{ route('fakultas.index') }}" aria-expanded="false">
                    <i class="flaticon-381-television"></i>
                    <span class="nav-text">Master Fakultas & Prodi</span>
                </a>
            </li>
            <li>
                <a class="ai-icon" href="{{ route('dosen.index') }}" aria-expanded="false">
                    <i class="flaticon-381-television"></i>
                    <span class="nav-text">Master Dosen</span>
                </a>
            </li>
            <li>
                <a class="ai-icon" href="{{ route('mahasiswa.index') }}" aria-expanded="false">
                    <i class="flaticon-381-television"></i>
                    <span class="nav-text">Master Mahasiswa</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->role_id == 3)
            <li>
                <a class="ai-icon" href="{{ route('detail_sesi_mata_kuliah.dosen') }}" aria-expanded="false">
                    <i class="flaticon-381-television"></i>
                    <span class="nav-text">Mata Kuliah Tersedia</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->role_id == 2)
            <li>
                <a class="ai-icon" href="{{ route('mata_kuliah_mahasiswa.index') }}" aria-expanded="false">
                    <i class="flaticon-381-television"></i>
                    <span class="nav-text">Mata Kuliah</span>
                </a>
            </li>
            @endif
        </ul>
        <div class="add-menu-sidebar">
            <img src="{{ asset('assets/images/calendar.png') }}" alt="" class=" mr-3">
            <p class="	font-w500 mb-0">Create Workout Plan Now</p>
        </div>
        <div class="copyright">
            <p><strong>Gymove Fitness Admin Dashboard</strong> © 2020 All Rights Reserved</p>
            <p>Made with <span class="heart"></span> by DexignZone</p>
        </div>
    </div>
</div>