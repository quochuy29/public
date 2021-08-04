@extends('admin.layouts.main')
@section('content')
<h1 style="text-align: center;">Thêm sản phẩm</h1>
<form action="" method="post" enctype="multipart/form-data" style="width: 700px;margin:auto;height:auto" files=true>
    @csrf
    <div class="form-group">
        <label>Tên sản phẩm</label>
        <input type="text" name="name" id="name" class="form-control" />
        <span style="color:red;" id="errorName"></span>
        @error('name')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>
    <div class="form-row">
        <div class="col">
            <label>Danh mục</label>
            <select class="custom-select" name="cate_id" id="cate_id">
                <option value="">Chọn danh mục</option>
                @foreach($cate as $c)
                <option value="{{$c->id}}">{{$c->name}}</option>
                @endforeach
            </select>
            <span style="color:red;" id="errorCate"></span>
            @error('cate_id')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="col">
            <label>Trạng thái</label>
            <select class="custom-select" name="status" id="status">
                <option value="">Chọn trạng thái</option>
                <option value="0">Hết hàng</option>
                <option value="1">Còn hàng</option>
                <option value="3">Sắp ra mắt</option>
            </select>
            <span style="color:red;" id="errorStatus"></span>
            @error('status')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <label>Mã giảm giá</label>
            <input type="text" name="code_sale" id="code_sale" class="form-control" />
            <span style="color:red;" id="errorCode"></span>
            @error('code_sale')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="col">
            <label>Số lượng</label>
            <input type="number" name="amount" id="amount" class="form-control" />
            <span style="color:red;" id="errorNum"></span>
            @error('amount')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
    </div>
    <label for="">Thẻ tag</label>
    @foreach($tag as $t)
    <div class="form-check" style="margin-left:5px;">
        <input type="checkbox" name="nameTag[]" id="nameTag" class="form-check-input" value="{{$t->id}}">
        <label class="form-check-label" for="flexCheckDefault">
            {{$t->name}}
        </label>
        @error('nameTag')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>
    @endforeach
    <span style="color:red;" id="errorTag"></span>
    <div class="form-group">
        <label>Giá sản phẩm</label>
        <input type="number" name="price" id="price" class="form-control" />
        <span style="color:red;" id="errorPrice"></span>
        @error('price')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>
    <div class="form-group col-md-6" id="image-show">
        <img id="images" width="70" alt="">
    </div>
    <div class="form-group">
        <label for="">Image</label>
        <input type="file" name="image" id="image" class="form-control" onchange="loadFile(event)">
        <span style="color:red;" id="errorImage"></span>
        @error('image')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>
    <div class="form-group">
        <label>Chi tiết</label>
        <textarea class="form-control" id="detail" name="detail"></textarea>
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
function loadFile(event) {
    var reader = new FileReader();
    var output = document.getElementById('images');
    reader.onload = function() {
        output.src = reader.result;
    };
    if (event.target.files[0] == undefined) {
        output.src = "";
    } else {
        reader.readAsDataURL(event.target.files[0]);
    }

};
$(document).ready(function() {
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
        var img = $('#image').val();
        var imgNew = img.replace(/^C:\\fakepath\\/i, "C:\\xampp\\tmp\\");
        var tag = [];
        $(':checkbox:checked').each(function(i) {
            tag[i] = $(this).val();
        });
        var formData = new FormData($('form')[0]);
        formData.append('_token', '{{ csrf_token() }}')
        formData.append('name', $('#name').val())
        formData.append('cate_id', $('#cate_id').val())
        formData.append('status', $('#status').val())
        formData.append('image', imgNew)
        // .replace(/C:\\fakepath\\/i, '')
        formData.append('price', $('#price').val())
        formData.append('code_sale', $('#code_sale').val())
        formData.append('amount', $('#amount').val())
        formData.append('detail', $('#detail').val())
        formData.append('tag', tag)

        $.ajax({
            url: "{{ route('product.saveAdd') }}",
            type: 'POST',
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                console.log(data);
                if (data.success) {
                    window.location.href = "{{route('product.list',['cate'=>$cate])}}";
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
                            case 'tag':
                                $("#errorTag").html(value);
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