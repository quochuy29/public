@extends('admin.layouts.main')
@section('content')
<h1>Quản trị sản phẩm</h1>
<span style="background-color: #9bf886; color: white; height:30px;width:auto;border-radius:10px;">@isset($message)
    @endisset</span>
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Trạng thái :</strong></label>
                        <select id="status" class="form-control">
                            <option value="">--Select status--</option>
                            <option value="1">Còn hàng</option>
                            <option value="0">Hết hàng</option>
                            <option value="0">Sắp ra mắt</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Category :</strong></label>
                        <select id="cate" class="form-control">
                            <option value="">--Select category--</option>
                            @foreach($category as $cate)
                            <option value="{{$cate->id}}">{{$cate->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a href="{{route('product.add')}}" class="btn btn-success btn-sm">Thêm</a>
                    </div>
                </div>
            </div>
            <table class="table table-bordered data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col">Gía sản phẩm</th>
                        <th scope="col">Thể loại</th>
                        <th scope="col">Mã giảm giá</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- @php
    $al = $products->where('id',23);
    @endphp
   -->
<!-- @foreach($products as $p)
    <td>{!! $p->detail !!}</td>
    @endforeach -->
@endsection
@section('pagejs')
<script>
$(function() {

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('product.filter') }}",
            data: function(d) {
                d.cate = $('#cate').val();
                d.status = $('#status').val(),
                    d.search = $('input[type="search"]').val()
            }
        },
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'price',
                name: 'price'
            },
            {
                data: 'cate_id',
                name: 'cate_id'
            },
            {
                data: 'code_sale',
                name: 'code_sale'
            },
            {
                data: 'amount',
                name: 'amount'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]

    });

    $('#status').change(function() {
        table.draw();
    });

    $('#cate').change(function() {
        table.draw();
    });
});
</script>
@endsection