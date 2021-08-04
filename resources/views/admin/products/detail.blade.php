<!DOCTYPE html>
<html lang="en">
@extends('admin.layouts.main')
@section('content')
<main class="container-fluid" style="height: 600px;">
    <div class="container">
        <div class="sp" style="display: grid;
    grid-template-columns: 1fr 1fr;">
            <div class=" image">
                <img width="300" src="{{asset('storage/'.$detail->image)}}" alt="">
            </div>
            <div class="info">
                <h2>Tên sản phẩm : {{$detail->name}}</h2>
                <p>Giá : {{number_format($detail->price) . " " . 'VNĐ'}}</p>
                <p>Tên loại : {{$detail->categories->name}}</p>
                <p>Mã giảm giá : {{$detail->code_sale}}</p>
                <p>Trạng thái : @if($detail->status == 1)
                    Còn hàng
                    @elseif($detail->status == 0)
                    Hết hàng
                    @else
                    Sắp ra mắt
                    @endif
                </p>
                <p>số lượng : {{$detail->amount}}</p>
                <p>
                    @isset($detail->tags)
                    Tag :
                    @foreach($detail->tags as $t)
                    <span style="border: solid 1px #ccc; margin-right: 5px;">{{$t->name}}</span>
                    @endforeach
                    @endisset
                </p>
                <p>Chi tiết sản phẩm : {!! $detail->detail !!}</p>
            </div>
        </div>
        <div class="mission" style="float:right;">
            <a href="{{route('product.edit',['id'=>$detail->id])}}" class="btn btn-info btn-sm">
                Sửa
            </a>
            <a onclick="return confirm('bạn có chắc muốn xóa tài khoản này?')"
                href="{{route('product.delete',['id'=>$detail->id])}}" class="btn btn-danger btn-sm">
                Xóa
            </a>
        </div>
    </div>
</main>
@endsection