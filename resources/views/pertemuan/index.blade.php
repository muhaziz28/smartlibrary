@extends('layouts.apps')

@section('title')
Pertemuan {{ $pertemuan->pertemuan_ke}}
@endsection

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Pertemuan {{ $pertemuan->pertemuan_ke }}
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('detail_sesi_mata_kuliah.index', $pertemuan->sesi_mata_kuliah_id) }}" class="btn btn-outline d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
    @include('pertemuan.ebook.index')

    @include('pertemuan.modul.index')

    @include('pertemuan.video_conf.index')

    @include('pertemuan.video_pembelajaran.index')
    <div class="container-xl mt-3">
        <div class="row row-deck row-cards mb-3">
            <!-- TUGAS -->
            <div class="col-3">
                <div class="card">
                    <div class="card-status-top bg-danger"></div>
                    <div class="card-header">
                        <h4 class="card-title">Tugas</h4>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('tugas.index', $pertemuan->id) }}" class="btn btn-outline-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-text-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M19 10h-14" />
                                <path d="M5 6h14" />
                                <path d="M14 14h-9" />
                                <path d="M5 18h6" />
                                <path d="M18 15v6" />
                                <path d="M15 18h6" />
                            </svg>
                            Tugas
                        </a>
                    </div>
                </div>
            </div>
            <!-- EVALUASI PEMBELAJARAN -->
            <div class="col-3">
                <div class="card">
                    <div class="card-status-top bg-danger"></div>
                    <div class="card-header">
                        <h4 class="card-title">Evaluasi Pembelajaran</h4>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('evaluasi.index', $pertemuan->id) }}" class="btn btn-outline-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-text-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M19 10h-14" />
                                <path d="M5 6h14" />
                                <path d="M14 14h-9" />
                                <path d="M5 18h6" />
                                <path d="M18 15v6" />
                                <path d="M15 18h6" />
                            </svg>
                            Evaluasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('pertemuan.ebook.create_ebook')
@include('pertemuan.ebook.edit_ebook')

@include('pertemuan.modul.create_modul')
@include('pertemuan.modul.edit_modul')

@include('pertemuan.video_conf.create')
@include('pertemuan.video_conf.edit')

@include('pertemuan.video_pembelajaran.create')
@include('pertemuan.video_pembelajaran.edit')
@endsection

@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        $('.edit_ebook').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $.ajax({
                url: "{{ route('ebook.show', '') }}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#edit_ebook_modal').modal('show');
                        $('#edit_ebook_modal').find('#id').val(response.data.id);
                        $('#edit_ebook_modal').find('#judul').val(response.data.judul);
                        $('#edit_ebook_modal').find('#file').val(response.data.file);
                        $('#edit_ebook_modal').find('#link').val(response.data.link);
                        $('#edit_ebook_modal').find('#keterangan').val(response.data.keterangan);
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('.delete_ebook').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('ebook.destroy', '') }}/" + id,
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

        $('#add_ebook_modal_form').on('submit', function(e) {
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
                        $('#add_ebook_modal').modal('hide');
                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            });
        });

        $('#edit_ebook_modal_form').on('submit', function(e) {
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

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            });
        });

        $('.edit_modul').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $.ajax({
                url: "{{ route('modul.show', '') }}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#edit_modul_modal').modal('show');
                        $('#edit_modul_modal_form').find('#id').val(response.data.id);
                        $('#edit_modul_modal_form').find('#link').val(response.data.link);
                        $('#edit_modul_modal_form').find('#keterangan').val(response.data.keterangan);

                        // type berupa select option. jadi harus di set value nya ke option yang sesuai

                        $('#edit_modul_modal_form').find('#type').val(response.data.type);


                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('.delete_modul').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('modul.destroy', '') }}/" + id,
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

        $('#add_modul_modal_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = new FormData(form[0]);

            var insert = document.getElementById('insert').checked;
            if (insert == true) {
                var insert = 1;
            } else {
                var insert = 0;
            }

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    form.find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    form.find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        // reset modal
                        form[0].reset();
                        $('#add_modul_modal').modal('hide');
                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            });
        });

        $('#edit_modul_modal_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = new FormData(form[0]);

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    form.find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    form.find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            });
        });

        $('#add_modul_modal').on('hidden.bs.modal', function(e) {
            $('#add_modul_modal_form')[0].reset();
        });

        $('#edit_modul_modal').on('hidden.bs.modal', function(e) {
            $('#edit_modul_modal_form')[0].reset();
        });

        $('#add_video_conf_modal_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = form.serialize();

            var insert = document.getElementById('insert').checked;
            if (insert == true) {
                var insert = 1;
            } else {
                var insert = 0;
            }

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $('#add_video_conf_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#add_video_conf_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        $('#add_video_conf_modal_form')[0].reset();
                        $('#add_video_conf_modal').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('.delete_video_conf').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('video_conf.destroy', '') }}/" + id,
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

        $('.edit_video_conf').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $.ajax({
                url: "{{ route('video_conf.show', '') }}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#edit_video_conf_modal').modal('show');
                        $('#edit_video_conf_modal').find('#id').val(response.data.id);
                        $('#edit_video_conf_modal').find('#link').val(response.data.link);
                        $('#edit_video_conf_modal').find('#keterangan').val(response.data.keterangan);
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('#edit_video_conf_modal_form').on('submit', function(e) {
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
                    $('#edit_video_conf_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#edit_video_conf_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        // reset modal
                        $('#edit_video_conf_modal_form')[0].reset();
                        $('#edit_video_conf_modal').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $(document).ready(function() {
            $('.preview-video').on('click', function() {
                var videoUrl = $(this).data('video');
                $('#videoModal').modal('show');
                $('#previewVideo').attr('src', videoUrl);
            });
        });

        $('#add_video_pembelajaran_modal_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = form.serialize();

            var insert = document.getElementById('insert').checked;
            if (insert == true) {
                var insert = 1;
            } else {
                var insert = 0;
            }

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $('#add_video_pembelajaran_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#add_video_pembelajaran_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        $('#add_video_pembelajaran_modal_form')[0].reset();
                        $('#add_video_pembelajaran_modal').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('.delete_video_pembelajaran').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('video_pembelajaran.destroy', '') }}/" + id,
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

        $('.edit_video_pembelajaran').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $.ajax({
                url: "{{ route('video_pembelajaran.show', '') }}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#edit_video_pembelajaran_modal').modal('show');
                        $('#edit_video_pembelajaran_modal').find('#id').val(response.data.id);
                        $('#edit_video_pembelajaran_modal').find('#link').val(response.data.link);
                        $('#edit_video_pembelajaran_modal').find('#keterangan').val(response.data.keterangan);
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('#edit_video_pembelajaran_modal_form').on('submit', function(e) {
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
                    $('#edit_video_pembelajaran_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#edit_video_pembelajaran_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        // reset modal
                        $('#edit_video_pembelajaran_modal_form')[0].reset();
                        $('#edit_video_pembelajaran_modal').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })
    });
</script>
@endsection