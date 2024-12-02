@extends('layouts.apps')

@section('title')
Absensi
@endsection

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Absensi
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb d-inline-flex mb-3 px-2 py-1 fw-semibold bg-info-subtle border border-success-subtle rounded-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="#">Dosen</a></li>
                    </ol>
                </nav>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('absensi.index', $pertemuan->sesiMataKuliah->id) }}" class="btn btn-outline d-none d-sm-inline-block">
                        <svg xmlns=" http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 7l-5 5l5 5" />
                            <path d="M17 7l-5 5l5 5" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <form action="{{ route('absensi.addAbsensi') }}" method="post">
                        @csrf
                        <input type="hidden" name="pertemuan_id" value="{{ $pertemuan->id }}">
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($pertemuan->sesiMataKuliah->mata_kuliah_diambil) == 0)
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            Tidak ada data
                                        </td>
                                    </tr>
                                    @endif
                                    @foreach ($pertemuan->sesiMataKuliah->mata_kuliah_diambil as $m )

                                    <tr>
                                        <td>
                                            {{ $m->mahasiswa->nim }} - {{ $m->mahasiswa->nama_mahasiswa }}
                                            <input type="hidden" name="nim[]" value="{{ $m->mahasiswa->nim }}">
                                        </td>

                                        <td>

                                            @if ($m->absen)
                                            @if($m->absen->is_approved != null)
                                            <span class="badge bg-success p-2">
                                                approved
                                            </span>
                                            @else
                                            <span class="badge bg-danger p-2">
                                            </span>
                                            @endif
                                            @endif

                                        </td>

                                        <td class="text-end">
                                            <div class="btn-list">
                                                <select class="form-select" name="type[]" id="type" required>
                                                    <option value="">Pilih Kehadiran</option>
                                                    <option value="hadir" <?php if ($m->absen && $m->absen->status == 'hadir') echo 'selected'; ?>>Hadir</option>
                                                    <option value="izin" <?php if ($m->absen && $m->absen->status == 'izin') echo 'selected'; ?>>Izin</option>
                                                    <option value="sakit" <?php if ($m->absen && $m->absen->status == 'sakit') echo 'selected'; ?>>Sakit</option>
                                                    <option value="alpa" <?php if ($m->absen && $m->absen->status == 'alpa') echo 'selected'; ?>>Alpa</option>
                                                </select>

                                            </div>
                                        </td>
                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            @foreach ($pertemuan->sesiMataKuliah->mata_kuliah_diambil as $m )
                            @if ($m->absen)
                            <button type="submit" class="btn btn-warning">
                                Update Absensi
                            </button>
                            @else
                            <button type="submit" class="btn btn-primary">
                                Tambah Absensi
                            </button>
                            @endif
                            @break
                            @endforeach

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection