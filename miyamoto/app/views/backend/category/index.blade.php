@extends('backend.layouts.content')

@section('header-content')
<div class="pull-right">
    <a href="{{URL::to('admin/product_category/create')}}" class="btn btn-primary">Create</a>
</div>
@stop

@section('body-content')
@if(Session::has('product_category'))
    <div class="alert alert-success alert-dismissable">
        <i class="fa fa-check"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Success!</b> {{Session::get('product_category')}}.
    </div>
@endif

@if(Session::has('product_category_alert'))
    <div class="alert alert-warning alert-dismissable">
        <i class="fa fa-warning"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Warning!</b> {{Session::get('product_category_alert')}}.
    </div>
@endif
<div class="box">
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
            <tr>
                <th style="width: 50px">#</th>
                <th>Name</th>
                <th>Code Prefix</th>
                <th>Total Product</th>
                <th>Created by</th>
                <th>Modified at</th>
                <th>Action</th>
            </tr>
            </thead>
            <?php 
            if(Input::get('page')){
                $page = Input::get('page');
            }else{
                $page = 1;
            }
            $nomor = $page + ($page-1) * ($limit-1);
            ?>
            <tbody>
            @foreach($category as $row)
            <?php $total = Helper::TotalOfProduct($row->id); ?>
            <tr>
                <td>{{$nomor++}}.</td>
                <td>{{$row->name}}</td>
                <td>{{$row->prefix}}</td>
                <td>{{$total}}</td>
                <td>{{$row->author->name}}</td>
                <td>{{date('d F Y, H:i:s',strtotime($row->updated_at))}}</td>
                <td>
                    <a href="{{URL::to('admin/product_category/edit/'.$row->id)}}" class="btn btn-info btn-xs"><i class="fa fa-fw fa-edit"></i> Edit</a>
                    <a href="{{URL::to('admin/product_category/delete/'.$row->id)}}" class="btn btn-danger btn-xs delete"><i class="fa fa-fw fa-trash-o"></i> Delete</a>
                    <!-- <a href="">Hapus</a> -->
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix">
        {{$category->links()}}
    </div>
</div>
@stop