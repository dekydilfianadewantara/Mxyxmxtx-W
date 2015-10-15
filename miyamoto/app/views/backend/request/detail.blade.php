@extends('backend.layouts.content')

@section('body-content-child')
<div class="col-xs-12">
    <div class="box box-info">
        <div class="box-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="title">Title</label>
                        <input type="text" disabled="" value="{{$request->title}}" class="form-control" id="title" placeholder="Title">
                    </div>
                    <div class="col-xs-6">
                        <label for="open_price">Standard Price</label>
                        <input type="text" disabled="" value="${{number_format($request->open_price)}}" class="form-control" id="open_price" placeholder="Open Price">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="open_date">Open Date</label>
                        <input type="text" disabled="" value="{{date('Y-m-d H:i',strtotime($request->open_date))}}" class="form-control open_date" id="open_date" placeholder="yyyy-mm-dd hh:mm">
                    </div>
                    <div class="col-xs-6">
                        <label for="close_date">Close Date</label>
                        <input type="text" disabled="" value="{{date('Y-m-d H:i',strtotime($request->close_date))}}" class="form-control close_date" id="close_date" placeholder="yyyy-mm-dd hh:mm">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">
                        <label for="longitude_from">Longitude From</label>
                        <input type="text" disabled="" value="{{$request->longitude_from}}" class="form-control" id="longitude_from" placeholder="Longitude From">
                    </div>
                    <div class="col-xs-3">
                        <label for="latitude_from">Latitude From</label>
                        <input type="text" disabled="" value="{{$request->latitude_from}}" class="form-control" id="latitude_from" placeholder="Latitude From">
                    </div>
                    <div class="col-xs-3">
                        <label for="longitude_to">Longitude To</label>
                        <input type="text" disabled="" value="{{$request->longitude_to}}" class="form-control" id="longitude_to" placeholder="Longitude To">
                    </div>
                    <div class="col-xs-3">
                        <label for="latitude_to">Latitude To</label>
                        <input type="text" disabled="" value="{{$request->latitude_to}}" class="form-control" id="latitude_to" placeholder="Latitude To">
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div>
</div>
<div class="col-xs-12">
    <div class="box box-success">
        <div class="box-header">
            <h3 class="box-title pull-left">Items</h3>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Product</th>
                <th style="width:10%">Weight</th>
                <th style="width:20%">Picture</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
                @foreach($item as $key => $row)
                <tr>
                    <td><input type="text" class="form-control" disabled="" value="{{$row->name}}" placeholder="Name"></td>
                    <td><input type="text" disabled="" class="form-control" value="{{$row->weight}}" placeholder="Weight on Kg"></td>
                    <td>
                        <?php
                        $paths       = public_path('assets/store/request/'.$row->image);
                        ?>
                        @if(!empty($row->image) && is_file($paths))
                        <img width="150" style="border: 1px solid #DDD;padding: 5px;" src="{{asset('assets/store/request/'.$row->image)}}" alt="img"/>
                        @else
                        <img width="150" style="border: 1px solid #DDD;padding: 5px;" src="{{asset('assets/store/no_image.png')}}" alt="img"/>
                        @endif

                        {{-- <img src="{{URL::to('admin/request/image?id='.$row->id)}}" width="150"> --}}
                    </td>
                    <td><textarea class="form-control" disabled="" placeholder="Description">{{$row->description}}</textarea></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>
<div class="col-xs-12">
    <!-- general form elements -->
    <div class="box no-border" style="padding:10px">
        <a href="{{URL::to('admin/request')}}" class="btn btn-default">{{trans('button.bc')}}</a>
    </div>
</div>

@stop