<div class="modal modal-blur fade" id="sync_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sync Sesi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sync_modal_form" action="{{ route('sync.preview') }}" method="GET">
                @csrf
                <input type="hidden" name="current" id="current" value="{{ $sesiMataKuliah->kode_sesi}}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Sesi</label>
                        <select name="sesi" id="sesi" class="form-select" required>
                            <option value="">-- Pilih Sesi --</option>
                            @foreach ($sesi as $s)
                            <option value="{{ $s->id }}">{{ $s->kode_sesi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary ms-auto">
                        Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>