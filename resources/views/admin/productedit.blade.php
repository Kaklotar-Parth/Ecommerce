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
                    <h3>Update Product</h3>
                    <div class="go-line"></div>
                </div>
                <!-- Page Content -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="gocover"></div>
                        <div id="response"></div>
                        <form method="POST" action="{!! action('ProductController@update', ['id' => $product->id]) !!}" class="form-horizontal form-label-left"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product Name<span
                                        class="required">*</span>
                                    <p class="small-label">(In Any Language)</p>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="name" class="form-control col-md-7 col-xs-12" name="title"
                                        value="{{ $product->title }}" placeholder="e.g Atractive Stylish Jeans For Women"
                                        required="required" type="text">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Main Category<span
                                        class="required">*</span>

                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="mainid" id="maincats" required>
                                        <option value="">Select Main Category</option>
                                        @foreach ($categories as $category)
                                            @if ($product->category[0] == $category->id)
                                                <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                            @else
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Category<span
                                        class="required">*</span>

                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="subid" id="subs">
                                        <option value="">Select Sub Category</option>
                                        @foreach ($subs as $sub)
                                            @if ($product->category[1] == $sub->id)
                                                <option value="{{ $sub->id }}" selected>{{ $sub->name }}</option>
                                            @else
                                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Child Category<span
                                        class="required">*</span>

                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="childid" id="childs">
                                        <option value="">Select Child Category</option>
                                        @foreach ($child as $data)
                                            @if ($product->category[2] == $data->id)
                                                <option value="{{ $data->id }}" selected>{{ $data->name }}</option>
                                            @else
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> Current Featured
                                    Image <span class="required">*</span>
                                </label>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <img style="max-width: 250px;"
                                        src="{{ url('assets/images/products') }}/{{ $product->feature_image }}"
                                        id="adminimg" alt="No Featured Image Added">
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <input onchange="readURL(this)" id="uploadFile" name="photo" type="file">
                                    {{-- <div id="uploadTrigger" onclick="uploadclick()" style="white-space: normal;" class="form-control btn btn-default"><i class="fa fa-upload"></i> Add Featured Image</div> --}}
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="galdel" value="1" />
                                            Delete Old Gallery Images</label>
                                    </div>

                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number"> Product Gallery
                                    Images <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="file" accept="image/*" name="gallery[]" multiple />
                                    <br>
                                    <p class="small-label">Multiple Image Allowed</p>
                                </div>
                            </div>
                            @if ($product->sizes != null)
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="checkbox">
                                            <label><input type="checkbox" name="pallow" id="allow" value="1"
                                                    checked><strong>Allow Product Sizes</strong></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="item form-group" id="pSizes">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product
                                        Sizes<span class="required">*</span>
                                        <p class="small-label">(Write your own size Separated by Comma[,])</p>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="sizes"
                                            value="{{ $product->sizes }}" data-role="tagsinput" />
                                    </div>
                                </div>
                            @else
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="checkbox">
                                            <label><input type="checkbox" name="pallow" id="allow"
                                                    value="1"><strong>Allow Product Sizes</strong></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="item form-group" id="pSizes" style="display: none;">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product
                                        Sizes<span class="required">*</span>
                                        <p class="small-label">(Write your own size Separated by Comma[,])</p>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control col-md-7 col-xs-12" name="sizes"
                                            value="X,XL,XXL,M,L,S" data-role="tagsinput" />
                                    </div>
                                </div>
                            @endif
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Description<span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="description" id="description" class="form-control" rows="6">{{ $product->description }}</textarea>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Current Price<span
                                        class="required">*</span>
                                    <p class="small-label">(In USD)</p>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" name="price"
                                        value="{{ $product->price }}" placeholder="e.g 20"
                                        pattern="[0-9]+(\.[0-9]{0,2})?%?"
                                        title="Price must be a numeric or up to 2 decimal places." required="required"
                                        type="text">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Previous Price<span
                                        class="required">*</span>
                                    <p class="small-label">(In USD, Leave Blank if not Required)</p>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" name="previous_price"
                                        value="{{ $product->previous_price }}" placeholder="e.g 25"
                                        pattern="[0-9]+(\.[0-9]{0,2})?%?"
                                        title="Price must be a numeric or up to 2 decimal places." type="text">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Stock<span
                                        class="required">*</span>
                                    <p class="small-label">(Leave Empty will Show Always Available)</p>
                                </label>
                                {{-- <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" name="stock" value="{{$product->stock}}" placeholder="e.g 15" pattern="[0-9]{1,10}" type="text">
                                </div> --}}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="stock" class="form-control" value="{{ $product->stock }}"
                                        name="stock" placeholder="e.g 15" pattern="[0-9]{1,10}" type="text">
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Select File Type:<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label><input type="radio" class="file-type-checkbox" name="file_type"
                                            value="image">
                                        Images (JPEG, PNG, JPG)</label>
                                    <label><input type="radio" class="file-type-checkbox" name="file_type"
                                            value="pdf">
                                        PDF</label>
                                    <label><input type="radio" class="file-type-checkbox" name="file_type"
                                            value="zip_gif"> ZIP/GIF</label>
                                    <label><input type="radio" class="file-type-checkbox" name="file_type"
                                            value="mp3_mp4"> MP3/MP4</label>

                                </div>
                            </div>
                            <!-- File Upload Sections -->
                            <div id="pdfuploadFields"></div>
                            <div id="zipgifuploadFields"></div>
                            <div id="mp3mp4Fields"></div>
                            <div id="serialNumberFields"></div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Buy/Return Policy<span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="policy" id="policy" class="form-control" rows="6">{{ $product->policy }}</textarea>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number">
                                </label>
                                <div class="col-md-9 col-sm-6 col-xs-12" data-toggle="buttons">
                                    @if ($product->featured == 1)
                                        <label class="btn btn-default active">
                                            <input type="checkbox" name="featured" value="1" autocomplete="off"
                                                checked>
                                            <span class="go_checkbox"><i class="glyphicon glyphicon-ok"></i></span>
                                            Add to Featured Product
                                        </label>
                                    @else
                                        <label class="btn btn-default">
                                            <input type="checkbox" name="featured" value="1" autocomplete="off">
                                            <span class="go_checkbox"><i class="glyphicon glyphicon-ok"></i></span>
                                            Add to Featured Product
                                        </label>
                                    @endif
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button id="add_ads" type="submit" class="btn btn-success btn-block">Update
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
    <script>
        const stockInput = document.getElementById('stock');

        // Update stock value dynamically
        stockInput.addEventListener('input', function() {
            let stockValue = parseInt(this.value) || 10;
            console.log("Stock Value Updated:", stockValue); // Debugging log
        });

        // Handle file type selection and field generation
        document.querySelectorAll('.file-type-checkbox').forEach(radio => {
            radio.addEventListener('change', function() {
                let stockValue = parseInt(stockInput?.value) || 10;
                console.log("Stock Value Selected:", stockValue); // Default stock value: 10

                // Clear all fields before adding new ones
                document.getElementById('pdfuploadFields').innerHTML = "";
                document.getElementById('zipgifuploadFields').innerHTML = "";
                document.getElementById('mp3mp4Fields').innerHTML = "";
                document.getElementById('serialNumberFields').innerHTML = "";

                let selectedType = this.value;
                let fieldContainer;
                let fieldName;
                let labelText;
                let acceptTypes;

                if (selectedType === 'pdf') {
                    fieldContainer = document.getElementById('pdfuploadFields');
                    fieldName = 'pdf_files[]';
                    labelText = 'PDF File';
                    acceptTypes = '.pdf';
                } else if (selectedType === 'zip_gif') {
                    fieldContainer = document.getElementById('zipgifuploadFields');
                    fieldName = 'zip_gif_files[]';
                    labelText = 'ZIP/GIF File';
                    acceptTypes = '.zip,.gif';
                } else if (selectedType === 'mp3_mp4') {
                    fieldContainer = document.getElementById('mp3mp4Fields');
                    fieldName = 'mp3_mp4_files[]';
                    labelText = 'MP3/MP4 File';
                    acceptTypes = 'audio/*,video/*';
                } else if (selectedType === 'image') {
                    fieldContainer = document.getElementById('serialNumberFields');
                    fieldName = 'serial_numbers[]';
                    labelText = 'Serial Number';
                    acceptTypes = 'image/*';
                }

                // Generate fields based on stock count (max 10)
                for (let i = 1; i <= stockValue; i++) {
                    let div = document.createElement('div');
                    div.classList.add('item', 'form-group');

                    let label = document.createElement('label');
                    label.classList.add('control-label', 'col-md-3', 'col-sm-3', 'col-xs-12');
                    label.innerHTML = `${labelText} ${i} <span class="required">*</span>`;

                    let inputDiv = document.createElement('div');
                    inputDiv.classList.add('col-md-6', 'col-sm-6', 'col-xs-12');

                    let input = document.createElement('input');
                    input.classList.add('form-control');
                    input.type = selectedType === 'image' ? 'text' : 'file';
                    input.name = fieldName;
                    input.placeholder = `${labelText} ${i}`;
                    input.accept = acceptTypes;

                    if (selectedType === 'image') {
                        input.required = true;
                    }

                    inputDiv.appendChild(input);
                    div.appendChild(label);
                    div.appendChild(inputDiv);
                    fieldContainer.appendChild(div);
                }
            });
        });
    </script>
@stop
