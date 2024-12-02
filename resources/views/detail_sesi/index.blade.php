@extends('layouts.apps')

@section('title')
Detail Sesi Mata Kuliah
@endsection

@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Detail Sesi Mata Kuliah
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    @if(Auth::user()->role_id == 1)
                    <a href="{{ route('sesi_mata_kuliah.index', $sesiMataKuliah->periode_mata_kuliah_id) }}" class="btn btn-outline d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 7l-5 5l5 5" />
                            <path d="M17 7l-5 5l5 5" />
                        </svg>
                        Kembali
                    </a>
                    <a href="#" class="btn btn-secondary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#setting_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 7l-5 5l5 5" />
                            <path d="M17 7l-5 5l5 5" />
                        </svg>
                        Pengaturan
                    </a>
                    @elseif(Auth::user()->role_id == 2)
                    <a href="{{ route('mata_kuliah_mahasiswa.index') }}" class="btn btn-outline d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 7l-5 5l5 5" />
                            <path d="M17 7l-5 5l5 5" />
                        </svg>
                        Kembali
                    </a>
                    @else
                    <a href="{{ route('detail_sesi_mata_kuliah.dosen') }}" class="btn btn-outline d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 7l-5 5l5 5" />
                            <path d="M17 7l-5 5l5 5" />
                        </svg>
                        Kembali
                    </a>
                    <a href="#" class="btn btn-secondary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#setting_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 7l-5 5l5 5" />
                            <path d="M17 7l-5 5l5 5" />
                        </svg>
                        Pengaturan
                    </a>
                    @foreach ($periode as $p)

                    @if( $sesiMataKuliah->periode_mata_kuliah->periode->aktif == 1 && $p->aktif == 0)

                    <a class="btn btn-outline-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#sync_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                        </svg>
                        Sync Sesi
                    </a>
                    <a class="btn btn-outline-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#backup_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                        </svg>
                        Backup
                    </a>
                    @endif
                    @endforeach


                    <!-- <a class="btn btn-outline-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#backup_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                        </svg>
                        Backup
                    </a> -->
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    @if(Auth::user()->role_id == 2)
    @if($checkAngket == null)
    <div class="container-xl mb-3">
        <div class="row row-deck row-cards">
            <div class="col-xl-12 col-xxl-12">
                <div class="card">
                    <div class="card-status-top bg-info"></div>
                    <div class="card-header">
                        <h4 class="card-title">Angket</h4>
                    </div>
                    <div class="card-body">
                        <h3 class="text-muted">
                            Silahkan lengkapi angket berikut
                        </h3>
                        <p class="text-muted">
                            Angket ini digunakan untuk menilai mata kuliah yang anda ikuti,
                            silahkan isi angket dengan jujur dan benar.
                        </p>
                        <a href="{{ route('angket.index', $id) }}" class="btn btn-primary">
                            Lengkapi Angket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif
    <div class="container-xl">
        @if(Auth::user()->role_id == 3 )
        <!-- <div>
            <canvas id="instrument"></canvas>
        </div> -->
        <div class="row row-deck row-cards mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-success text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $peserta }} Mahasiswa
                                </div>
                                <div class="text-muted">
                                    Peserta Mata Kuliah
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <a href="{{ route('peserta', $id) }}" class="btn w-100"><!-- Download SVG icon from http://tabler-icons.io/i/phone -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                            </svg>
                            Detail
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning  text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4c.96 0 1.84 .338 2.53 .901" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        <path d="M16 19h6" />
                                        <path d="M19 16v6" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $konfirmasiPeserta }} Mahasiswa
                                </div>
                                <div class="text-muted">
                                    Konfirmasi Peserta Mata Kuliah
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <a href="{{ route('konfirmasi', $id) }}" class="btn w-100"><!-- Download SVG icon from http://tabler-icons.io/i/phone -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4c.96 0 1.84 .338 2.53 .901" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M16 19h6" />
                                <path d="M19 16v6" />
                            </svg>
                            Detail
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning  text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4c.96 0 1.84 .338 2.53 .901" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        <path d="M16 19h6" />
                                        <path d="M19 16v6" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    Angket
                                </div>
                                <div class="">
                                    Yang belum {{ count($mahasiswaIsNotFilledQuest) }} <br>
                                    Yang sudah {{ count($mahasiswaIsFilledQuest) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <a href="{{ route('konfirmasi', $id) }}" class="btn w-100">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="row row-deck row-cards mb-3">
            <!-- MODUL PENGANTAR -->
            @if(Auth::user()->role_id != 2)
            <div class="col-3">
                <div class="card">
                    <div class="card-status-top bg-danger"></div>
                    <div class="card-header">
                        <h4 class="card-title">Absensi</h4>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('absensi.index', $id) }}" class="btn btn-outline-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-text-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M19 10h-14" />
                                <path d="M5 6h14" />
                                <path d="M14 14h-9" />
                                <path d="M5 18h6" />
                                <path d="M18 15v6" />
                                <path d="M15 18h6" />
                            </svg>
                            Absensi
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @include('detail_sesi.pengantar')
    @include('detail_sesi.rps')
    @include('detail_sesi.modul-pengantar.index')

    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-xl-12 col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Pertemuan Teori</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1">No. <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M6 15l6 -6l6 6" />
                                        </svg>
                                    </th>
                                    <th>PERTEMUAN KE</th>
                                    <th>TANGGAL PERTEMUAN</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pertemuanTeori as $m )
                                <tr>
                                    <td>
                                        <span class="text-muted">
                                            {{ $loop->iteration  }}
                                        </span>
                                    </td>
                                    <td>
                                        Pertemuan Ke {{ $m->pertemuan_ke }}
                                    </td>
                                    <td>
                                        {{ $m->tanggal}} | {{ $sesiMataKuliah->jadwalTeori->start }} - {{ $sesiMataKuliah->jadwalTeori->end }}

                                        <?php
                                        $today = date('Y-m-d');
                                        if ($today === $m->tanggal) {
                                            echo '<span class="badge bg-success">Hari ini</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-list">
                                            <a href="{{ route('pertemuan.index', $m->id) }}" class="btn btn-outline">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list-details" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M13 5h8" />
                                                    <path d="M13 9h5" />
                                                    <path d="M13 15h8" />
                                                    <path d="M13 19h5" />
                                                    <path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                    <path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                </svg>
                                                Detail Pertemuan
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xl mt-3">
        <div class="row row-deck row-cards">
            <div class="col-xl-12 col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Pertemuan Praktikum</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1">No.
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M6 15l6 -6l6 6" />
                                        </svg>
                                    </th>
                                    <th>PERTEMUAN KE</th>
                                    <th>TANGGAL PERTEMUAN</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pertemuanPraktikum as $m )
                                <tr>
                                    <td>
                                        <span class="text-muted">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>
                                    <td>
                                        Pertemuan Ke {{ $m->pertemuan_ke }}
                                    </td>
                                    <td>
                                        {{ $m->tanggal}} | {{ \Carbon\Carbon::parse($sesiMataKuliah->jadwalPraktikum->start)->format('H:s') }} - {{ \Carbon\Carbon::parse($sesiMataKuliah->jadwalPraktikum->end)->format('H:s') }}

                                        <?php
                                        $today = date('Y-m-d');
                                        if ($today === $m->tanggal) {
                                            echo '<span class="badge bg-success">Hari ini</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-list">
                                            <a href="{{ route('pertemuan.index', $m->id) }}" class="btn btn-outline">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list-details" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M13 5h8" />
                                                    <path d="M13 9h5" />
                                                    <path d="M13 15h8" />
                                                    <path d="M13 19h5" />
                                                    <path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                    <path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                </svg>
                                                Detail Pertemuan
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@include('detail_sesi.backup_modal')
@include('detail_sesi.sync_modal')
@include('detail_sesi.rps.create_rps')
@include('detail_sesi.rps.edit_rps')
@include('detail_sesi.modul-pengantar.create_modul_pengantar')
@include('detail_sesi.modul-pengantar.edit_modul_pengantar')
@include('detail_sesi.setting-modal')
@endsection

@section('script')

<script>
    $(document).ready(function() {
        $('#edit_sesi_mata_kuliah_form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('pengantar.update') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#edit_sesi_mata_kuliah_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#edit_sesi_mata_kuliah_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        $('#modal-success').modal('show');
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    $('#modal-danger').modal('show');
                    $('#modal-danger').find('#message').html(response.message);
                }
            });
        });
    });

    $(document).ready(function() {
        $('#add_sesi_mata_kuliah_form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('pengantar.store') }}",
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#add_sesi_mata_kuliah_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#add_sesi_mata_kuliah_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    var err = JSON.parse(xhr.responseText);
                    var err_msg = '';

                    $.each(err.errors, function(key, val) {
                        err_msg += val + '<br/>';
                    });

                    $('#modal-danger').modal('show');
                    $('#modal-danger').find('#message').html(err_msg);
                }
            });
        });
    });

    $(document).ready(function() {
        $('#edit_sesi_mata_kuliah_form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('pengantar.update') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#edit_sesi_mata_kuliah_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#edit_sesi_mata_kuliah_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        $('#modal-success').modal('show');
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    $('#modal-danger').modal('show');
                    $('#modal-danger').find('#message').html(response.message);
                }
            });
        });
    });

    $(function() {
        $('.edit_rps').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $.ajax({
                url: "{{ route('rps.show', '') }}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#edit_rps_modal').modal('show');
                        $('#edit_rps_modal').find('#id').val(response.data.id);
                        $('#edit_rps_modal').find('#deskripsi').val(response.data.deskripsi);
                        $('#edit_rps_modal').find('#file').val(response.data.file);
                        $('#edit_rps_modal').find('#link').val(response.data.link);

                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('.delete_rps').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('rps.destroy', '') }}/" + id,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#delete').modal('hide');
                            window.location.reload();
                        } else {
                            $('#modal-danger').modal('show');
                            $('#modal-danger').find('#message').html(response.message);
                        }
                    }
                })
            })
        })

        $('#add_rps_modal_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = new FormData(form[0]); // Use FormData for file uploads

            var insert = document.getElementById('insert').checked;
            if (insert == true) {
                var insert = 1;
            } else {
                var insert = 0;
            }

            data.append('insert', insert);
            console.log(insert);

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                contentType: false, // Ensure these settings for file uploads
                processData: false,
                beforeSend: function() {
                    form.find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    form.find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        // reset modal
                        form[0].reset();
                        $('#add_rps_modal').modal('hide');
                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            });
        });

        $('#edit_rps_modal_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = new FormData(form[0]); // Use FormData for file uploads

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                contentType: false, // Ensure these settings for file uploads
                processData: false,
                beforeSend: function() {
                    form.find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    form.find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        // reset modal
                        form[0].reset();
                        $('#edit_rps_modal').modal('hide');
                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            });
        });

        $('#add_modul_pengantar_modal_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = new FormData(this);

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('#edit_modul_pengantar_modal_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = new FormData(this);

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        // ketika select pada backup modal diubah
        $('#periode').on('change', function() {
            var periode = $(this).val();
            $.ajax({
                url: "{{ route('backup.matakuliah') }}",
                type: "GET",
                data: {
                    periode: periode
                },
                success: function(response) {
                    var sesi = response.sesi;
                    var html = '';
                    if (sesi.length > 0) {
                        html += '<option value="">-- Pilih Sesi Mata Kuliah --</option>';
                        sesi.forEach(function(item) {
                            html += '<option value="' + item.id + '">' + item.kode_sesi + '</option>';
                        });
                        $('#sesi').prop('disabled', false);
                    } else {
                        html += '<option value="">-- Tidak ada sesi mata kuliah --</option>';
                        $('#sesi').prop('disabled', true);
                    }
                    $('#sesi').html(html);
                }
            })
        })

        $('#chatID').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = new FormData(this);

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        window.location.reload()
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('#radius').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = new FormData(this);

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        window.location.reload()
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })
    })

    const ctx = document.getElementById('instrument');
    var label = <?php echo json_encode($instrumentLabel); ?>;
    var dataSet = <?php echo json_encode($dataSet2); ?>;
    // map dataSet to get result in array
    var data = dataSet.map(function(item) {
        return item.result;
    });
    console.log(label)
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: label,
            datasets: [{
                label: '# of Votes',
                data: data,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
                x: {
                    ticks: {
                        callback: function(value) {
                            let label = this.getLabelForValue(value);
                            if (label.length > 10) { // Anda bisa menyesuaikan panjang maksimal label di sini
                                return label.substring(0, 10) + '...'; // Memotong label dan menambahkan ellipsis
                            } else {
                                return label;
                            }
                        }
                    }
                },
            }
        }
    });
</script>
@endsection