$(function() {
	$(document).on('click','.delete',function(){
		return confirm('Are you sure want to delete ?');
	});

    $(document).on('click','.finish',function(){
        return confirm('Are you sure want to finish this request ?');
    });

    $(document).on('click','.process',function(){
        return confirm('Are you sure want to process this request ?');
    });

    $(document).on('click','.accept-bid',function(){
        return confirm('Are you sure want to accept this bid ?');
    });

    $(document).on('click','.restore',function(){
        return confirm('Are you sure want to restore ?');
    });

    $(document).on('click','.actived',function(){
        return confirm('Are you sure want to active ?');
    });

    $(document).on('click','.noactived',function(){
        return confirm('Are you sure want to non active ?');
    });

    $(document).on('click','.published',function(){
        return confirm('Are you sure want to publish ?');
    });

    $(document).on('click','.drafted',function(){
        return confirm('Are you sure want to draft ?');
    });

	$('.filter').on('click',function (e){
        e.preventDefault();
	        if($(this).data('method')==='filter'){
		        $('#compose-modal').modal('show');
	        }
    });

    $('.info-client').on('click',function (e){
        var idClient = $(this).data('idclient');
        $('#open-modal').load('/admin/orders/modal-client-info/'+idClient);
    });

    $('.message-detail').on('click',function (e){
        var idMessage = $(this).data('idmessage');
        $('#open-modal').load('/admin/messages/detail/'+idMessage);
    });

    $('.add-orders').on('click',function (e){
        $('#open-modal').load('/admin/orders/modal-add-orders');
    });

    $('.add-item').on('click',function (e){
        // e.preventDefault();
        var id_orders = $(this).data('idorder');
        $('#open-modal').load('/admin/orders/modal-add-item/'+id_orders);
    });

    $('.edit-payment').on('click',function (e){
        // e.preventDefault();
        var id_orders = $(this).data('idorder');
        var id_payment = $(this).data('idpayment');
        $('#open-modal').load('/admin/orders/modal-edit-payment/'+id_orders+'/'+id_payment);
    });

    $('.add-payment').on('click',function (e){
        // e.preventDefault();
        var id_orders = $(this).data('idorder');
        $('#open-modal').load('/admin/orders/modal-add-payment/'+id_orders);
    });

    $('.detail-client').on('click',function (e){
        // e.preventDefault();
        var id_client = $(this).data('idclient');
        $('#open-modal').load('/admin/clients/modal-detail-client/'+id_client);
    });

    $('.detail-user').on('click',function (e){
        // e.preventDefault();
        var id_user = $(this).data('iduser');
        $('#open-modal').load('/admin/users/modal-detail-user/'+id_user);
    });

    $('.deleted-staff').on('click',function (e){
        $('#open-modal').load('/admin/users/modal-deleted-user');
    });

    $(document).on('change','.category_product',function(){
    	$('.subcategory').attr('data-selected','');
        subcategory();
    });
    function subcategory(){
        var category_product = $('.category_product').val();
        $.ajax({
            type : 'POST',
            url  : '/admin/products/subcategory',
            data : {id_category : category_product},
            beforeSend : function() {
                $('.subcategory').html("<option value=''>Loading...</option>");
            },
            success : function(data){
                $('.subcategory').html(data);
            }
        }).done(function(){
            $('.subcategory').val($('.subcategory').attr('data-selected'));
        });
    }

    $(document).on('click','.ajax-delete',function (e){
        e.preventDefault();
        var id_item    = $(this).data('id');
        var id_request = $(this).data('request');

    });
   //hanyamoto

    $(document).on('click','.waiting',function(){
        $('.waiting').addClass('disabled');
        $('.waiting').html('Waiting ...');
    })

    $(document).on('click','.notif', function(){
        
        $.ajax({
            type : 'GET',
            url  : '/admin/profile/notif'
        }).done(function(data){
            if(data>0){
                $('.notif-number').remove();

                // setTimeout(function () {
                    $('.new-notif').animate({backgroundColor: '#fff'}, 2000);
                // }, 2000);
                // $('.notif-frase').html('You have 0 notifications');
            }
        });
    });
});