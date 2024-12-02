@extends('layouts.apps')

@section('title')
Master Periode Mata Kuliah
@endsection

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Master Periode Mata Kuliah
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="#" class="btn btn-outline-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#add_periode_mata_kuliah_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Tambah Periode Mata Kuliah
                    </a>
                    <a href="#" class="btn btn-outline-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#add_periode_mata_kuliah_modal" aria-label="Tambah Periode Mata Kuliah">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-body border-bottom py-3">
                        <form action="" class="d-flex gap-1">
                            <div class="col-auto">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Keyword..." value="{{ $request->keyword }}">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="input-group">
                                    <select name="periode" id="periode" class="form-control">
                                        <option value="">--Pilih Periode--</option>
                                        @foreach ($periode as $p)
                                        <option value="{{ $p->id }}" {{ $request->periode == $p->id ? 'selected' : '' }}>{{ $p->mulai }} - {{ $p->selesai }} ({{ $p->tahun_ajaran }})</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-outline-primary">
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
                            @if ($request->keyword || $request->periode)
                            <a href="{{ route('periode_mata_kuliah.index') }}" class="btn btn-outline-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M18 6l-12 12" />
                                    <path d="M6 6l12 12" />
                                </svg>
                                Clear
                            </a>

                            @endif
                        </form>
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
                                    <th>MATA KULIAH</th>
                                    <th>PRODI</th>
                                    <th>PERIODE</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($data) == 0)
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Tidak ada data
                                    </td>
                                </tr>
                                @endif
                                @foreach ($data as $m )
                                <tr>
                                    <td>
                                        <span class="text-muted">
                                            {{ $loop->iteration + $data->firstItem() - 1 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-muted"> {{$m->mata_kuliah->kode_mk }} </div>
                                        {{ $m->mata_kuliah->nama_mk }}
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            {{ $m->mata_kuliah->prodi->nama_prodi }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($m->periode->aktif == 1)
                                        <span class="badge bg-green-lt">Aktif</span>
                                        @else
                                        <span class="badge bg-red-lt">Tidak Aktif</span>
                                        @endif

                                        {{ $m->periode->mulai }} - {{ $m->periode->selesai }} ({{ $m->periode->tahun_ajaran }})
                                    </td>

                                    <td class="text-end">
                                        <div class="btn-list">
                                            <a href="{{ route('sesi_mata_kuliah.index', $m->id) }}" class="btn btn-outline">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-book-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M19 4v16h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z" />
                                                    <path d="M19 16h-12a2 2 0 0 0 -2 2" />
                                                    <path d="M9 8h6" />
                                                </svg>
                                                Sesi
                                            </a>
                                            <a href="#" class="btn btn-outline-danger delete_periode_mata_kuliah" data-id="{{ $m->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                                Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        <p class="m-0 text-muted ms-3">
                            Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} entries
                        </p>
                        <ul class="pagination m-0 ms-auto">
                            @if($data->currentPage() > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $data->url(1) }}">First</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="{{ $data->previousPageUrl() }}">Previous</a>
                            </li>
                            @endif

                            @for($i = max(1, $data->currentPage() - 2); $i <= min($data->currentPage() + 2, $data->lastPage()); $i++)
                                @if($i == $data->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $i }}</span>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                                </li>
                                @endif
                                @endfor

                                @if($data->currentPage() < $data->lastPage())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $data->nextPageUrl() }}">Next</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $data->url($data->lastPage()) }}">Last</a>
                                    </li>
                                    @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('periode_mata_kuliah.create')
@include('periode_mata_kuliah.edit')
@endsection

@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        $('#mata-kuliah-select').select2({
            placeholder: "--Pilih Mata Kuliah--",
            allowClear: true,
            dropdownParent: $('#add_periode_mata_kuliah_modal'),
            ajax: {
                url: "{{ route('mata_kuliah.getMataKuliahList') }}",
                type: "GET",
                dataType: 'json',
                data: function(params) {
                    return {
                        search: params.term,
                    }
                },
                processResults: function(data) {
                    return {
                        results: $.map(data.data, function(item) {
                            return {
                                text: item.kode_mk + ' - ' + item.nama_mk,
                                id: item.id
                            }
                        })
                    }

                },
                cache: true
            }
        })

        $('#add_periode_mata_kuliah_modal_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = form.serialize();

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $('#add_periode_mata_kuliah_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#add_periode_mata_kuliah_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        // reset modal
                        $('#add_periode_mata_kuliah_modal_form')[0].reset();
                        $('#add_periode_mata_kuliah_modal').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('.delete_periode_mata_kuliah').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('periode_mata_kuliah.destroy', '') }}/" + id,
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
    });
</script>
@endsection