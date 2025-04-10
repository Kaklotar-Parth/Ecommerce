@extends('admin.includes.master-admin')

@section('content')

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row" id="main">
                <!-- Page Heading -->
                <div class="go-title">
                    <div class="pull-right">
                        <a href="{!! url('admin/products/create') !!}" class="btn btn-primary btn-add">
                            <i class="fa fa-plus"></i> Add Physical Product
                        </a>
                        <a href="{!! url('/admin/product/create') !!}" class="btn btn-primary btn-add">
                            <i class="fa fa-plus"></i> Add Virtual Product
                        </a>
                    </div>
                    <h3>Products</h3>
                    <div class="go-line"></div>
                </div>
                <!-- Page Content -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="response">
                            @if (Session::has('message'))
                                <div class="alert alert-success alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ Session::get('message') }}
                                </div>
                            @endif
                        </div>
                        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="10%">ID#</th>
                                    <th>Product Title</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->title }}</td>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                        <td>
                                            @php
                                                // Check if the category is an array or JSON
                                                $categories = is_array($product->category)
                                                    ? $product->category
                                                    : json_decode($product->category, true);

                                                // Load categories only if they exist
                                                $mainCategory = isset($categories[0])
                                                    ? \App\Category::find($categories[0])
                                                    : null;
                                                $subCategory =
                                                    isset($categories[1]) && $categories[1] !== ''
                                                        ? \App\Category::find($categories[1])
                                                        : null;
                                                $childCategory =
                                                    isset($categories[2]) && $categories[2] !== ''
                                                        ? \App\Category::find($categories[2])
                                                        : null;
                                            @endphp

                                            {{ isset($mainCategory) ? $mainCategory->name : 'No Category' }}<br>
                                            @if ($subCategory)
                                                {{ $subCategory->name }}<br>
                                            @endif
                                            @if ($childCategory)
                                                {{ $childCategory->name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->status == 1)
                                                <span class="label label-success">Active</span>
                                            @elseif($product->status == 2)
                                                <span class="label label-warning">Pending</span>
                                            @else
                                                <span class="label label-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form method="POST" action="{!! action('ProductController@destroy', ['id' => $product->id]) !!}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="_method" value="DELETE">

                                                <a href="{!! url('admin/products') !!}/{{ $product->id }}/edit"
                                                    class="btn btn-primary btn-xs">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>

                                                @if ($product->status == 1)
                                                    <a href="{!! url('admin/products') !!}/status/{{ $product->id }}/0"
                                                        class="btn btn-warning btn-xs">
                                                        <i class="fa fa-times"></i> Deactivate
                                                    </a>
                                                @else
                                                    <a href="{!! url('admin/products') !!}/status/{{ $product->id }}/1"
                                                        class="btn btn-success btn-xs">
                                                        <i class="fa fa-check"></i> Activate
                                                    </a>
                                                @endif

                                                <button type="submit" class="btn btn-danger btn-xs"
                                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fa fa-trash"></i> Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->

@stop

@section('footer')

@stop
