<div class="modal modal-blur fade" id="skip_report_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-status bg-success"></div>
            <div class="modal-body text-center py-4">
                <!-- Download SVG icon from http://tabler-icons.io/i/circle-check -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M9 12l2 2l4 -4" />
                </svg>
                <h3>Import Berhasil</h3>
                <div class="text-muted">Data duplikat diabaikan</div>
                <p class="mb-0" id="skip_report">
                </p>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <a href="#" class="btn btn-success w-100" onclick="window.location.reload();">
                                Ok
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>