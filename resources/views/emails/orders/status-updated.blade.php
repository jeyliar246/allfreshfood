<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>Order Status Updated</title>
<style>
  body {
    margin: 0;
    padding: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  
  .email-container {
    max-width: 600px;
    width: 100%;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
  }
  
  .header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px 30px;
    text-align: center;
    position: relative;
  }
  
  .header::after {
    content: '';
    position: absolute;
    bottom: -20px;
    left: 0;
    right: 0;
    height: 40px;
    background: #ffffff;
    border-radius: 50% 50% 0 0 / 100% 100% 0 0;
  }
  
  .status-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.95);
    padding: 12px 24px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    color: #667eea;
    margin-top: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }
  
  h1 {
    color: #ffffff;
    margin: 0 0 10px 0;
    font-size: 28px;
    font-weight: 700;
  }
  
  .content {
    padding: 40px 30px;
  }
  
  .greeting {
    font-size: 18px;
    color: #1a1a1a;
    margin-bottom: 20px;
  }
  
  .status-change {
    background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
    padding: 24px;
    border-radius: 12px;
    margin: 25px 0;
    border-left: 4px solid #667eea;
  }
  
  .status-change p {
    margin: 0;
    color: #4a5568;
    font-size: 15px;
    line-height: 1.6;
  }
  
  .status-arrow {
    display: inline-block;
    margin: 0 10px;
    color: #667eea;
    font-size: 18px;
  }
  
  .old-status {
    text-decoration: line-through;
    opacity: 0.6;
  }
  
  .new-status {
    color: #667eea;
    font-weight: 700;
  }
  
  h2 {
    color: #1a1a1a;
    font-size: 20px;
    margin: 30px 0 20px 0;
    font-weight: 600;
  }
  
  .order-details {
    background: #ffffff;
    border: 2px solid #e8ecf1;
    border-radius: 12px;
    padding: 0;
    list-style: none;
    margin: 0;
    overflow: hidden;
  }
  
  .order-details li {
    padding: 18px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e8ecf1;
    font-size: 15px;
  }
  
  .order-details li:last-child {
    border-bottom: none;
  }
  
  .detail-label {
    color: #718096;
    font-weight: 500;
  }
  
  .detail-value {
    color: #1a1a1a;
    font-weight: 600;
  }
  
  .total-row {
    background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
  }
  
  .footer {
    padding: 30px;
    text-align: center;
    background: #f9fafb;
    color: #718096;
    font-size: 14px;
    line-height: 1.6;
  }
  
  .footer p {
    margin: 8px 0;
  }
  
  .cta-text {
    color: #667eea;
    font-weight: 600;
  }
  
  @media (max-width: 640px) {
    .email-container {
      margin: 10px;
    }
    
    .header {
      padding: 30px 20px;
    }
    
    h1 {
      font-size: 24px;
    }
    
    .content {
      padding: 30px 20px;
    }
    
    .order-details li {
      flex-direction: column;
      align-items: flex-start;
      gap: 5px;
    }
  }
</style>
</head>
<body>
  <div class="email-container">
    <div class="header">
      <h1>Order Status Updated</h1>
      <div class="status-badge">Order #{{ $order->id }}</div>
    </div>
    
    <div class="content">
      <p class="greeting">Hi {{ $order->user->name ?? 'there' }},</p>
      
      <div class="status-change">
        <p>Your order status has been updated:</p>
        <p style="margin-top: 12px; font-size: 16px;">
          <span class="old-status">{{ ucfirst($oldStatus) }}</span>
          <span class="status-arrow">→</span>
          <span class="new-status">{{ ucfirst($order->status) }}</span>
        </p>
      </div>
      
      <h2>Order Summary</h2>
      <ul class="order-details">
        <li class="total-row">
          <span class="detail-label">Total Amount</span>
          <span class="detail-value">£{{ number_format($order->total, 2) }}</span>
        </li>
        <li>
          <span class="detail-label">Vendor</span>
          <span class="detail-value">{{ $order->vendor->name ?? 'N/A' }}</span>
        </li>
        <li>
          <span class="detail-label">Order Date</span>
          <span class="detail-value">{{ $order->created_at->format('M d, Y H:i') }}</span>
        </li>
      </ul>
    </div>
    
    <div class="footer">
      <p><span class="cta-text">Have questions?</span> Just reply to this email and we'll be happy to help.</p>
      <p style="margin-top: 20px;">Thanks,<br/><strong>Customer Support Team</strong></p>
    </div>
  </div>
</body>
</html>