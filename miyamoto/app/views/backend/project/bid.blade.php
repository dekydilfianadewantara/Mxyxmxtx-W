@extends('backend.layouts.content')

@section('end-script')
    @parent

    <script src="{{asset('assets/js/accounting.min.js')}}"></script>
    <script src="{{asset('assets/js/AdminLTE/jquery.form-validator.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/jquery.datetimepicker.css')}}"/ >
    <script src="{{asset('assets/js/jquery.datetimepicker.js')}}"></script>
    <script type="text/javascript">
    $.formUtils.addValidator({
        name : 'max_number',
        validatorFunction : function(value, $el, config, language, $form) {
            // return parseInt(value, 10) % 2 === 0;
            return (parseInt(value) <= {{$detail->open_price}});
        },
        errorMessage : "Price can't greater than ${{number_format($detail->open_price,2)}}",
        errorMessageKey: 'badEvenNumber'
    });

    $.validate({
      form : '#request'
    });



    jQuery('.estimation').datetimepicker({ 
        format:'Y-m-d H:i',
        minDate:'0',
        scrollInput:false
    });
    </script>
@stop

@section('body-content-root')
@if(Session::has('project'))
    <div class="alert alert-success alert-dismissable">
        <i class="fa fa-check"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{Session::get('project')}}.
    </div>
@endif
@if($detail->close_date >  date('Y-m-d H:i:s'))
<div class="page-header">
    <h3>{{$detail->title}}</h3>
</div>
<div class="box">
    {{Form::open(array('url'=>'admin/find-work/bid/'.Helper::Encrypt($detail->id), 'method'=>'POST', 'id'=>'request'))}}
        <div class="box-body">  
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="estimation">Estimated Duration</label>
                            <input type="text" name="estimation" autocomplete="off" value="{{(isset($bid->time)?date('Y-m-d H:i',strtotime($bid->time)):'')}}" class="form-control estimation" id="estimation" placeholder="YYYY-M-D H:i">
                            {{$errors->first('estimation','<p class="text-red">:message</p>')}}
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" name="price" autocomplete="off" value="{{(isset($bid->price)?$bid->price:'')}}" class="form-control" id="price" placeholder="Price" data-validation="max_number">
                            {{$errors->first('price','<p class="text-red">:message</p>')}}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cover_letter">Cover Letter</label>
                    <textarea id="cover_letter" name="cover_letter" class="form-control" style="height:200px" rows="3" placeholder="Cover Letter">{{(isset($bid->cover_letter)?$bid->cover_letter:'')}}</textarea>
                    {{$errors->first('cover_letter','<p class="text-red">:message</p>')}}
                </div>
        </div><!-- /.box-body -->

        <div class="box-footer">
            <a href="{{URL::to('admin/find-work')}}" class="btn btn-default">{{trans('button.bc')}}</a>
            <button type="submit" class="btn btn-primary waiting">{{(isset($bid)?trans('button.up'):trans('button.su'))}}</button>
        </div>
    {{Form::close()}}
</div>
@else
<div class="col-xs-12">
    <div class="box no-border">
            <h3 class="box-title pull-left">Request Has expired</h3>
    </div>
</div>
<div class="col-xs-12">
    <!-- general form elements -->
    <div class="no-border" style="">
        <a href="{{URL::to('admin/find-work')}}" class="btn btn-default">{{trans('button.bc')}}</a>
    </div>
</div>
@endif
@stop