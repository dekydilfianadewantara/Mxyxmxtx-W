<script type="text/javascript">
$(function() {
    $('#compose-modal').modal('show');
    $('#compose-modal').on('shown.bs.modal', function() {
        // console.log('opened');
        // setTimeout($.unblockUI, 2000); 
        $.unblockUI();
    })
});
</script>
<div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">User Detail</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                <label>Name</label>
                                <input type="text" class="form-control" disabled="" value="{{$users->name}}">
                            </div>
                            <div class="col-xs-6">
                                <label>Email Address</label>
                                <input type="email" class="form-control" disabled="" value="{{$users->email}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                <label>Longitude</label>
                                <input type="text" class="form-control" disabled="" value="{{$users->longitude}}">
                            </div>
                            <div class="col-xs-6">
                                <label>Latitude</label>
                                <input type="text" class="form-control" disabled="" value="{{$users->latitude}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" disabled="" value="{{$users->phone}}">
                    </div>
                    <div class="form-group">
                        <label for="note">Address</label>
                        <textarea class="form-control" disabled="" rows="3">{{$users->address}}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer clearfix">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>