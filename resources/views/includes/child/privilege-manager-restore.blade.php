{{-- Modal for verifying to ban privilege --}}
<div id="bannedPrivilege" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Do you want to restore this privilege?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                @include('includes.forms.child-privilege-restore')

                {{-- <div class='btn-group btn-group-lg'>
                    <button id="bannedSubmit" type="button" class="btn btn-primary">Yes</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                </div> --}}
            </div>
        </div>
    </div>
</div>