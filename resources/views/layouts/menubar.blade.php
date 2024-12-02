 <div class="col-12">
     <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
         <div class="carousel-inner">
             <div class="carousel-item active">
                 <img src="{{ asset('unp.jpeg') }}" class="d-block" style="height: 40vh; width: 100%" alt="...">
             </div>
         </div>
     </div>
 </div>
 <header class="navbar-expand-md">
     <div class="collapse navbar-collapse" id="navbar-menu">
         <div class="navbar">
             <div class="container-xl">
                 <ul class="navbar-nav">
                     <li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
                         <a class="nav-link" href="{{ route('home') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                     <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                     <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                     <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                 </svg>
                             </span>
                             <span class="nav-link-title">
                                 Home
                             </span>
                         </a>
                     </li>
                     @if(Auth::user()->role_id == 3)
                     <li class="nav-item dropdown {{ Request::is('detail_sesi_mata_kuliah/*') ? 'active' : '' }}">
                         <a class="nav-link" href="{{ route('detail_sesi_mata_kuliah.dosen') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                     <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                     <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                     <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                 </svg>
                             </span>
                             <span class="nav-link-title">
                                 Mata Kuliah
                             </span>
                         </a>
                     </li>
                     @endif
                     @if(Auth::user()->role_id == 2)
                     <!--  -->
                     <li class="nav-item dropdown">
                         <a class="nav-link" href="{{ route('mata_kuliah_mahasiswa.index') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                     <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                     <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                     <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                 </svg>
                             </span>
                             <span class="nav-link-title">
                                 Mata Kuliah
                             </span>
                         </a>
                     </li>
                     @endif
                     @if(Auth::user()->role_id == 1)
                     <li class="nav-item {{ Request::is('mahasiswa*') ? 'active' : '' }}">
                         <a class="nav-link" href="{{ route('mahasiswa.index') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg" class=" icon icon-tabler icon-tabler-users-group" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                     <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                     <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                                     <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                     <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                                     <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                     <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                                 </svg>
                             </span>
                             <span class="nav-link-title"> Mahasiswa</span>
                         </a>
                     </li>
                     <li class="nav-item {{ Request::is('dosen*') ? 'active' : '' }}">
                         <a class="nav-link" href="{{ route('dosen.index') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-group" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                     <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                     <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                                     <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                     <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                                     <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                     <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                                 </svg>
                             </span>
                             <span class="nav-link-title"> Dosen</span>
                         </a>
                     </li>
                     <li class="nav-item {{ Request::is('fakultas*') || Request::is('prodi*') ? 'active' : '' }}">
                         <a class="nav-link" href="{{ route('fakultas.index') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                     <path d="M3 21l18 0" />
                                     <path d="M9 8l1 0" />
                                     <path d="M9 12l1 0" />
                                     <path d="M9 16l1 0" />
                                     <path d="M14 8l1 0" />
                                     <path d="M14 12l1 0" />
                                     <path d="M14 16l1 0" />
                                     <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
                                 </svg>
                             </span>
                             <span class="nav-link-title"> Fakultas</span>
                         </a>
                     </li>
                     <li class="nav-item {{ Request::is('role*') ? 'active' : '' }}">
                         <a class="nav-link" href="{{ route('role.index') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-server-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                     <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                     <path d="M3 12m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                     <path d="M7 8l0 .01" />
                                     <path d="M7 16l0 .01" />
                                     <path d="M11 8h6" />
                                     <path d="M11 16h6" />
                                 </svg>
                             </span>
                             <span class="nav-link-title"> Role</span>
                         </a>
                     </li>
                     <li class="nav-item {{ Request::is('periode') ? 'active' : '' }}">
                         <a class="nav-link" href="{{ route('periode.index') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-due" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                     <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                     <path d="M16 3v4" />
                                     <path d="M8 3v4" />
                                     <path d="M4 11h16" />
                                     <path d="M12 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                 </svg>
                             </span>
                             <span class="nav-link-title"> Periode</span>
                         </a>
                     </li>
                     <li class="nav-item {{ Request::is('mata_kuliah*') ? 'active' : '' }}">
                         <a class="nav-link" href="{{ route('mata_kuliah.index') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-databricks" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                     <path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" />
                                 </svg>
                             </span>
                             <span class="nav-link-title"> Mata Kuliah</span>
                         </a>
                     </li>
                     <li class="nav-item {{ Request::is('periode_mata_kuliah*') ? 'active' : '' }}">
                         <a class="nav-link" href="{{ route('periode_mata_kuliah.index') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-databricks" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                     <path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" />
                                 </svg>
                             </span>
                             <span class="nav-link-title">Periode Mata Kuliah</span>
                         </a>
                     </li>
                     <li class="nav-item {{ Request::is('jam-perkuliahan*') ? 'active' : '' }}">
                         <a class="nav-link" href="{{ route('jam-perkuliahan.index') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-databricks" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                     <path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" />
                                 </svg>
                             </span>
                             <span class="nav-link-title">Jam Perkuliahan</span>
                         </a>
                     </li>
                     @endif
                     <li class="nav-item {{ Request::is('instrument*') ? 'active' : '' }}">
                         <a class="nav-link" href="{{ route('instrument.index') }}">
                             <span class="nav-link-icon d-md-none d-lg-inline-block">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-databricks" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                     <path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" />
                                 </svg>
                             </span>
                             <span class="nav-link-title">Instrument</span>
                         </a>
                     </li>
                 </ul>

             </div>
         </div>
     </div>
 </header>