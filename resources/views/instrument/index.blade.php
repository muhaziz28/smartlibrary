@extends('layouts.apps')

@section('title')
Dashboard
@endsection

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="row g-2 align-items-center">

            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">

        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Instrument
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#add-instrument-modal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Tambah Instrument
                        </a>
                    </div>
                </div>
            </div>
            <div class="row row-deck row-cards mt-2">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable" id="instrument">
                                <thead>
                                    <tr>
                                        <th class=" w-1">No</th>
                                        <th class="text-uppercase">Instrument</>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($instruments) == 0)
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            Tidak ada data
                                        </td>
                                    </tr>
                                    @endif
                                    @foreach ($instruments as $item)
                                    <tr>
                                        <td class="text-muted">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="text-capitalize">{{ $item->item }}</td>
                                        <td class="text-end">
                                            <a href="" class="btn btn-primary edit-instrument" data-id="{{ $item->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                                Edit
                                            </a>
                                            <a href="#" class="btn btn-danger delete-instrument" data-id="{{ $item->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <line x1="4" y1="7" x2="20" y2="7" />
                                                    <line x1="10" y1="11" x2="10" y2="17" />
                                                    <line x1="14" y1="11" x2="14" y2="17" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1
                                                    -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('instrument.instrument-modal')
@include('instrument.instrument-modal-edit')

@include('create')
@endsection

@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        $('#add-instrument-modal-form').on('submit', function(e) {
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
                    $('#add-instrument-modal-form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    console.log(response)
                    $('#add-instrument-modal-form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        $('#add-instrument-modal-form')[0].reset();
                        $('#add-instrument-modal').modal('hide');

                        window.location.reload();
                    }
                },
                error: function(response) {
                    console.log(response)
                    $('#add-instrument-modal-form').find('button[type="submit"]').removeClass('btn-loading');
                    $('#modal-danger').modal('show');
                    $('#modal-danger').find('#message').html(response.responseJSON.message);
                }
            })
        })

        $('.edit-instrument').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $.ajax({
                url: "{{ route('instrument.show', '') }}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#edit-instrument-modal').modal('show');
                        $('#edit-instrument-modal').find('#id').val(response.data.id);
                        $('#edit-instrument-modal').find('#instrument').val(response.data.item);
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('#edit-instrument-modal-form').on('submit', function(e) {
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
                    $('#edit-instrument-modal-form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#edit-instrument-modal-form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        $('#edit-instrument-modal-form')[0].reset();
                        $('#edit-instrument-modal').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                    console.log(response)
                }
            })
        })

        $('.delete-instrument').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('instrument.destroy', '') }}/" + id,
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
    })
</script>
@endsection