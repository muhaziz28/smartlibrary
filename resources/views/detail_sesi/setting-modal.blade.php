<div class="modal modal-blur fade" id="setting_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Penganturan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-status-top bg-info"></div>
                    <div class="card-body">
                        <h3 class="card-title">Telegram Chat ID</h3>
                        @if($sesiMataKuliah->chat_id === null)
                        <p class="text-muted">
                            Silahkan inputkan chat id telegram. Untuk proses integrasi bot, silahkan hubungi admin sistem.
                        </p>
                        @endif
                        <form action="{{ route('detail_sesi_mata_kuliah.updateChatID') }}" method="post" id="chatID">
                            @csrf
                            <div class="mb-3">
                                <input type="hidden" name="id" id="id" value="{{ $sesiMataKuliah->id }}">
                                <input type="text" class="form-control" name="chat_id" id="chat_id" value="{{ $sesiMataKuliah->chat_id }}" placeholder="CHAT ID ...">
                            </div>
                            <button type="submit" class="btn btn-outline-info">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-spreadsheet" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    <path d="M8 11h8v7h-8z" />
                                    <path d="M8 15h8" />
                                    <path d="M11 11v7" />
                                </svg>
                                Simpan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="card-status-top bg-info"></div>
                <div class="card-body">
                    <h3 class="card-title">Radius Absensi</h3>

                    <p class="text-muted">
                        Secara default, radius di-set <strong> 100 meter </strong>.
                    </p>

                    <form action="{{ route('detail_sesi_mata_kuliah.radius') }}" method="POST" id="radius">
                        @csrf
                        <div class="mb-3">
                            <input type="hidden" name="id" id="id" value="{{ $sesiMataKuliah->id }}">
                            <input type="number" class="form-control" name="rad" id="rad" value="{{ $sesiMataKuliah->radius }}" placeholder="RADIUS...">
                        </div>
                        <button type="submit" class="btn btn-outline-info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-spreadsheet" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <path d="M8 11h8v7h-8z" />
                                <path d="M8 15h8" />
                                <path d="M11 11v7" />
                            </svg>
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Close
                </a>
            </div>
        </div>
    </div>
</div>