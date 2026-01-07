@extends('layouts.admin')

@section('page-title','Quản lý báo cáo')

@section('content')
    <div class="card mb-12">
        <h2 style="color:var(--green-medium);margin:0 0 12px 0">QUẢN LÝ BÁO CÁO</h2>

        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:18px;">
            <div class="card" style="flex:1;min-width:180px;display:flex;flex-direction:column;align-items:flex-start;gap:8px;">
                <strong>Doanh thu</strong>
                <div style="font-size:20px;font-weight:700">{{ number_format($totalRevenue,0,',','.') }} đ</div>
            </div>
            <div class="card" style="flex:1;min-width:140px;display:flex;flex-direction:column;align-items:flex-start;gap:8px;">
                <strong>Đơn hàng</strong>
                <div class="pill">{{ $totalOrders }}</div>
            </div>
            <div class="card" style="flex:1;min-width:140px;display:flex;flex-direction:column;align-items:flex-start;gap:8px;">
                <strong>Đã giao</strong>
                <div class="pill">{{ $deliveredCount }}</div>
            </div>
            <div class="card" style="flex:1;min-width:140px;display:flex;flex-direction:column;align-items:flex-start;gap:8px;">
                <strong>Khách mới</strong>
                <div class="pill">{{ $newCustomers }}</div>
            </div>
            <div style="margin-left:auto">
                <button class="btn">Xuất PDF</button>
            </div>
        </div>

        <div class="chart-row" style="margin-bottom:18px">
            <div class="chart-box">
                <label style="font-weight:700">Doanh thu (tháng)</label>
                <canvas id="revenueChart" width="600" height="260"></canvas>
            </div>
            <div class="chart-box">
                <label style="font-weight:700">Sản phẩm</label>
                <canvas id="productChart" width="400" height="260"></canvas>
            </div>
        </div>

        <div class="card">
            <h3 style="margin-top:0;color:var(--green-medium)">CHI TIẾT DOANH THU THEO SẢN PHẨM</h3>
            <table class="admin-table" style="margin-top:12px">
                <thead>
                    <tr>
                        <th>TÊN SẢN PHẨM</th>
                        <th>SỐ LƯỢNG BÁN</th>
                        <th>DOANH THU</th>
                        <th>TỶ LỆ DOANH THU</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    $totalRev = $topProducts->sum('revenue') ?: 1;
                @endphp
                @foreach($topProducts as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->qty }}</td>
                        <td>{{ number_format($p->revenue,0,',','.') }} đ</td>
                        <td>{{ number_format(($p->revenue / $totalRev) * 100,2) }}%</td>
                    </tr>
                @endforeach
                    <tr>
                        <td style="font-weight:700">TỔNG CỘNG</td>
                        <td>{{ $topProducts->sum('qty') }}</td>
                        <td>{{ number_format($topProducts->sum('revenue'),0,',','.') }} đ</td>
                        <td>100.00%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function(){
            const monthly = @json($monthly);
            const months = Object.keys(monthly);
            const revenues = Object.values(monthly).map(v => Number(v));

            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Doanh thu',
                        data: revenues,
                        backgroundColor: '#4e7a66'
                    }]
                },
                options: { responsive:true, maintainAspectRatio:false }
            });

            // product pie
            const prodNames = @json($topProducts->pluck('name'));
            const prodQty = @json($topProducts->pluck('qty'));
            const ctx2 = document.getElementById('productChart').getContext('2d');
            new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: prodNames,
                    datasets: [{
                        data: prodQty,
                        backgroundColor: ['#2f5b43','#ff8a65','#ffd54f','#4db6ac','#9575cd','#f06292','#90a4ae','#66bb6a','#ffb74d','#29b6f6']
                    }]
                },
                options: { responsive:true, maintainAspectRatio:false }
            });
        })();
    </script>

@endsection


