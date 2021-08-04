@extends('admin.layouts.main')
@section('content')
<h1>Quản trị loại hàng</h1>
<span style="background-color: #9bf886; color: white; height:30px;width:auto;border-radius:10px;">@isset($message)
    @endisset</span>
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('pagejs')
<script>
$(function() {

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('tag.filter') }}",

        columns: [{
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]

    });

});
</script>
@endsection