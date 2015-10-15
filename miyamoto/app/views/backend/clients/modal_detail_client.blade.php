<script type="text/javascript">
$(function() {
    $('#compose-modal').modal('show');
});
</script>
<div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Client Detail</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" disabled="" value="{{$clients->name}}">
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" class="form-control" disabled="" value="{{$clients->email}}">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" disabled="" value="{{$clients->phone}}">
                    </div>
                    <div class="form-group">
                        <label for="note">Address</label>
                        <textarea class="form-control" disabled="" rows="3">{{$clients->address}}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer clearfix">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>