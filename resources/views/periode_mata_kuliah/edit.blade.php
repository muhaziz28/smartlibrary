<div class="modal fade" id="edit_mata_kuliah_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Role</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <form id="edit_mata_kuliah_modal_form" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="id">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode_mk">Kode Mata Kuliah</label>
                        <input class="form-control @error('kode_mk') is-invalid @enderror" type="text" name="kode_mk" id="kode_mk" placeholder="Kode Mata Kuliah">
                        @error('kode_mk')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nama_mk">Mata Kuliah</label>
                        <input class="form-control @error('nama_mk') is-invalid @enderror" type="text" name="nama_mk" id="nama_mk" placeholder="Mata Kuliah">
                        @error('nama_mk')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="sks">SKS</label>
                        <input class="form-control @error('sks') is-invalid @enderror" type="number" name="sks" id="sks" placeholder="SKS">
                        @error('sks')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="fakultas_id">Fakultas</label>
                        <select class="form-control" name="fakultas_id" id="edit-fakultas-select">
                        </select>
                    </div>
                    <div class="form-group" id="prodi">
                        <label for="prodi_id">Prodi</label>
                        <select class="form-control @error('prodi_id') is-invalid @enderror" name="prodi_id" id="edit-prodi-select">
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm light" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>