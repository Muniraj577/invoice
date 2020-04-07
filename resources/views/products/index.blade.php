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
            <a href="{{route('products.create')}}" class="btn btn-primary float-right">Add product</a>
        </div>
        <div class="card-body">
            <h5 class="card-title">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </h5>

            <table id="products" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <td>SN</td>
                    <td>Code</td>
                    <td>Name</td>
                    <td>Price</td>
                    <td>Action</td>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{++$id}}</td>
                    <td>{{ $product->code }}</td>
                    <td>{{$product->name}}</td>
                    <td>{{$product->price}}</td>
                    <td>
                        <a href="{{route('products.edit', $product->id)}}" class="btn btn-primary btn-sm">Edit</a>
                    </td>
                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('#products').DataTable();
        });
    </script>
@endsection