<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard">
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <div style="background:#fff;border-radius:8px;padding:10px 14px;display:flex;align-items:center;min-width:180px;box-shadow:0 1px 0 rgba(0,0,0,0.06);">
            <div style="width:44px;height:44px;border-radius:6px;background:#e6f7f1;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                <i class="fas fa-dollar-sign" style="color:#1b805a"></i>
            </div>
            <div>
                <div style="font-size:18px;font-weight:700"><?php echo e(number_format($totalRevenue,0,',','.')); ?> đ</div>
                <div style="font-size:12px;color:#666;">Doanh thu</div>
            </div>
        </div>

        <div style="background:#fff;border-radius:8px;padding:10px 14px;display:flex;align-items:center;min-width:140px;box-shadow:0 1px 0 rgba(0,0,0,0.06);">
            <div style="width:44px;height:44px;border-radius:6px;background:#eaf6f0;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                <i class="fas fa-shopping-cart" style="color:#2f9f6d"></i>
            </div>
            <div>
                <div style="font-size:18px;font-weight:700"><?php echo e($totalOrders); ?></div>
                <div style="font-size:12px;color:#666;">Đơn hàng</div>
            </div>
        </div>

        <div style="background:#fff;border-radius:8px;padding:10px 14px;display:flex;align-items:center;min-width:140px;box-shadow:0 1px 0 rgba(0,0,0,0.06);">
            <div style="width:44px;height:44px;border-radius:6px;background:#fff4e6;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                <i class="fas fa-check-circle" style="color:#f0a83b"></i>
            </div>
            <div>
                <div style="font-size:18px;font-weight:700"><?php echo e($deliveredCount); ?></div>
                <div style="font-size:12px;color:#666;">Đã giao</div>
            </div>
        </div>

        <div style="background:#fff;border-radius:8px;padding:10px 14px;display:flex;align-items:center;min-width:120px;box-shadow:0 1px 0 rgba(0,0,0,0.06);">
            <div style="width:44px;height:44px;border-radius:6px;background:#f3ecff;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                <i class="fas fa-user-plus" style="color:#7b4ce6"></i>
            </div>
            <div>
                <div style="font-size:18px;font-weight:700"><?php echo e($newCustomers); ?></div>
                <div style="font-size:12px;color:#666;">Khách mới</div>
            </div>
        </div>
    </div>

    <div class="charts-row" style="margin-top:18px; display:flex; gap:18px; align-items:stretch;">
        <div class="chart-panel" style="flex:1; background:#fff; padding:18px; border-radius:6px;">
            <label style="font-weight:700">Doanh thu (tháng) (VND)</label>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                <label style="margin:0;font-weight:600">Năm:</label>
                <select id="selectYear" style="padding:6px;border-radius:4px;border:1px solid #ddd;">
                </select>
            </div>
            <canvas id="revenueChart" width="600" height="260"></canvas>
        </div>
        <div class="chart-panel" style="width:420px; background:#fff; padding:18px; border-radius:6px;">
            <label style="font-weight:700">Sản phẩm</label>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                <label style="margin:0;font-weight:600">Tháng:</label>
                <select id="selectMonth" style="padding:6px;border-radius:4px;border:1px solid #ddd;">
                    <option value="1">Tháng 1</option>
                    <option value="2">Tháng 2</option>
                    <option value="3">Tháng 3</option>
                    <option value="4">Tháng 4</option>
                    <option value="5">Tháng 5</option>
                    <option value="6" selected>Tháng 6</option>
                    <option value="7">Tháng 7</option>
                    <option value="8">Tháng 8</option>
                    <option value="9">Tháng 9</option>
                    <option value="10">Tháng 10</option>
                    <option value="11">Tháng 11</option>
                    <option value="12">Tháng 12</option>
                </select>
            </div>
            <canvas id="productChart" width="400" height="260"></canvas>
        </div>
    </div>

    <div class="card" style="margin-top:18px; background:#fff; padding:12px; border-radius:6px;">
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
            <?php
                $totalRev = $topProducts->sum('revenue') ?: 1;
            ?>
            <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($p->name); ?></td>
                    <td><?php echo e($p->qty); ?></td>
                    <td><?php echo e(number_format($p->revenue,0,',','.')); ?> đ</td>
                    <td><?php echo e(number_format(($p->revenue / $totalRev) * 100,2)); ?>%</td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:700">TỔNG CỘNG</td>
                    <td><?php echo e($topProducts->sum('qty')); ?></td>
                    <td><?php echo e(number_format($topProducts->sum('revenue'),0,',','.')); ?> đ</td>
                    <td>100.00%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('js/chart.min.js')); ?>"></script>
    <script>
        if (!window._dashboardChartsInitialized) {
        (function(){
            // Inject monthly data safely
            const monthly = <?php echo json_encode($monthly, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP); ?>;
            const months = Object.keys(monthly);
            const revenues = Object.values(monthly).map(v => Number(v));

            const ctx = document.getElementById('revenueChart').getContext('2d');
            window._revenueChart = window._revenueChart || new Chart(ctx, {
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

            // product pie data
            const prodNames = <?php echo json_encode($topProducts->pluck('name'), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP); ?>;
            const prodQty = <?php echo json_encode($topProducts->pluck('qty'), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP); ?>;
            const ctx2 = document.getElementById('productChart').getContext('2d');
            window._productChart = new Chart(ctx2, {
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
            // populate year select
            const selectYear = document.getElementById('selectYear');
            const currentYear = new Date().getFullYear();
            for (let y = currentYear; y >= currentYear-5; y--) {
                const opt = document.createElement('option'); opt.value = y; opt.text = y;
                if (y == currentYear) opt.selected = true;
                selectYear.appendChild(opt);
            }
            selectYear.addEventListener('change', async function(){
                await loadMonthlyForYear(this.value, window._revenueChart || null);
            });

            // populate month select listener
            document.getElementById('selectMonth').addEventListener('change', async function(){
                const year = document.getElementById('selectYear').value || currentYear;
                await loadProductBreakdown(year, this.value);
            });
            // expose revenue chart
            // remove duplicate revenue chart initialization (already created above)
        })();
        window._dashboardChartsInitialized = true;
    }
    </script>
    <script>
    async function loadMonthlyForYear(year, chart) {
        try {
            const token = localStorage.getItem('api_token');
            let res;
            if (token) {
                res = await fetch(`/api/admin/reports/monthly?year=${year}`, { headers: { 'Authorization': `Bearer ${token}` } });
            } else {
                res = await fetch(`/admin/reports/monthly?year=${year}`, { credentials: 'same-origin' });
            }
            const payload = await res.json();
            console.log('monthly payload:', payload);
            if (!payload.success) return;
            const monthly = payload.data.monthly || {};
            const labels = Object.keys(monthly);
            const data = Object.values(monthly).map(v => Number(v));
            if (chart) {
                chart.data.labels = labels;
                chart.data.datasets[0].data = data;
                chart.update();
            } else if (window._revenueChart) {
                window._revenueChart.data.labels = labels;
                window._revenueChart.data.datasets[0].data = data;
                window._revenueChart.update();
            }
        } catch (e) {
            console.error('Error loading monthly for year', e);
        }
    }

    async function loadProductBreakdown(year, month) {
        try {
            const token = localStorage.getItem('api_token');
            let res;
            if (token) {
                res = await fetch(`/api/admin/reports/products?year=${year}&month=${month}`, { headers: { 'Authorization': `Bearer ${token}` } });
            } else {
                res = await fetch(`/admin/reports/products?year=${year}&month=${month}`, { credentials: 'same-origin' });
            }
            const payload = await res.json();
            console.log('product breakdown payload:', payload);
            if (!payload.success) return;
            const names = payload.data.names || [];
            const qtys = payload.data.qtys || [];
            if (window._productChart) {
                window._productChart.data.labels = names;
                window._productChart.data.datasets[0].data = qtys;
                window._productChart.update();
            }
        } catch (e) {
            console.error('Error loading product breakdown', e);
        }
    }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Hoc_tap\Lap_Trinh_PHP\xampp\htdocs\Web-Ban-Banh-Kem-PHP\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>