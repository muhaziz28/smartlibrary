<div class="container-xl mb-3">
    <div class="card">
        <div class="card-body">
            <div class="accordion" id="accordion-modul">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-1">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-modul" aria-expanded="true">
                            Modul
                        </button>
                    </h2>
                    <div id="collapse-modul" class="accordion-collapse collapse show" data-bs-parent="#accordion-modul">
                        <div class="accordion-body pt-0">
                            <div class="row row-deck row-cards">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body border-bottom py-3">
                                            <div class="col-auto ms-auto d-print-none">
                                                <div class="btn-list">
                                                    @if (Auth::user()->role_id != 2)
                                                    <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#add_modul_modal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M12 5l0 14" />
                                                            <path d="M5 12l14 0" />
                                                        </svg>
                                                        Tambah Modul
                                                    </a>
                                                    <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#add_modul_modal" aria-label="Tambah Modul">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M12 5l0 14" />
                                                            <path d="M5 12l14 0" />
                                                        </svg>
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table card-table table-vcenter text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th class="w-1">No. </th>
                                                        <th>FILE</th>
                                                        <th>LINK</th>
                                                        <th>TYPE</th>
                                                        <th>KETERANGAN</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (count($modul) == 0)
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">
                                                            Tidak ada data
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @foreach ($modul as $m )
                                                    <tr>
                                                        <td>
                                                            <span class="text-muted">
                                                                {{ $loop->iteration }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if($m->file != null)
                                                            <a href="{{ asset('media/' . $m->file) }}" target="_blank" class="btn btn-outline-default">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M9 15l6 -6" />
                                                                    <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                                    <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                                </svg>
                                                                Lihat File
                                                            </a>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($m->link != null)
                                                            <a href="{{ $m->link }}" target="_blank" class="btn btn-outline-default">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M9 15l6 -6" />
                                                                    <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                                    <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                                </svg>
                                                                Lihat Link
                                                            </a>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($m->type == 'teori')
                                                            <span class="badge bg-blue">Teori</span>
                                                            @elseif ($m->type == 'praktikum')
                                                            <span class="badge bg-green">Praktikum</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $m->keterangan}}
                                                        </td>
                                                        <td class="text-end">
                                                            @if(Auth::user()->role_id != 2)
                                                            <div class="btn-list">
                                                                <a href="#" class="btn btn-outline-info edit_modul" data-id="{{ $m->id }}">
                                                                    <!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                                        <path d="M16 5l3 3" />
                                                                    </svg>
                                                                    Edit
                                                                </a>
                                                                <a href="#" class="btn btn-outline-danger delete_modul" data-id="{{ $m->id }}">
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
                </div>
            </div>
        </div>
    </div>
</div>