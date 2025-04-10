@extends('admin.includes.master-admin')

@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row" id="main">

                <div class="go-title">
                    <div class="pull-right">
                        <a href="{{ url('admin/products') }}" class="btn btn-default btn-back">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                    <h3>Add Virtual Product</h3>
                    <div class="go-line"></div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="gocover"></div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ action('VirtualController@store') }}"
                            class="form-horizontal form-label-left" enctype="multipart/form-data">
                            {{ csrf_field() }}


                            {{-- Product Type Dropdown (Physical Pre-selected and Disabled) --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Type:<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="product_type" id="product_type" required disabled>
                                        <option value="physical" selected>Virtual</option>
                                    </select>
                                    <input type="hidden" name="product_type" value="Virtual">
                                </div>
                            </div>

                            {{-- Main Category --}}
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Category<span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="mainid" id="maincats" required>
                                        <option value="">Select Main Category</option>
                                        @foreach ($categories as $category)
                                            {{-- Only show Virtual and Physical categories --}}
                                            @if (in_array($category->name, ['Virtual']))
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Name<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="name" class="form-control" name="title"
                                        placeholder="e.g. E-Book, Software, Music, etc." required type="text">
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Upload Virtual Product Files:<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="file" name="virtual_files" class="form-control" id="virtualProductFiles"
                                        multiple accept="image/*,.pdf,.gif,.zip,audio/*,video/*" required>
                                    <small class="text-muted">You can upload multiple files: images, PDFs, GIFs, ZIPs,
                                        audio, or video files</small>
                                </div>
                            </div>
                            {{-- 
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Select File Type:<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label><input type="radio" class="file-type-checkbox" name="file_type" value="pdf">
                                        PDF</label>
                                    <label><input type="radio" class="file-type-checkbox" name="file_type"
                                            value="zip_gif"> ZIP/GIF</label>
                                    <label><input type="radio" class="file-type-checkbox" name="file_type"
                                            value="mp3_mp4"> MP3/MP4</label>
                                    <label><input type="radio" class="file-type-checkbox" name="file_type" value="image">
                                        Images (JPEG, PNG, JPG)</label>
                                </div>
                            </div> --}}


                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Virtual Product Price<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control" name="virtual_price" placeholder="e.g 20"
                                        pattern="[0-9]+(\.[0-9]{0,2})?%?"
                                        title="Price must be numeric or up to 2 decimal places." required type="text">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Virtual Product Previous Price<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control" name="previous_price" placeholder="e.g 25"
                                        pattern="[0-9]+(\.[0-9]{0,2})?%?"
                                        title="Price must be numeric or up to 2 decimal places." type="text">
                                </div>
                            </div>

                            <!-- Stock input field -->
                            {{-- <input type="hidden" type="number" id="stock" value="10" min="0" /> --}}

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Stock</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="stock" class="form-control" name="stock" placeholder="e.g 15"
                                        pattern="[0-9]{1,10}" type="text">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Select File Type:<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label><input type="radio" class="file-type-checkbox" name="file_type" value="image">
                                        Images (JPEG, PNG, JPG)</label>
                                    <label><input type="radio" class="file-type-checkbox" name="file_type" value="pdf">
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
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Virtual Product Description:<span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" name="description" rows="4" placeholder="Enter product description" required></textarea>
                                </div>
                            </div>

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
                                    <button id="add_ads" type="submit" class="btn btn-success btn-block">Add Virtual
                                        Product</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
