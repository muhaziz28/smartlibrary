<div class="modal modal-blur fade" id="add_sesi_mata_kuliah_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Sesi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="add_sesi_mata_kuliah_modal_form" action="{{ route('sesi_mata_kuliah.store') }}" method="POST">
                @csrf
                <input type="hidden" name="periode_mata_kuliah_id" value="{{ $id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Sesi</label>
                        <input class="form-control @error('kode_sesi') is-invalid @enderror" type="text" name="kode_sesi" id="kode_sesi" placeholder="Kode Sesi">
                        @error('kode_sesi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dosen</label>
                        <select class="form-control form-select @error('kode_dosen') is-invalid @enderror" name="kode_dosen" id="dosen-select">
                        </select>
                        @error('kode_dosen')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jadwal Teori</label>
                        <div class="row">
                            <div class="col-lg-6">
                                <input class="form-control" type="text" name="jadwal_teori" id="jadwal_teori" placeholder="Tanggal pertemuan 1 teori">
                            </div>
                            <div class="col-lg-6">
                                <select name="jam_teori" id="jam_teori" class="form-control">
                                    <option value="" selected>Pilih Jam Perkuliahan</option>
                                    @foreach ($jam as $m)
                                    <option value="{{ $m->id}}">{{ $m->start }} - {{ $m->end }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jadwal Praktikum</label>
                        <div class="row">
                            <div class="col-lg-6">
                                <input class="form-control" type="text" name="jadwal_praktikum" id="jadwal_praktikum" placeholder="Tanggal pertemuan 1 praktikum">
                            </div>
                            <div class="col-lg-6">
                                <select name="jam_praktikum" id="jam_praktikum" class="form-control">
                                    <option value="" selected>Pilih Jam Perkuliahan</option>
                                    @foreach ($jam as $m)
                                    <option value="{{ $m->id}}">{{ $m->start }} - {{ $m->end }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M14 4l0 4l-6 0l0 -4" />
                        </svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>