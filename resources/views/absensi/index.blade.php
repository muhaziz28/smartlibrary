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
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('detail_sesi_mata_kuliah.index', $id) }}" class="btn btn-outline d-none d-sm-inline-block">
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
                    <div class="card-header">Pertemuan Teori</div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>Pertemuan</th>
                                    <th>Tanggal Pertemuan</th>
                                    <th>Jenis Absensi</th>
                                    <th>Absensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($pertemuanTeori) == 0)
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Tidak ada data
                                    </td>
                                </tr>
                                @endif
                                @foreach ($pertemuanTeori as $m )
                                <tr>
                                    <td>
                                        Pertemuan ke {{ $m->pertemuan_ke }}
                                    </td>
                                    <td>
                                        {{ $m->tanggal }} | {{ $sesi->jadwalTeori->start }} - {{ $sesi->jadwalTeori->end }}

                                        <?php
                                        $today = date('Y-m-d');
                                        if ($today === $m->tanggal) {
                                            echo '<span class="badge bg-success">Hari ini</span>';
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <span class="text-uppercase">{{ $m->absensi_type->type ?? '' }}</span>
                                        <button class="btn btn-default type" data-id="{{ $m->id }}">Atur</button>
                                    </td>

                                    <td>

                                        @if ($today === $m->tanggal || $today >= $m->tanggal)
                                        <a href="{{ route('detail-absensi.index', $m->id) }}" class="btn btn-default">Data Absensi</a>
                                        @else
                                        <span class="text-warning">Belum ada absensi</span>
                                        @endif

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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Pertemuan Praktikum</div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>Pertemuan</th>
                                    <th>Tanggal Pertemuan</th>
                                    <th>Jenis Absensi</th>
                                    <th>Absensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($pertemuanPraktikum) == 0)
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Tidak ada data
                                    </td>
                                </tr>
                                @endif
                                @foreach ($pertemuanPraktikum as $m )
                                <tr>
                                    <td>
                                        Pertemuan ke {{ $m->pertemuan_ke }}
                                    </td>
                                    <td>
                                        {{ $m->tanggal }} | {{ $sesi->jadwalPraktikum->start }} - {{ $sesi->jadwalPraktikum->end }}

                                        <?php
                                        $today = date('Y-m-d');
                                        if ($today === $m->tanggal) {
                                            echo '<span class="badge bg-success">Hari ini</span>';
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <span class="text-uppercase">{{ $m->absensi_type->type ?? '' }}</span>
                                        <button class="btn btn-default type" data-id="{{ $m->id }}">Atur</button>
                                    </td>

                                    <td>

                                        @if ($today === $m->tanggal || $today >= $m->tanggal)
                                        <a href="{{ route('detail-absensi.index', $m->id) }}" class="btn btn-default">Data Absensi</a>
                                        @else
                                        <span class="text-warning">Belum ada absensi</span>
                                        @endif

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
@include('absensi.add-type-modal')
@endsection

@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        $('.type').on('click', function(e) {
            e.preventDefault();
            var pertemuanId = $(this).data('id');
            console.log(id)
            $('#add_type_modal').modal('show');
            $('#add_type_modal_form').find('#id').val(pertemuanId);
            // handle form submit
            $('#add_type_modal_form').on('submit', function(e) {
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
                        $('#add_type_modal_form').find('button[type="submit"]').addClass('btn-loading');
                    },
                    success: function(response) {
                        $('#add_type_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                        if (response.success) {
                            // reset modal
                            $('#add_type_modal_form')[0].reset();
                            $('#add_type_modal').modal('hide');

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