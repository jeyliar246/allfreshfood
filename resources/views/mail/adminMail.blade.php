<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Order Confirmation</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 32px;
            text-align: center;
            color: white;
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

        .checkmark {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            backdrop-filter: blur(10px);
        }

        .checkmark svg {
            width: 36px;
            height: 36px;
        }

        .content {
            padding: 32px;
        }

        .intro {
            text-align: center;
            margin-bottom: 32px;
        }

        .intro h2 {
            font-size: 24px;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .intro p {
            color: #666;
            font-size: 15px;
        }

        .order-card {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            border: 1px solid #e5e9f7;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 16px;
            border-bottom: 2px solid #e5e9f7;
            margin-bottom: 16px;
        }

        .order-number {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
        }

        .vendor-name {
            font-size: 14px;
            color: #666;
            background: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .items-list {
            list-style: none;
            margin: 16px 0;
        }

        .items-list li {
            padding: 12px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e5e9f7;
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
        }

        .item-price {
            font-weight: 600;
            color: #1a1a1a;
            font-size: 15px;
        }

        .order-summary {
            background: white;
            padding: 16px;
            border-radius: 8px;
            margin-top: 16px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }

        .summary-row.total {
            border-top: 2px solid #e5e9f7;
            margin-top: 8px;
            padding-top: 12px;
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
        }

        .delivery-info {
            background: #fef9e7;
            border-left: 4px solid #f7c948;
            padding: 12px 16px;
            margin-top: 16px;
            border-radius: 4px;
        }

        .delivery-info .label {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
            color: #997404;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .delivery-info .address {
            color: #5d4e00;
            font-size: 14px;
        }

        .footer {
            background: #f8f9fa;
            padding: 32px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .footer p {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .footer .signature {
            margin-top: 20px;
            font-size: 16px;
            color: #1a1a1a;
            font-weight: 600;
        }

        .footer .company {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
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
                gap: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="header">
            <div class="checkmark">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h1>New Order Confirmation!</h1>
            <p>New order placed</p>
        </div>

        <div class="content">
            <div class="intro">
                <h2>Order from, {{ $user->name }}!</h2>
                <p>You've received a new order.</p>
            </div>

            @foreach ($orders ?? [] as $order)
                <div class="order-card">
                    <div class="order-header">
                        <span class="order-number">Order #{{ $order->id }}</span>
                        <span class="vendor-name">{{ optional($order->vendor)->name ?? 'Vendor' }}</span>
                    </div>

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

                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>£{{ number_format($order->total - $order->delivery_fee, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Delivery</span>
                            <span>£{{ number_format($order->delivery_fee, 2) }}</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span>£{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>

                    <div class="delivery-info">
                        <div class="label">Delivery Address</div>
                        <div class="address">{{ $order->delivery_address }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="footer">
            <p>If you have any questions, just reply to this email.</p>
            <p>We're here to help!</p>
            <div class="signature">
                Regards,<br />
                <span class="company">All Foods</span>
            </div>
        </div>
    </div>
</body>

</html>




























