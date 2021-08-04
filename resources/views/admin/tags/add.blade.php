@extends('admin.layouts.main')
@section('content')
<h1>Thêm tag</h1>
<div class="col-md-6 offset-md-3">
    <form action="javascrpit:void(0)" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="exampleInputEmail1" class="form-label">Tên tag</label>
            <input type="text" class="form-control" name="name" id="name">
            <span style="color:red;" id="errorName"></span>
        </div><br>
        <div class="text-center">
            <button class="btn btn-sm btn-success" type="submit">Tạo mới</button>
            <a href="" class="btn btn-sm btn-warning">Hủy</a>
        </div>
    </form>
</div>
@endsection
@section('pagejs')
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script>
$(document).ready(function() {
    $(".btn-success").click(function(e) {
        e.preventDefault();
        var formData = new FormData($('form')[0]);
        formData.append('_token', '{{ csrf_token() }}')
        formData.append('name', $('#name').val())

        $.ajax({
            url: "{{ route('tag.saveAdd') }}",
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                if (data.success) {
                    window.location.href =
                        "{{route('tag.listTag',['message'=>'Thêm tag thành công'])}}";
                } else {
                    $.each(data.error, function(key, value) {
                        if (key == "name") {
                            $("#errorName").html(value);
                        }
                    });
                }
            },
        });
    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#image-show').html('<img src="#" id="category-img-tag" width="200px" />');
            $('#category-img-tag').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#image").change(function() {
    readURL(this);
});
</script>
@endsection