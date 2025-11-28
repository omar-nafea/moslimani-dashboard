<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>طلب #{{ $order->id }}</title>
    <style>
        @font-face {
            font-family: 'NotoNaskhArabic';
            src: url('{{ storage_path('fonts/NotoNaskhArabic-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'Amiri';
            src: url('{{ storage_path('fonts/Amiri-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'NotoNaskhArabic', 'Amiri', 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.8;
            padding: 20px;
            direction: rtl;
            text-align: right;
            font-size: 14px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #22c55e;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #22c55e;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 12px;
            font-family: 'DejaVu Sans', sans-serif;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            color: #22c55e;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 12px;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 5px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .info-table td {
            padding: 8px 10px;
            vertical-align: top;
            font-size: 14px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            width: 130px;
        }
        
        .info-value {
            color: #333;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 4px;
            font-size: 12px;
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
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .products-table thead {
            background: #22c55e;
            color: white;
        }
        
        .products-table th {
            padding: 12px 10px;
            text-align: right;
            font-weight: bold;
            font-size: 14px;
        }
        
        .products-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e7eb;
            text-align: right;
            font-size: 13px;
        }
        
        .products-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .totals-section {
            margin-top: 25px;
            border-top: 2px solid #e5e7eb;
            padding-top: 20px;
        }
        
        .totals-table {
            width: 320px;
            margin-right: auto;
            border-collapse: collapse;
        }
        
        .totals-table td {
            padding: 10px 12px;
            font-size: 14px;
        }
        
        .totals-table .label {
            text-align: right;
            color: #666;
        }
        
        .totals-table .value {
            text-align: left;
            color: #333;
            font-weight: bold;
        }
        
        .totals-table .final-row td {
            border-top: 2px solid #22c55e;
            padding-top: 15px;
            font-size: 16px;
        }
        
        .totals-table .final-row .label,
        .totals-table .final-row .value {
            color: #22c55e;
            font-weight: bold;
        }
        
        .address-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            line-height: 2;
            font-size: 14px;
        }
        
        .notes-box {
            background: #fffbeb;
            padding: 15px;
            border-radius: 6px;
            border-right: 4px solid #f59e0b;
            line-height: 1.8;
            font-size: 14px;
        }
        
        .footer {
            text-align: center;
            margin-top: 35px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #666;
            font-size: 13px;
        }
        
        .footer p {
            margin-bottom: 5px;
        }
        
        .two-columns {
            width: 100%;
        }
        
        .two-columns td {
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }
        
        .ltr {
            direction: ltr;
            text-align: right;
            font-family: 'DejaVu Sans', sans-serif;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>مزرعة مسلماني</h1>
        <p>Moslimani Farm - Fresh & Organic Products</p>
    </div>

    <!-- Order & Customer Info -->
    <table class="two-columns">
        <tr>
            <td>
                <div class="section">
                    <div class="section-title">معلومات الطلب</div>
                    <table class="info-table">
                        <tr>
                            <td class="info-label">رقم الطلب:</td>
                            <td class="info-value ltr">#{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">رقم الفاتورة:</td>
                            <td class="info-value ltr">{{ $order->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">التاريخ:</td>
                            <td class="info-value ltr">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">الحالة:</td>
                            <td class="info-value">
                                <span class="status-badge status-{{ $order->status }}">
                                    @switch($order->status)
                                        @case('pending') قيد الانتظار @break
                                        @case('completed') مكتمل @break
                                        @case('canceled') ملغي @break
                                        @default {{ $order->status }}
                                    @endswitch
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td>
                <div class="section">
                    <div class="section-title">معلومات العميل</div>
                    <table class="info-table">
                        <tr>
                            <td class="info-label">الاسم:</td>
                            <td class="info-value">{{ $order->customer->name ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">الهاتف:</td>
                            <td class="info-value ltr">{{ $order->phone }}</td>
                        </tr>
                    </table>
                    
                    <div class="section-title" style="margin-top: 15px;">عنوان التوصيل</div>
                    <div class="address-box">
                        @if($order->address_city)
                            <strong>المدينة:</strong> {{ $order->address_city }}<br>
                        @endif
                        @if($order->address_street)
                            <strong>الشارع:</strong> {{ $order->address_street }}<br>
                        @endif
                        @if($order->address_building)
                            <strong>العمارة/الشقة:</strong> {{ $order->address_building }}
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Notes -->
    @if($order->notes)
        <div class="section">
            <div class="section-title">ملاحظات</div>
            <div class="notes-box">
                {{ $order->notes }}
            </div>
        </div>
    @endif

    <!-- Products Table -->
    <div class="section">
        <div class="section-title">المنتجات المطلوبة</div>
        <table class="products-table">
            <thead>
                <tr>
                    <th style="width: 50%;">المنتج</th>
                    <th style="width: 17%;">السعر</th>
                    <th style="width: 16%;">الكمية</th>
                    <th style="width: 17%;">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="ltr">{{ number_format($item->price, 0) }} ج.م</td>
                    <td class="ltr">{{ $item->quantity }}</td>
                    <td class="ltr">{{ number_format($item->total, 0) }} ج.م</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Totals -->
    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td class="label">المجموع الفرعي:</td>
                <td class="value ltr">{{ number_format($order->subtotal, 0) }} ج.م</td>
            </tr>
            <tr>
                <td class="label">تكلفة الشحن:</td>
                <td class="value ltr">{{ number_format($order->shipping_cost, 0) }} ج.م</td>
            </tr>
            <tr class="final-row">
                <td class="label">الإجمالي النهائي:</td>
                <td class="value ltr">{{ number_format($order->total, 0) }} ج.م</td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>شكراً لتعاملكم معنا!</p>
        <p class="ltr">للاستفسارات: 01557285489</p>
        <p class="ltr" style="margin-top: 10px; font-size: 11px;">تم إنشاء هذا المستند في {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
