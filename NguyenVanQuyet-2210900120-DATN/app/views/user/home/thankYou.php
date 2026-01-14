<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cảm ơn bạn đã đặt hàng!</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Inter', system-ui, sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #e4e9fd 100%);
      color: #2d3748;
      line-height: 1.6;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .container {
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.08);
      padding: 48px 32px;
      max-width: 620px;
      width: 100%;
      text-align: center;
    }
    .icon-check {
      font-size: 80px;
      color: #22c55e;
      margin-bottom: 24px;
    }
    h1 {
      font-size: 2.5rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 16px;
    }
    .subtitle {
      font-size: 1.25rem;
      color: #64748b;
      margin-bottom: 32px;
    }
    .order-info {
      background: #f8fafc;
      border-radius: 12px;
      padding: 24px;
      margin: 32px 0;
      font-size: 1.1rem;
    }
    .order-info strong {
      color: #1e293b;
    }
    .btn {
      display: inline-block;
      background: #3b82f6;
      color: white;
      padding: 16px 36px;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 600;
      font-size: 1.1rem;
      transition: all 0.3s;
      margin: 12px;
    }
    .btn:hover {
      background: #2563eb;
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(59,130,246,0.25);
    }
    .btn-outline {
      background: transparent;
      border: 2px solid #64748b;
      color: #64748b;
    }
    .btn-outline:hover {
      background: #64748b;
      color: white;
      border-color: #64748b;
    }
    .tips {
      margin-top: 40px;
      color: #64748b;
      font-size: 0.95rem;
    }
    @media (max-width: 480px) {
      .container { padding: 32px 20px; }
      h1 { font-size: 2rem; }
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="icon-check">
      <i class="fas fa-check-circle"></i>
    </div>

    <h1>Cảm ơn bạn rất nhiều!</h1>
    <p class="subtitle">Đơn hàng của bạn đã được đặt thành công</p>

    <div class="order-info">
  <p>
    Mã đơn hàng:
    <strong>#<?= htmlspecialchars($donHang['ma_don_hang']) ?></strong>
  </p>

  <p>
    Tổng tiền:
    <strong><?= number_format($donHang['tong_tien'], 0, ',', '.') ?>₫</strong>
  </p>
  <p>
    Chúng tôi sẽ gửi email xác nhận + thông tin vận chuyển trong vài phút tới!
  </p>
</div>

    <p style="margin: 32px 0; font-size: 1.1rem; color: #475569;">
      Trong lúc chờ hàng, bạn có thể ghe thăm các sản phẩm khác của trnag web 
    </p>

    <a href="/" class="btn">Về trang chủ</a>
    <a href="/tat-ca-san-pham" class="btn">Tiếp tục mua sắm</a>
    <br>
    <a href="/theo-doi-don-hang" class="btn btn-outline">Theo dõi đơn hàng</a>

    <div class="tips">
      <p>★ Mẹo nhỏ: Theo dõi fanpage/FB để nhận nhiều ưu đãi bất ngờ nhé!</p>
    </div>
  </div>

</body>
</html>