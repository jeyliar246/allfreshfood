<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Order Received</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #1a1a1a;
            line-height: 1.6;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 40px 20px;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 40px 32px;
            text-align: center;
            color: white;
            position: relative;
        }

        .alert-badge {
            width: 72px;
            height: 72px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            backdrop-filter: blur(10px);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(255, 255, 255, 0);
            }
        }

        .alert-badge svg {
            width: 40px;
            height: 40px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 16px;
            opacity: 0.95;
        }

        .urgent-banner {
            background: #ff6b6b;
            color: white;
            padding: 12px 32px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .content {
            padding: 32px;
        }

        .greeting {
            margin-bottom: 24px;
        }

        .greeting h2 {
            font-size: 22px;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .greeting p {
            color: #666;
            font-size: 15px;
        }

        .order-card {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe8f0 100%);
            border: 2px solid #ffd1dc;
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }

        .order-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(180deg, #f093fb 0%, #f5576c 100%);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 2px solid #ffd1dc;
        }

        .order-number {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .new-badge {
            background: #ff6b6b;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(20px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .items-section {
            margin: 20px 0;
        }

        .items-header {
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 700;
            color: #f5576c;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .items-list {
            list-style: none;
            background: white;
            border-radius: 8px;
            padding: 16px;
        }

        .items-list li {
            padding: 12px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ffe8f0;
        }

        .items-list li:last-child {
            border-bottom: none;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #1a1a1a;
            font-size: 15px;
        }

        .item-quantity {
            color: #666;
            font-size: 13px;
            margin-left: 8px;
            background: #fff5f5;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .item-price {
            font-weight: 700;
            color: #f5576c;
            font-size: 16px;
        }

        .order-total {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border: 2px dashed #ffd1dc;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 15px;
            color: #666;
        }

        .total-row.grand-total {
            border-top: 2px solid #ffd1dc;
            margin-top: 12px;
            padding-top: 16px;
            font-size: 22px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .total-row.grand-total .amount {
            color: #f5576c;
        }

        .delivery-section {
            background: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 16px;
            margin-top: 20px;
            border-radius: 6px;
        }

        .delivery-section .icon-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .delivery-icon {
            background: #ffc107;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .delivery-icon svg {
            width: 18px;
            height: 18px;
        }

        .delivery-content {
            flex: 1;
        }

        .delivery-label {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 700;
            color: #f57f17;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .delivery-address {
            color: #5d4037;
            font-size: 15px;
            font-weight: 500;
        }

        .action-section {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 24px 32px;
            text-align: center;
        }

        .action-section p {
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .action-note {
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
        }

        .footer {
            background: #f8f9fa;
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .footer .signature {
            font-size: 15px;
            color: #666;
        }

        .footer .company {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            margin-top: 4px;
            font-size: 16px;
        }

        @media (max-width: 640px) {
            body {
                padding: 20px 10px;
            }

            .content {
                padding: 24px 20px;
            }

            .header {
                padding: 32px 20px;
            }

            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .order-number {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="header">
            <div class="alert-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <h1>New Order Alert!</h1>
            <p>Action required</p>
        </div>

        <div class="urgent-banner">
            ⚡ Immediate attention needed
        </div>

        <div class="content">
            <div class="greeting">
                <h2>Hello {{ $vendor->name }},</h2>
                <p>You have received a new order that requires processing.</p>
            </div>

            <div class="order-card">
                <div class="order-header">
                    <span class="order-number">Order #{{ $order->id }}</span>
                    <span class="new-badge">New</span>
                </div>

                <div class="items-section">
                    <div class="items-header">Order Items</div>
                    <ul class="items-list">
                        @foreach ($order->items ?? [] as $item)
                            <li>
                                <div class="item-details">
                                    <span class="item-name">{{ optional($item->product)->name ?? 'Item' }}</span>
                                    <span class="item-quantity">× {{ $item->quantity }}</span>
                                </div>
                                <span class="item-price">£{{ number_format($item->price, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="order-total">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>£{{ number_format($order->total - $order->delivery_fee, 2) }}</span>
                    </div>
                    <div class="total-row">
                        <span>Delivery Fee</span>
                        <span>£{{ number_format($order->delivery_fee, 2) }}</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total</span>
                        <span class="amount">£{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                <div class="delivery-section">
                    <div class="icon-row">
                        <div class="delivery-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <div class="delivery-content">
                            <div class="delivery-label">Delivery Address</div>
                            <div class="delivery-address">{{ $order->delivery_address }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-section">
            <p>Please process this order promptly</p>
            <p class="action-note">Customers are waiting for their items</p>
        </div>

        <div class="footer">
            <div class="signature">
                Regards,<br />
                <div class="company">All Foods</div>
            </div>
        </div>
    </div>
</body>

</html>
