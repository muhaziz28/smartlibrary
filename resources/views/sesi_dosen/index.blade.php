@extends('layouts.apps')

@section('title')
Sesi Mata Kuliah
@endsection

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Mata Kuliah
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col">
                <div class="card">
                    <form action="{{ route('detail_sesi_mata_kuliah.dosen') }}" method="GET">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label">Periode</label>
                                        <select type="text" class="form-select" id="periode" name="periode">
                                            <option value="">-- Pilih Periode --</option>
                                            @foreach ($periode as $p)
                                            <option value="{{ $p->id }}" {{ ($request->periode == $p->id) ? 'selected' : '' }}>{{ $p->mulai }} - {{ $p->selesai }} ({{ $p->tahun_ajaran }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label">Periode</label>
                                        <select type="text" class="form-select" id="select-labels" value="">
                                            <option value="copy" data-custom-properties="&lt;span class=&quot;badge bg-primary-lt&quot;&gt;cmd + C&lt;/span&gt;">Copy</option>
                                            <option value="paste" data-custom-properties="&lt;span class=&quot;badge bg-primary-lt&quot;&gt;cmd + V&lt;/span&gt;">Paste</option>
                                            <option value="cut" data-custom-properties="&lt;span class=&quot;badge bg-primary-lt&quot;&gt;cmd + X&lt;/span&gt;">Cut</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label">Periode</label>
                                        <select type="text" class="form-select" id="select-labels" value="">
                                            <option value="copy" data-custom-properties="&lt;span class=&quot;badge bg-primary-lt&quot;&gt;cmd + C&lt;/span&gt;">Copy</option>
                                            <option value="paste" data-custom-properties="&lt;span class=&quot;badge bg-primary-lt&quot;&gt;cmd + V&lt;/span&gt;">Paste</option>
                                            <option value="cut" data-custom-properties="&lt;span class=&quot;badge bg-primary-lt&quot;&gt;cmd + X&lt;/span&gt;">Cut</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
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
                                Filter</button>
                        </div>

                    </form>
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
                                    <th>KODE SESI</th>
                                    <th>MATA KULIAH</th>
                                    <th>SKS</th>
                                    <th>PERIODE</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($sesi) == 0)
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Tidak ada data
                                    </td>
                                </tr>
                                @endif
                                @foreach ($sesi as $s)
                                <tr>
                                    <td>
                                        <span class="text-muted">
                                            {{ $loop->iteration + ($currentPage - 1) * $perPage }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $s->kode_sesi }}
                                    </td>
                                    <td>
                                        {{ $s->periode_mata_kuliah->mata_kuliah->nama_mk }}
                                    </td>
                                    <td>
                                        {{ $s->periode_mata_kuliah->mata_kuliah->sks }}
                                    </td>
                                    <td>
                                        {{ $s->periode_mata_kuliah->periode->mulai }} - {{ $s->periode_mata_kuliah->periode->selesai }} ({{ $s->periode_mata_kuliah->periode->tahun_ajaran }})
                                    </td>
                                    <td>
                                        <a href="{{ route('detail_sesi_mata_kuliah.index', $s->id) }}" class="btn"><!-- Download SVG icon from http://tabler-icons.io/i/phone -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list-details" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M13 5h8" />
                                                <path d="M13 9h5" />
                                                <path d="M13 15h8" />
                                                <path d="M13 19h5" />
                                                <path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                <path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
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