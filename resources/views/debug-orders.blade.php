@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2>Debug: Order & User Info</h2>
    
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5>Current User Info</h5>
        </div>
        <div class="card-body">
            <p><strong>UserID:</strong> {{ $user->UserID }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Name:</strong> {{ $user->full_name }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5>My Orders (customer_id = {{ $user->UserID }})</h5>
        </div>
        <div class="card-body">
            @if($orders->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>OrderID</th>
                            <th>Order Code</th>
                            <th>customer_id</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->OrderID }}</td>
                            <td>{{ $order->order_code }}</td>
                            <td>{{ $order->customer_id }}</td>
                            <td>{{ $order->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-danger">No orders found for this user!</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-warning">
            <h5>Latest 5 Orders (All Users)</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>OrderID</th>
                        <th>Order Code</th>
                        <th>customer_id</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allOrders as $order)
                    <tr class="{{ $order->customer_id == $user->UserID ? 'table-success' : '' }}">
                        <td>{{ $order->OrderID }}</td>
                        <td>{{ $order->order_code }}</td>
                        <td>{{ $order->customer_id }}</td>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
