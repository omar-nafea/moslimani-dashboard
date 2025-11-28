<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>طلب #<?php echo e($order->id); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.4;
            padding: 15px;
            direction: rtl;
            font-size: 11px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 10px;
        }
        
        .header-title {
            display: inline-block;
            vertical-align: middle;
        }
        
        .header img {
            width: 120px;
            height: 120px;
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
        }
        
        .header h1 {
            color: #22c55e;
            font-size: 20px;
            margin: 0;
            display: inline-block;
            vertical-align: middle;
        }
        
        .header p {
            color: #666;
            font-size: 10px;
            margin-top: 3px;
        }
        
        .section {
            margin-bottom: 16px;
        }
        
        .section-title {
            color: #22c55e;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 6px;
            border-bottom: 1px solid #22c55e;
            padding-bottom: 3px;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }
        
        .info-col {
            display: table-cell;
            width: 50%;
            padding: 6px 5px;
            line-height: 1.6;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            font-size: 10px;
        }
        
        .info-value {
            color: #333;
            font-size: 11px;
        }
        
        .mg-y {
            margin-top: 8px;
            margin-bottom: 8px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        
        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-canceled {
            background: #fee2e2;
            color: #991b1b;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        thead {
            background: #22c55e;
            color: white;
        }
        
        th {
            padding: 6px 4px;
            text-align: right;
            font-weight: bold;
            font-size: 10px;
        }
        
        td {
            padding: 5px 4px;
            border-bottom: 1px solid #e5e7eb;
            text-align: right;
            font-size: 10px;
        }
        
        .totals-table {
            width: 100%;
            margin-top: 8px;
        }
        
        .totals-table td {
            padding: 4px 5px;
            border: none;
        }
        
        .totals-table .label {
            text-align: right;
            font-weight: normal;
            color: #666;
        }
        
        .totals-table .value {
            text-align: left;
            font-weight: normal;
            color: #666;
        }
        
        .totals-table .final-label {
            font-weight: bold;
            color: #22c55e;
            font-size: 12px;
            border-top: 2px solid #22c55e;
            padding-top: 6px;
        }
        
        .totals-table .final-value {
            font-weight: bold;
            color: #22c55e;
            font-size: 12px;
            border-top: 2px solid #22c55e;
            padding-top: 6px;
        }
        
        .address-box {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 4px;
            font-size: 10px;
            line-height: 2;
        }
        
        .notes-box {
            background: #fffbeb;
            padding: 8px;
            border-radius: 4px;
            border-right: 3px solid #f59e0b;
            font-size: 10px;
            line-height: 1.4;
        }
        
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            color: #666;
            font-size: 9px;
        }
        
        .two-column {
            display: table;
            width: 100%;
            font-weight: bold;
            font-size: 22px;
        }
        
        .column {
            display: table-cell;
            width: 50%;
            font-weight: bold;
            font-size: 22px;
            vertical-align: top;
            padding: 0 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-title">
            <img src="<?php echo e(resource_path('views/invoices/logo-meduim.jpg')); ?>" alt="Logo" width="120" height="120">
            <h1>مزرعة مسلماني</h1>
        </div>
        <p>Moslimani Farm - Fresh & Organic Products</p>
    </div>



    <table style="width: 100%; line-height: 1.6; height: 800px; margin-bottom: 16px; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; font-weight: bold; font-size: 22px; height: 100%; vertical-align: top; padding-right: 5px;">
                <div class="section">
                    <div class="section-title mg-y">معلومات الطلب</div>
                    <div class="info-row">
                        <div class="info-col mg-y">
                            <span class="info-label">رقم الطلب: </span>
                            <span class="info-value">#<?php echo e($order->invoice_number); ?></span>
                        </div>
                        <div class="info-col mg-y">
                            <span class="info-label">التاريخ: </span>
                            <span class="info-value"><?php echo e($order->created_at->format('Y-m-d H:i')); ?></span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-col mg-y">
                            <span class="info-label">اسم العميل: </span>
                            <span class="info-value"><?php echo e($order->customer->name); ?></span>
                        </div>
                        <div class="info-col mg-y">
                            <span class="info-label">رقم الهاتف: </span>
                            <span class="info-value"><?php echo e($order->phone); ?></span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-col mg-y">
                            <span class="info-label">الحالة: </span>
                            <span class="status-badge status-<?php echo e($order->status); ?>">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($order->status):
                                    case ('pending'): ?> قيد الانتظار <?php break; ?>
                                    <?php case ('completed'): ?> تم التسليم <?php break; ?>
                                    <?php case ('canceled'): ?> ملغي <?php break; ?>
                                    <?php default: ?> <?php echo e($order->status); ?>

                                <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </td>
            
            <td style="width: 50%; font-weight: bold; font-size: 22px; vertical-align: top; padding-left: 5px;">
                <div class="section">
                    <div class="section-title mg-y">عنوان التوصيل</div>
                    <div class="address-box">
                        <div class="mg-y"><strong>المدينة:</strong> <?php echo e($order->address_city); ?></div>
                        <div class="mg-y"><strong>الشارع:</strong> <?php echo e($order->address_street); ?></div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->address_building): ?>
                            <div class="mg-y"><strong>العمارة/الشقة:</strong> <?php echo e($order->address_building); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    
                   
                </div>
            </td>
        </tr>
    </table>
     <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->notes): ?>
         <div class="section">
                <div style="margin-top: 10px;">
                    <div class="section-title">ملاحظات</div>
                    <div class="notes-box">
                        <?php echo e($order->notes); ?>

                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="section">
        <div class="section-title">المنتجات المطلوبة</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">المنتج</th>
                    <th style="width: 17%;">السعر</th>
                    <th style="width: 16%;">الكمية</th>
                    <th style="width: 17%;">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($item->name); ?></td>
                    <td><?php echo e(number_format($item->price, 0)); ?> ج</td>
                    <td><?php echo e($item->quantity); ?></td>
                    <td><?php echo e(number_format($item->total, 0)); ?> ج</td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="section">
        <table class="totals-table">
            <tr>
                <td class="label">المجموع الفرعي:</td>
                <td class="value"><?php echo e(number_format($order->subtotal, 0)); ?> جنيه</td>
            </tr>
            <tr>
                <td class="label">تكلفة الشحن:</td>
                <td class="value"><?php echo e(number_format($order->shipping_cost, 0)); ?> جنيه</td>
            </tr>
            <tr>
                <td class="final-label">الإجمالي النهائي:</td>
                <td class="final-value"><?php echo e(number_format($order->total, 0)); ?> جنيه</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>شكراً لتعاملكم معنا! | للاستفسارات كلم: 01557285489</p>
        <p>تم إنشاء هذا المستند في <?php echo e(now()->format('Y-m-d H:i:s')); ?></p>
    </div>
</body>
</html>
<?php /**PATH /home/omarnafea/projects/moslimani-platform/Dashboard/resources/views/invoices/order-mpdf.blade.php ENDPATH**/ ?>