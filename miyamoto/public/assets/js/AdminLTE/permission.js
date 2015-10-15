 $(function() {
    $('.parent_all').click(function() {
    	var all = this.checked;
    	var ID = $(this).data('id');
    	$('.parent_child_'+ID).each(function() {
    		this.checked = all;
    	});

    });

    $(".child").click(function(){
        var child    = $(this).data('child');
        var id_child = $(this).val();
        if($(".parent_child_"+child).length == $(".parent_child_"+child+":checked").length){
            $(".root_"+child).data('id',$(this).data('child')).each(function() {
                this.checked = true;
            });
        }else{
            $(".root_"+child).data('id',$(this).data('child')).each(function() {
                this.checked = false;
            });
        }            
    });

    $(document).on('click',".parent_selected",function() {
      var value = $(this).val();
      if(value == ""){
        $('.submenu').hide();
        $('.icon').removeAttr('disabled');
      }else{
        $('.submenu').show();
        $('.icon').attr('disabled','disabled');
      }
    });

});

$(window).load(function() {
    /*! pace 0.4.17 */
    (function() {
      $('.submenu').hide();
          var value = $(".parent_selected").val();
          if(value == ""){
            $('.icon').removeAttr('disabled');
          }else{
            $('.submenu').show();
            $('.icon').attr('disabled','disabled');
          }
    }).call(this);
});