@extends('admin.layouts.main')
@section('content')
<h1 style="text-align: center;">Sửa sản phẩm</h1>
<form action="javascript:void(0);" method="post" enctype="multipart/form-data" style="width: 700px;margin:auto;">
    @csrf
    <div class="form-group">
        <label>Tên sản phẩm</label>
        <input type="text" name="name" id="name" value="{{$edit->name}}" class="form-control" />
        <span style="color:red;" id="errorName"></span>
    </div>
    <div class="form-row">
        <div class="col">
            <label>Danh mục</label>
            <select class="custom-select" name="cate_id" id="cate_id">
                <option value="">Chọn danh mục</option>
                @foreach($category as $cate)
                <option {{ ($cate->id == $edit->cate_id) ? 'selected="selected"' : '' }} value="{{$cate->id}}">
                    {{$cate->name}}
                </option>
                @endforeach
            </select>
            <span style="color:red;" id="errorCate"></span>
        </div>
        <div class="col">
            <label>Trạng thái</label>
            <select class="custom-select" name="status" id="status">
                <option value="">Chọn trạng thái</option>
                <option {{ ($edit->status == 0) ? 'selected="selected"' : '' }}value="0">Hết hàng</option>
                <option {{ ($edit->status == 1) ? 'selected="selected"' : '' }} value="1">Còn hàng</option>
                <option {{ ($edit->status == 3) ? 'selected="selected"' : '' }} value="3">Sắp ra mắt</option>
            </select>
            <span style="color:red;" id="errorStatus"></span>
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <label>Mã giảm giá</label>
            <input type="text" name="code_sale" id="code_sale" value="{{$edit->code_sale}}" class="form-control" />
            <span style="color:red;" id="errorCode"></span>
        </div>
        <div class="col">
            <label>Số lượng</label>
            <input type="number" name="amount" id="amount" value="{{$edit->amount}}" class="form-control" />
            <span style="color:red;" id="errorNum"></span>
        </div>
    </div>
    <div class="form-group">
        <label>Giá sản phẩm</label>
        <input type="number" name="price" id="price" value="{{$edit->price}}" class="form-control" />
        <span style="color:red;" id="errorPrice"></span>
    </div>
    <div class="form-group col-md-6" id="image-show">
        <img src="{{asset('storage/'.$edit->image)}}" id="category-img-tag" width="200px" />
    </div>
    <div class="form-group">
        <label for="">Image</label>
        <input type="file" name="image" id="image" class="form-control">
        <span style="color:red;" id="errorImage"></span>
    </div>
    <div class="form-group col-md-6" id="images-show">
        <span class="pip">
            @foreach($edit->album as $img)
            <img width="70" src="{{asset('storage/images/'.$img)}}" alt="">
            @endforeach
        </span>
    </div>
    <div class="form-group">
        <label for="">Album</label>
        <input type="file" name="album[]" id="album" class="form-control" multiple="multiple">
        <span style="color:red;" id="errorAlbum"></span>
    </div>
    <div class="form-group">
        <label>Chi tiết</label>
        <textarea class="form-control" id="detail" name="detail">{{$edit->detail}}</textarea>
    </div>
    <div class="text-center">
        <button class="btn btn-sm btn-success" type="submit">Tạo mới</button>
        <a href="" class="btn btn-sm btn-warning">Hủy</a>
    </div>
</form>
@endsection
@section('pagejs')
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script>
$(document).ready(function() {
    $("#album").on("change", function(e) {
        var files = e.target.files,
            filesLength = files.length;
        for (var i = 0; i < filesLength; i++) {
            var f = files[i]
            var fileReader = new FileReader();
            fileReader.onload = (function(e) {
                var file = e.target;
                $("<img width=\"70\" class=\"imageThumb\" src=\"" + e.target.result +
                    "\" title=\"" +
                    file.name + "\"/>").insertAfter(".pip");
                $(".remove").click(function() {
                    $(this).parent(".pip").remove();
                    $('#album').val("");
                });
            });
            fileReader.readAsDataURL(f);
        }
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#category-img-tag').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#image").change(function() {
        readURL(this);
    });

    tinymce.init({
        selector: 'textarea', // change this value according to your HTML
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        },
        width: 700,
        height: 300,
        plugins: [
            'advlist autolink link image lists charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks code fullscreen insertdatetime media nonbreaking',
            'table emoticons template paste help'
        ],
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | link image | print preview media fullpage | ' +
            'forecolor backcolor emoticons | help',
        menu: {
            favs: {
                title: 'My Favorites',
                items: 'code visualaid | searchreplace | emoticons'
            }
        },
        menubar: 'favs file edit view insert format tools table',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
        relative_urls: false,
        images_upload_handler: function(blobInfo, success, failure) {
            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', "{{route('product.upload')}}");
            var token = '{{csrf_token()}}';
            xhr.setRequestHeader("X-CSRF-Token", token);
            xhr.onload = function() {
                var json;

                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                json = JSON.parse(xhr.responseText);

                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                success(json.location);
            };
            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        }
    });

    $(".btn-success").click(function(e) {
        e.preventDefault();
        var formData = new FormData($('form')[0]);

        $.ajax({
            url: "{{ route('product.saveEdit',['id'=>$edit->id]) }}",
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                if (data.success) {
                    window.location.href =
                        "{{route('product.list',['message'=>'Sửa sản phẩm thành công','cate'=>$category])}}";
                } else {
                    $.each(data.error, function(key, value) {
                        switch (key) {
                            case 'name':
                                $("#errorName").html(value);
                                break;
                            case 'image':
                                $("#errorImage").html(value);
                                break;
                            case 'code_sale':
                                $("#errorCode").html(value);
                                break;
                            case 'price':
                                $("#errorPrice").html(value);
                                break;
                            case 'amount':
                                $("#errorNum").html(value);
                                break;
                            case 'status':
                                $("#errorStatus").html(value);
                                break;
                            case 'cate_id':
                                $("#errorCate").html(value);
                                break;
                            case 'album':
                                $("#errorAlbum").html(value);
                                break;
                        }
                    });
                }
            },
        });
    });
});
</script>
@endsection