<?php
class Modal{
	public static function filter()
	{
		$html='
        <div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Filter Data</h4>
                    </div>
                    <form action="#" method="post">
                        <div class="modal-body">
                             Are you sure want to delete this data ?
                        </div>
                        <div class="modal-footer clearfix">
                        	<button class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <input type="submit" class="btn btn-default" value="Submit" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
		';
		return $html;
	}
}