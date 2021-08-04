<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <title>Thêm danh mục</title>
</head>

<body>
    <h1>Thêm danh mục</h1>
    <div class="col-md-6 offset-md-3">
        <form action="javascrpit:void(0)" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="exampleInputEmail1" class="form-label">Tên loại hàng</label>
                <input type="text" class="form-control" name="name" id="name">
                <span style="color:red;" id="errorName"></span>
            </div><br>
            <div class="form-group col-md-6" id="image-show">

            </div>
            <div class="form-group">
                <label for="">Image</label>
                <input type="file" name="image" id="image" class="form-control">
                <span style="color:red;" id="errorImage"></span>
            </div><br>
            <div class="text-center">
                <button class="btn btn-sm btn-success" type="submit">Tạo mới</button>
                <a href="" class="btn btn-sm btn-warning">Hủy</a>
            </div>
        </form>
    </div>
</body>

</html>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script>
$(document).ready(function() {
    $(".btn-success").click(function(e) {
        e.preventDefault();
        var formData = new FormData($('form')[0]);

        $.ajax({
            url: "{{ route('category.saveAdd') }}",
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                if (data.success) {
                    window.location.href =
                        "{{route('category.listCate',['message'=>'Thêm danh mục thành công'])}}";
                } else {
                    $.each(data.error, function(key, value) {
                        if (key == "name") {
                            $("#errorName").html(value);
                        } else {
                            $("#errorImage").html(value);
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