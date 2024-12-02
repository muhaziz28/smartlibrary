@extends('layouts.apps')

@section('title')
Sesi Mata Kuliah
@endsection

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Mata Kuliah
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb d-inline-flex mb-3 px-2 py-1 fw-semibold bg-info-subtle border border-success-subtle rounded-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Mata Kuliah</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <form action="{{ route('mata_kuliah_mahasiswa.index') }}" method="GET">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">Periode</label>
                                            <div class="d-flex">
                                                <select type="text" class="form-select" id="periode" name="periode">
                                                    <option value="">-- Pilih Periode --</option>
                                                    @foreach ($periode as $p)
                                                    <option value="{{ $p->id }}" {{ ($request->periode == $p->id) ? 'selected' : '' }}>{{ $p->mulai }} - {{ $p->selesai }} ({{ $p->tahun_ajaran }})</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter-cog" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M12 20l-3 1v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v1.5" />
                                                        <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                        <path d="M19.001 15.5v1.5" />
                                                        <path d="M19.001 21v1.5" />
                                                        <path d="M22.032 17.25l-1.299 .75" />
                                                        <path d="M17.27 20l-1.3 .75" />
                                                        <path d="M15.97 17.25l1.3 .75" />
                                                        <path d="M20.733 20l1.3 .75" />
                                                    </svg>
                                                    Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </form>

                        </div>
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
                                    <th>KODE MATA KULIAH</th>
                                    <th>NAMA MATA KULIAH</th>
                                    <th>SKS</th>
                                    <th>DOSEN</th>
                                    <th>PERIODE</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($totalItems == 0)
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        Tidak ada data
                                    </td>
                                </tr>
                                @else
                                @foreach ($mataKuliahDiambil as $m)
                                <tr>
                                    <td>
                                        <span class="text-muted">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $m->sesiMataKuliah->kode_sesi }}
                                    </td>
                                    <td>
                                        {{ $m->sesiMataKuliah->periode_mata_kuliah->mata_kuliah->nama_mk }}
                                    </td>
                                    <td>
                                        {{ $m->sesiMataKuliah->periode_mata_kuliah->mata_kuliah->sks }}
                                    </td>
                                    <td>
                                        {{ $m->sesiMataKuliah->dosen->kode_dosen }} - {{ $m->sesiMataKuliah->dosen->nama_dosen }}
                                    </td>
                                    <td>
                                        {{ $m->sesiMataKuliah->periode_mata_kuliah->periode->mulai }} - {{ $m->sesiMataKuliah->periode_mata_kuliah->periode->selesai }} ({{ $m->sesiMataKuliah->periode_mata_kuliah->periode->tahun_ajaran }})
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-list">
                                            <a href="{{ route('detail_sesi_mata_kuliah.index', $m->sesiMataKuliah->id) }}" class="btn btn-outline btn-icon" tooltip="Detail" tooltip-placement="top">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>

                    </div>
                    <div class="card-footer d-flex align-items-center">
                        <p class="m-0 text-muted ms-3">
                            Showing <span>{{ $currentPage }}</span> to <span>{{ $currentPage * $perPage }}</span> of <span>{{ $totalItems }}</span> entries
                        </p>
                        <ul class="pagination m-0 ms-auto">
                            @if($currentPage > 1)
                            <li class="page-item">
                                <a class="page-link" href="?page=1">First</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?page={{ $currentPage - 1 }}">Previous</a>
                            </li>
                            @endif

                            @for($i = max(1, $currentPage - 2); $i <= min($currentPage + 2, $totalPages); $i++) @if($i==$currentPage) <li class="page-item active">
                                <span class="page-link">{{ $i }}</span>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                                </li>
                                @endif
                                @endfor

                                @if($currentPage < $totalPages) <li class="page-item">
                                    <a class="page-link" href="?page={{ $currentPage + 1 }}">Next</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $totalPages }}">Last</a>
                                    </li>
                                    @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endsection