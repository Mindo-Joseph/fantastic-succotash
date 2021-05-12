<div id="add-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Banner</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_banner_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="AddCardBox">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-blue waves-effect waves-light submitAddForm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="edit-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Banner</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_edit_banner_form" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editCardBox">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-blue waves-effect waves-light submitEditForm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>