{{--/**
 * Created by PhpStorm.
 * User: pirat
 * Date: 2020-04-07
 * Time: 12:13 PM
 */--}}
@extends('layouts.app')
@section('title', 'Products')
@section('products', 'active')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            Products
            <a href="#" class="btn btn-primary float-right">Add product</a>
        </div>
        <div class="card-body">
            <h5 class="card-title"></h5>
            <form action="{{route('products.update', $product->id)}}" method="POST" role="form">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label for="">Product code:</label>
                    <input type="text" name="code" value="{{$product->code}}" id="code" class="form-control">
                    @if($errors->has('code'))
                        <span class="text-danger">{{$errors->first('code')}}</span>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="name">Product Name:</label>
                    <input type="text" name="name" id="name" value="{{$product->name}}" class="form-control name">
                    @if($errors->has('name'))
                        <span class="text-danger">{{$errors->first('name')}}</span>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="price">Product Price:</label>
                    <input type="text" name="price" id="price" value="{{$product->price}}" class="form-control price">
                    @if($errors->has('price'))
                        <span class="text-danger">{{$errors->first('price')}}</span>
                    @endif
                </div>
                <div class="col-md-6 mt-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')

@endsection