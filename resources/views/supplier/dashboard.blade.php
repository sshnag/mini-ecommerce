@extends('adminlte::page')

@section('title', 'Supplier Dashboard')

@section('content')
<div class="container-fluid">
    <h1>Supplier Dashboard</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $productCount }}</h3>
                    <p>Products</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $orderCount }}</h3>
                    <p>Orders</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
