
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
                <h4 class="modal-title">Detail</h4>
            </div>
            <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" value="{{$messages->name}}" readonly class="form-control" id="name" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" value="{{$messages->email}}" readonly class="form-control" id="email" placeholder="Email Address">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" value="{{$messages->handphone}}" readonly class="form-control" id="phone" placeholder="Phone Number">
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" readonly id="message" rows="3" placeholder="Message">{{$messages->content}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer clearfix">
                    <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
        </div>
    </div>
</div>