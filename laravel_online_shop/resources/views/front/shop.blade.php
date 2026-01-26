@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-6 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 sidebar">
                    <div class="sub-title">
                        <h2>Categories</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="accordionExample">
                                @if($categories->isNotEmpty())
                                    @foreach($categories as $key => $category)
                                        <div class="accordion-item">
                                            @if($category->sub_category->isNotEmpty())
                                                <h2 class="accordion-header" id="heading-{{ $key }}">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-{{ $key }}" aria-expanded="false" aria-controls="collapse-{{ $key }}">
                                                        {{ $category->name }}
                                                    </button>
                                                </h2>

                                                <div id="collapse-{{ $key }}" class="accordion-collapse collapse {{$categorySelected == $category->id ? 'show' : ''}}" aria-labelledby="heading-{{ $key }}"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="navbar-nav">
                                                            @foreach($category->sub_category as $subCategory)
                                                                <a href="{{ route('front.shop', [$category->slug, $subCategory->slug]) }}" class="nav-item nav-link {{ ($subCategorySelected == $subCategory->id) ? 'text-primary' : '' }}">
                                                                    {{ $subCategory->name }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <a href="{{ route('front.shop', $category->slug) }}" class="nav-item nav-link px-3 py-2 {{ ($categorySelected == $category->id) ? 'text-primary' : '' }}">
                                                    {{ $category->name }}
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="sub-title mt-5">
                        <h2>Brand</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            @if($brands->isNotEmpty()) 
                                @foreach($brands as $brand)
                                    <div class="form-check mb-2">
                                        <input {{ in_array($brand->id, $brandsArray) ? "checked" : ""}} class="form-check-input brand-label" type="checkbox" name="brand[]" value="{{ $brand->id }}" id="brand-{{ $brand->id }}">
                                        <label class="form-check-label" for="brand-{{ $brand->id }}">
                                            {{ $brand->name }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif 

                        </div>
                    </div>

                    <div class="sub-title mt-5">
                        <h2>Price</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <input type="text" class="js-range-slider" name="my_range" value=""/>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row pb-3">
                        <div class="col-12 pb-1">
                            <div class="d-flex align-items-center justify-content-end mb-4">
                                <div class="ml-2">
                                    <select name="sort" id="sort" class="form-select form-select-sm">
                                        <option value="latest" {{ (isset($sort) && $sort == "latest") ? "selected" : "" }}>Latest</option>
                                        <option value="price-desc" {{ (isset($sort) && $sort == "price-desc") ? "selected" : ""}}>Price High</option>
                                        <option value="price-asc" {{ (isset($sort) && $sort == "price-asc") ? "selected" : ""}}>Price Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if($products->isNotEmpty())
                            @foreach($products as $product)
                                @php
                                    $productImage = $product->productImages && $product->productImages->isNotEmpty() ? $product->productImages->first() : null;
                                @endphp
                                <div class="col-md-4">
                                    <div class="card product-card">
                                        <div class="product-image position-relative">
                                            <a href="#" class="product-img">
                                                @if(!empty($productImage) && !empty($productImage->image))
                                                    <img class="card-img-top" src="{{ asset('uploads/product/small/'.$productImage->image)}}" />
                                                @else 
                                                    <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}" />
                                                @endif 
                                            </a>
                                            <a class="whishlist" href="222"><i class="far fa-heart"></i></a> 

                                            <div class="product-action">
                                                <a class="btn btn-dark" href="#">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body text-center mt-3">
                                            <a class="h6 link" href="product.php">{{ $product->title }}</a>
                                            <div class="price mt-2">
                                                <span class="h5"><strong>{{ $product->price }}</strong></span>
                                                @if($product->compare_price > 0)
                                                    <span class="h6 text-underline"><del>{{ $product->compare_price }}</del></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif


                        <div class="col-md-12 pt-5">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        var rangeSliderInstance = null;

        $(document).ready(function() {
            // Initialize Range Slider only if element exists
            if ($(".js-range-slider").length > 0) {
                rangeSliderInstance = $(".js-range-slider").ionRangeSlider({
                    type: "double",
                    min: 0,
                    max: 1000,
                    from: {{( $priceMin ?? 0 )}},
                    step: 10,
                    to: {{ ($priceMax ?? 1000) }},
                    skin: "round",
                    max_postfix: "+",
                    prefix: "$",
                    drag_interval: false,
                    onFinish: function(data){
                        apply_filters();
                    }
                });
            }

            // Brand filter change handler
            $(".brand-label").change(function(){
                apply_filters();
            });

            $("#sort").change(function(){
                apply_filters();
            });
        });

        function apply_filters(){
            let brands = [];
            $(".brand-label").each(function(){
                if($(this).is(":checked") == true){
                    brands.push($(this).val());
                }
            });

            let url = "{{ url()->current() }}";
            let params = [];
            
            // Add brand filter if any brands are selected
            if (brands.length > 0) {
                params.push('brand=' + brands.join(','));
            }

            // Get price range from slider if it exists
            if (rangeSliderInstance && rangeSliderInstance.data("ionRangeSlider")) {
                let slider = rangeSliderInstance.data("ionRangeSlider");
                params.push('price_min=' + slider.result.from);
                params.push('price_max=' + slider.result.to);
            }

            // Add sorting parameter
            params.push('sort=' + $("#sort").val());

            // Build final URL with parameters
            if (params.length > 0) {
                url += '?' + params.join('&');
            }

            window.location.href = url;
        }
    </script>
@endsection 