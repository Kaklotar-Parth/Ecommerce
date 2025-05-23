@extends('admin.includes.master-admin')

@section('content')

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row" id="main">

                <!-- Page Heading -->
                <div class="go-title">
                    <div class="pull-right">
                        <a href="{!! url('admin/products') !!}" class="btn btn-default btn-back"><i class="fa fa-arrow-left"></i>
                            Back</a>
                    </div>
                    <h3>Add Product</h3>
                    <div class="go-line"></div>
                </div>

                <!-- Page Content -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="gocover"></div>
                        <div id="response"></div>
                        <form method="POST" action="{!! action('ProductController@store') !!}" class="form-horizontal form-label-left"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{-- Product Type Dropdown (Physical Pre-selected and Disabled) --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Type:<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="product_type" id="product_type" required disabled>
                                        <option value="physical" selected>Physical</option>
                                    </select>
                                    <input type="hidden" name="product_type" value="physical">
                                </div>
                            </div>

                            {{-- Product Name --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product Name<span
                                        class="required">*</span>
                                    <p class="small-label">(In Any Language)</p>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="name" class="form-control col-md-7 col-xs-12" name="title"
                                        placeholder="e.g Attractive Stylish Jeans For Women" required="required"
                                        type="text">
                                </div>
                            </div>
                            {{-- Main Category --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Main Category<span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="mainid" id="maincats" required>
                                        <option value="">Select Main Category</option>
                                        @foreach ($categories as $category)
                                            {{-- Exclude Virtual category --}}
                                            @if ($category->name !== 'Virtual')
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            {{-- Sub Category --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Category<span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="subid" id="subs" disabled>
                                        <option value="">Select Sub Category</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Child Category --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Child Category<span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="childid" id="childs" disabled>
                                        <option value="">Select Child Category</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Featured Image --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Current Featured
                                    Image <span class="required">*</span>
                                </label>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <img style="max-width: 250px;" src="" id="adminimg"
                                        alt="No Featured Image Added">
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <input onchange="readURL(this)" id="uploadFile" accept="image/*" name="photo"
                                        type="file" required>
                                </div>
                            </div>

                            {{-- Product Gallery --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number">Product Gallery
                                    Images <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="file" accept="image/*" name="gallery[]" multiple />
                                    <br>
                                    <p class="small-label">Multiple Images Allowed</p>
                                </div>
                            </div>

                            {{-- Product Sizes --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="pallow" id="allow"
                                                value="1"><strong>Allow
                                                Product Sizes</strong></label>
                                    </div>
                                </div>
                            </div>

                            <div class="item form-group" id="pSizes" style="display: none;">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product Sizes<span
                                        class="required">*</span>
                                    <p class="small-label">(Write your own sizes separated by comma[,])</p>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" name="sizes" value="X,XL,XXL,M,L,S"
                                        data-role="tagsinput" />
                                </div>
                            </div>

                            {{-- Product Description --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Description<span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="description" id="description" class="form-control" rows="6"></textarea>
                                </div>
                            </div>

                            {{-- Product Price --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Current Price<span
                                        class="required">*</span>
                                    <p class="small-label">(In USD)</p>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" name="price" placeholder="e.g 20"
                                        pattern="[0-9]+(\.[0-9]{0,2})?%?"
                                        title="Price must be a numeric or up to 2 decimal places." required="required"
                                        type="text">
                                </div>
                            </div>

                            {{-- Product Previous Price --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Previous Price
                                    <p class="small-label">(In USD, Leave Blank if not Required)</p>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" name="previous_price"
                                        placeholder="e.g 25" pattern="[0-9]+(\.[0-9]{0,2})?%?"
                                        title="Price must be a numeric or up to 2 decimal places." type="text">
                                </div>
                            </div>

                            {{-- Product Stock --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Stock
                                    <p class="small-label">(Leave Empty will Show Always Available)</p>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" name="stock" placeholder="e.g 15"
                                        pattern="[0-9]{1,10}" type="text">
                                </div>
                            </div>

                            {{-- Product Buy/Return Policy --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Buy/Return Policy<span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="policy" id="policy" class="form-control" rows="6"></textarea>
                                </div>
                            </div>

                            {{-- Featured Product Checkbox --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                                <div class="col-md-9 col-sm-6 col-xs-12" data-toggle="buttons">
                                    <label class="btn btn-default">
                                        <input type="checkbox" name="featured" value="1" autocomplete="off">
                                        <span class="go_checkbox"><i class="glyphicon glyphicon-ok"></i></span>
                                        Add to Featured Product
                                    </label>
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button id="add_ads" type="submit" class="btn btn-success btn-block">Add New
                                        Product</button>
                                </div>
                            </div>
                        </form>
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
    <script>
        bkLib.onDomLoaded(function() {
            new nicEditor().panelInstance('description');
            new nicEditor().panelInstance('policy');
        });

        $("#allow").change(function() {
            $("#pSizes").toggle();
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#adminimg').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@stop
