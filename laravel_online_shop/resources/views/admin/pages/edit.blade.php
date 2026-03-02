@extends("admin.layouts.app")

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Page</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('pages.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="post" id="pageForm" name="pageForm">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input value="{{ $page->name }}" type="text" name="name" id="name" class="form-control" placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input value="{{ $page->slug }}" type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="content">Content</label>
                                    <textarea name="content" id="content" class="summernote" cols="30" rows="10">{{ $page->content }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('pages.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection 

@section('customJs')
<script>
$(document).ready(function(){
    $("#pageForm").submit(function(event){
        event.preventDefault();

        var $form = $(this);
        var $submitBtn = $form.find("button[type='submit']");

        if (typeof $.fn.summernote !== 'undefined' && $("#content").length) {
            try { $("#content").val($("#content").summernote('code')); } catch (e) {}
        }

        $form.find(".is-invalid").removeClass("is-invalid");
        $form.find(".invalid-feedback").removeClass("invalid-feedback").html("");

        $submitBtn.prop("disabled", true);

        $.ajax({
            url: "{{ route('pages.update', $page->id) }}",
            type: "put",
            data: $form.serialize(),
            dataType: "json",
            success: function(response){
                if (response.status == true) {
                    window.location.href = "{{ route('pages.index') }}";
                    return;
                }
                if (response.notFound == true) {
                    window.location.href = "{{ route('pages.index') }}";
                    return;
                }

                var errors = response.errors || {};
                var fields = ["name", "slug"];
                fields.forEach(function(field) {
                    var $input = $("#" + field);
                    var $msgEl = $input.siblings("p");
                    var message = errors[field];
                    if (message) {
                        message = Array.isArray(message) ? message[0] : message;
                        $input.addClass("is-invalid");
                        $msgEl.addClass("invalid-feedback").html(message);
                    } else {
                        $input.removeClass("is-invalid");
                        $msgEl.removeClass("invalid-feedback").html("");
                    }
                });
                $submitBtn.prop("disabled", false);
            },
            error: function(jqXHR, exception){
                $submitBtn.prop("disabled", false);
                console.log("Something went wrong");
            },
            complete: function(){
                $submitBtn.prop("disabled", false);
            }
        });
    });

    $("#name").on("keyup", function(){
        var element = $(this);
        $.ajax({
            url: "{{ route('getSlug') }}",
            type: "get",
            data: { title: element.val() },
            dataType: "json",
            success: function(response){
                if (response.status == true) {
                    $("#slug").val(response.slug);
                }
            }
        });
    });
});
</script>
@endsection