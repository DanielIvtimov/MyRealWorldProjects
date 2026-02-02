@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <form action="" method="post" name="productionForm" id="productForm">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control" placeholder="Title">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Slug</label>
                                            <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" readonly>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Short Description</label>
                                            <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Short Description"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Shipping and Returns</label>
                                            <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder=""></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="product-gallery">

                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price" class="form-control" placeholder="Price">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price" class="form-control"
                                                placeholder="Compare Price">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at
                                                price. Enter a lower value into Price.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" class="form-control" placeholder="sku">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control"
                                                placeholder="Barcode">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" id="track_qty_hidden" value="No">
                                                <input class="custom-control-input" type="checkbox" id="track_qty" value="Yes" checked>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Related product</h2>
                                <div class="mb-3">
                                    <label for="related_products">Select Related Products</label>
                                    <select name="related_products[]" id="related_products" class="related-products w-100" multiple>
                                    </select>
                                    <p class="text-muted mt-2">Start typing to search for products...</p>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a Category</option>
                                            @if($categories->isNotEmpty())
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                    </select>
                                    <p class="error"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="sub_category">Sub category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="">Select a Sub Category</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">
                                        <option value="">Select a Brand</option>
                                            @if($brands->isNotEmpty())
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" type="submit">Create</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        $(".related-products").select2({
            ajax: {
                url: "{{ route('products.getProducts')}}",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function(data){
                    return {
                        results: data.tags 
                    }
                },
                cache: true
            },
            placeholder: 'Search for products...',
            minimumInputLength: 3,
            multiple: true,
            width: '100%',
            allowClear: true
        });

        $("#title").keyup(function () {
            element = $(this);
            $("#button[type='submit']").prop('disabled', true);
            $.ajax({
                url: "{{ route('getSlug')}}",
                type: "get",
                data: {
                    title: element.val()
                },
                dataType: "json",
                success: function (response) {
                    $("button[type='submit']").prop('disabled', false);
                    if (response['status']) {
                        $("#slug").val(response['slug']);
                    }
                }
            });
        });

        // Handle track_qty checkbox change
        $("#track_qty").change(function () {
            if ($(this).is(':checked')) {
                $("#track_qty_hidden").val("Yes");
            } else {
                $("#track_qty_hidden").val("No");
            }
        });

        $("#productForm").submit(function (event) {
            event.preventDefault();

            // Ensure track_qty has correct value before submit
            if ($("#track_qty").is(':checked')) {
                $("#track_qty_hidden").val("Yes");
            } else {
                $("#track_qty_hidden").val("No");
            }

            formArray = $(this).serializeArray();
            $.ajax({
                url: "{{ route('products.store')}}",
                type: "post",
                data: formArray,
                dataType: "json",
                success: function (response) {
                    if (response['status'] == true) {
                        window.location.href = "{{ route('products.index') }}";
                    } else {
                        let errors = response['errors'];
                        let fields = ['title', 'slug', 'price', 'sku', 'track_qty', 'category', 'is_featured', 'qty'];

                        fields.forEach(function (field) {
                            let fieldElement = $("#" + field);
                            let errorElement = fieldElement.siblings('p');

                            if (errors[field]) {
                                fieldElement.addClass('is-invalid');
                                errorElement.addClass('invalid-feedback').html(errors[field][0]);
                            } else {
                                fieldElement.removeClass('is-invalid');
                                errorElement.removeClass('invalid-feedback').html('');
                            }
                        });
                    }
                },
                error: function () {
                    console.log("Something went wrong");
                }
            });
        });

        $("#category").change(function () {
            let category_id = $(this).val();

            $("#sub_category").find('option').not(':first').remove();

            if (category_id != "") {
                $.ajax({
                    url: "{{ route('product-subcategories.index') }}",
                    type: "get",
                    data: { category_id: category_id },
                    dataType: "json",
                    success: function (response) {
                        if (response['status'] == true) {
                            $("#sub_category").find('option').not(':first').remove();
                            $.each(response['subCategories'], function (key, item) {
                                $("#sub_category").append(`<option value="${item.id}">${item.name}</option>`);
                            });
                        }
                    },
                    error: function () {
                        console.log("Something went wrong");
                    }
                });
            }
        });

        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            // init: function () {
            //     this.on('addedfile', function (file) {
            //         if (this.files.length > 1) {
            //             this.removeFile(this.files[0]);
            //         }
            //     });
            // },
            url: "{{ route('temp-images.create')}}",
            maxFiles: 10,
            paramName: "image",
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function (file, response) {
                if(response.status == true){
                    let html = `<div class="col-md-3" id="image-row-${response.image_id}"><div class="card">
                        <input type="hidden" name="image_array[]" value="${response.image_id}">
                        <img src="${response.imagePath}" class="card-img-top" alt="" style="width: 100%; height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <a href="javascript:void(0)" onClick="deleteImage(${response.image_id})" class="btn btn-danger btn-sm">Delete</a>
                        </div>
                    </div></div>`;

                    $("#product-gallery").append(html);
                }
            },
            complete: function(file){
                this.removeFile(file);
            },
        });

        function deleteImage(image_id){
            $("#image-row-"+image_id).remove();
        }

    </script>
@endsection