<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Tin Tức - Cập nhật thông tin sản phẩm & dịch vụ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Biến CSS */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --gray-color: #6c757d;
            --border-color: #dee2e6;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        /* Reset và cơ bản */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            background-color: #f5f7fa;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid var(--secondary-color);
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 80px;
            height: 3px;
            background-color: var(--accent-color);
        }

        /* Header */
        header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            color: var(--secondary-color);
        }

        nav ul {
            display: flex;
            gap: 1.5rem;
        }

        nav a {
            font-weight: 500;
            padding: 0.5rem 0;
            position: relative;
            transition: var(--transition);
        }

        nav a:hover, nav a.active {
            color: var(--secondary-color);
        }

        nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--secondary-color);
            transition: var(--transition);
        }

        nav a:hover::after, nav a.active::after {
            width: 100%;
        }

        .mobile-menu-btn {
            display: none;
            font-size: 1.5rem;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
        }

        /* Hero section */
        .hero {
            background: linear-gradient(rgba(44, 62, 80, 0.9), rgba(52, 152, 219, 0.8)), url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 5rem 0;
            margin-bottom: 3rem;
        }

        .hero h1 {
            font-size: 2.8rem;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto 2rem;
        }

        /* Main layout */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2.5rem;
            margin-bottom: 3rem;
        }

        /* Tin tức chính */
        .featured-news {
            margin-bottom: 2.5rem;
        }

        .featured-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .featured-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }

        .featured-image {
            height: 300px;
            overflow: hidden;
        }

        .featured-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .featured-card:hover .featured-image img {
            transform: scale(1.05);
        }

        .featured-content {
            padding: 1.8rem;
        }

        .featured-content .category {
            display: inline-block;
            background-color: var(--secondary-color);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .featured-content h2 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .featured-content p {
            color: var(--gray-color);
            margin-bottom: 1.2rem;
        }

        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: var(--secondary-color);
            font-weight: 600;
            transition: var(--transition);
        }

        .read-more:hover {
            gap: 10px;
            color: var(--accent-color);
        }

        /* Danh sách tin tức */
        .news-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .news-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .news-image {
            height: 180px;
            overflow: hidden;
        }

        .news-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .news-card:hover .news-image img {
            transform: scale(1.05);
        }

        .news-content {
            padding: 1.5rem;
        }

        .news-content .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            font-size: 0.85rem;
        }

        .news-content .category {
            color: var(--secondary-color);
            font-weight: 600;
        }

        .news-content .date {
            color: var(--gray-color);
        }

        .news-content h3 {
            font-size: 1.3rem;
            margin-bottom: 0.8rem;
            color: var(--primary-color);
        }

        .news-content p {
            color: var(--gray-color);
            font-size: 0.95rem;
            margin-bottom: 1.2rem;
        }

        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .sidebar-widget {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .widget-title {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 1.2rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border-color);
        }

        .categories-list li {
            padding: 0.7rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .categories-list li:last-child {
            border-bottom: none;
        }

        .categories-list a {
            display: flex;
            justify-content: space-between;
            transition: var(--transition);
        }

        .categories-list a:hover {
            color: var(--secondary-color);
        }

        .categories-list .count {
            background-color: var(--light-color);
            padding: 0.1rem 0.6rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .promotion-banner {
            background: linear-gradient(135deg, var(--secondary-color), #1abc9c);
            color: white;
            text-align: center;
            padding: 2rem 1.5rem;
            border-radius: 8px;
        }

        .promotion-banner h3 {
            font-size: 1.5rem;
            margin-bottom: 0.8rem;
        }

        .promotion-banner p {
            margin-bottom: 1.5rem;
        }

        .btn {
            display: inline-block;
            background-color: white;
            color: var(--secondary-color);
            padding: 0.7rem 1.5rem;
            border-radius: 30px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }

        .recent-posts li {
            display: flex;
            gap: 1rem;
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .recent-posts li:last-child {
            border-bottom: none;
        }

        .recent-thumbnail {
            width: 70px;
            height: 70px;
            flex-shrink: 0;
            border-radius: 5px;
            overflow: hidden;
        }

        .recent-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .recent-content h4 {
            font-size: 0.95rem;
            margin-bottom: 0.3rem;
            color: var(--primary-color);
        }

        .recent-content .date {
            font-size: 0.8rem;
            color: var(--gray-color);
        }

        /* Footer */
        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 3rem 0 1.5rem;
            margin-top: 3rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-column h3 {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: var(--secondary-color);
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-links a:hover {
            color: var(--secondary-color);
            padding-left: 5px;
        }

        .footer-links i {
            font-size: 0.8rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: var(--transition);
        }

        .social-links a:hover {
            background-color: var(--secondary-color);
            transform: translateY(-3px);
        }

        .newsletter-form {
            display: flex;
            margin-top: 1rem;
        }

        .newsletter-form input {
            flex: 1;
            padding: 0.8rem;
            border: none;
            border-radius: 30px 0 0 30px;
            font-size: 0.95rem;
        }

        .newsletter-form button {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 0 1.5rem;
            border-radius: 0 30px 30px 0;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
        }

        .newsletter-form button:hover {
            background-color: #2980b9;
        }

        .copyright {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .news-list {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
            
            .hero h1 {
                font-size: 2.3rem;
            }
        }

        @media (max-width: 768px) {
            nav ul {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: var(--primary-color);
                flex-direction: column;
                padding: 1rem 0;
                box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            }
            
            nav ul.active {
                display: flex;
            }
            
            nav ul li {
                text-align: center;
                padding: 0.5rem 0;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .hero {
                padding: 3rem 0;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .featured-image {
                height: 220px;
            }
            
            .news-list {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <i class="fas fa-newspaper"></i>
                <span>Tin Tức Công Ty</span>
            </div>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            
            <nav>
                <ul id="mainMenu">
                    <li><a href="#" class="active">Trang chủ</a></li>
                    <li><a href="#">Tin tức</a></li>
                    <li><a href="#">Sản phẩm</a></li>
                    <li><a href="#">Dịch vụ</a></li>
                    <li><a href="#">Ưu đãi</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Cập nhật thông tin mới nhất</h1>
            <p>Đây là nơi tổng hợp các tin tức, thông báo và bài viết mới nhất liên quan đến sản phẩm, dịch vụ, hoạt động kinh doanh và những cập nhật quan trọng của chúng tôi.</p>
            <p>Các thông tin được cập nhật thường xuyên nhằm giúp khách hàng nhanh chóng nắm bắt xu hướng, chương trình ưu đãi và những thay đổi cần thiết trong quá trình sử dụng sản phẩm và dịch vụ.</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <div class="main-content">
            <!-- Main News Section -->
            <main>
                <!-- Featured News -->
                <section class="featured-news">
                    <h2 class="section-title">Tin tức nổi bật</h2>
                    <article class="featured-card">
                        <div class="featured-image">
                            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Ra mắt sản phẩm mới">
                        </div>
                        <div class="featured-content">
                            <span class="category">Sản phẩm mới</span>
                            <h2>Ra mắt dòng sản phẩm công nghệ cao - Phiên bản 2024</h2>
                            <p>Chúng tôi tự hào giới thiệu dòng sản phẩm công nghệ cao mới nhất với nhiều cải tiến vượt trội về hiệu suất và tiết kiệm năng lượng. Sản phẩm được phát triển dựa trên nghiên cứu thị trường và phản hồi từ khách hàng.</p>
                            <a href="#" class="read-more">Đọc tiếp <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                </section>

                <!-- News List -->
                <section class="latest-news">
                    <h2 class="section-title">Tin tức mới nhất</h2>
                    <div class="news-list">
                        <!-- News item 1 -->
                        <article class="news-card">
                            <div class="news-image">
                                <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Khuyến mãi mùa hè">
                            </div>
                            <div class="news-content">
                                <div class="meta">
                                    <span class="category">Khuyến mãi</span>
                                    <span class="date">15/06/2024</span>
                                </div>
                                <h3>Chương trình ưu đãi mùa hè: Giảm đến 30% cho sản phẩm điện tử</h3>
                                <p>Nhân dịp hè 2024, chúng tôi triển khai chương trình khuyến mãi lớn với ưu đãi lên đến 30% cho các sản phẩm điện tử tiêu dùng.</p>
                                <a href="#" class="read-more">Đọc tiếp <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </article>

                        <!-- News item 2 -->
                        <article class="news-card">
                            <div class="news-image">
                                <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Hội nghị khách hàng">
                            </div>
                            <div class="news-content">
                                <div class="meta">
                                    <span class="category">Sự kiện</span>
                                    <span class="date">10/06/2024</span>
                                </div>
                                <h3>Hội nghị khách hàng thường niên 2024: Kết nối và phát triển</h3>
                                <p>Sự kiện quan trọng nhất trong năm dành cho đối tác và khách hàng thân thiết sẽ diễn ra vào tháng 7 tới đây.</p>
                                <a href="#" class="read-more">Đọc tiếp <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </article>

                        <!-- News item 3 -->
                        <article class="news-card">
                            <div class="news-image">
                                <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80" alt="Nâng cấp dịch vụ">
                            </div>
                            <div class="news-content">
                                <div class="meta">
                                    <span class="category">Dịch vụ</span>
                                    <span class="date">05/06/2024</span>
                                </div>
                                <h3>Nâng cấp hệ thống dịch vụ khách hàng: Hỗ trợ 24/7</h3>
                                <p>Từ tháng 6/2024, chúng tôi chính thức triển khai dịch vụ hỗ trợ khách hàng 24/7 trên tất cả các kênh liên lạc.</p>
                                <a href="#" class="read-more">Đọc tiếp <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </article>

                        <!-- News item 4 -->
                        <article class="news-card">
                            <div class="news-image">
                                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Báo cáo tài chính">
                            </div>
                            <div class="news-content">
                                <div class="meta">
                                    <span class="category">Kinh doanh</span>
                                    <span class="date">01/06/2024</span>
                                </div>
                                <h3>Báo cáo tài chính quý 1/2024: Tăng trưởng ấn tượng 25%</h3>
                                <p>Công ty đạt mức tăng trưởng doanh thu 25% trong quý 1 năm 2024, vượt xa kế hoạch đề ra.</p>
                                <a href="#" class="read-more">Đọc tiếp <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </article>
                    </div>
                </section>
            </main>

            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- Categories -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Danh mục tin tức</h3>
                    <ul class="categories-list">
                        <li><a href="#">Tất cả tin tức <span class="count">42</span></a></li>
                        <li><a href="#">Sản phẩm mới <span class="count">12</span></a></li>
                        <li><a href="#">Khuyến mãi <span class="count">8</span></a></li>
                        <li><a href="#">Dịch vụ <span class="count">7</span></a></li>
                        <li><a href="#">Sự kiện <span class="count">5</span></a></li>
                        <li><a href="#">Kinh doanh <span class="count">6</span></a></li>
                        <li><a href="#">Cập nhật hệ thống <span class="count">4</span></a></li>
                    </ul>
                </div>

                <!-- Promotion Banner -->
                <div class="promotion-banner">
                    <h3>Ưu đãi đặc biệt</h3>
                    <p>Giảm 25% cho đơn hàng đầu tiên khi đăng ký nhận bản tin</p>
                    <a href="#" class="btn">Đăng ký ngay</a>
                </div>

                <!-- Recent Posts -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Bài viết gần đây</h3>
                    <ul class="recent-posts">
                        <li>
                            <div class="recent-thumbnail">
                                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Bài viết gần đây 1">
                            </div>
                            <div class="recent-content">
                                <h4>Hướng dẫn sử dụng sản phẩm mới</h4>
                                <span class="date">14/06/2024</span>
                            </div>
                        </li>
                        <li>
                            <div class="recent-thumbnail">
                                <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Bài viết gần đây 2">
                            </div>
                            <div class="recent-content">
                                <h4>Lịch bảo trì hệ thống tháng 6</h4>
                                <span class="date">12/06/2024</span>
                            </div>
                        </li>
                        <li>
                            <div class="recent-thumbnail">
                                <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Bài viết gần đây 3">
                            </div>
                            <div class="recent-content">
                                <h4>Tuyển dụng nhân sự mới</h4>
                                <span class="date">10/06/2024</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Về chúng tôi</h3>
                    <p>Chúng tôi là công ty hàng đầu trong lĩnh vực cung cấp sản phẩm và dịch vụ chất lượng cao. Trang tin tức này cập nhật mọi thông tin mới nhất về hoạt động kinh doanh, sản phẩm và dịch vụ của chúng tôi.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Liên kết nhanh</h3>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Trang chủ</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Tin tức</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Sản phẩm</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Dịch vụ</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Liên hệ</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Thông tin liên hệ</h3>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i> 123 Đường ABC, Quận 1, TP.HCM</a></li>
                        <li><a href="#"><i class="fas fa-phone"></i> (028) 1234 5678</a></li>
                        <li><a href="#"><i class="fas fa-envelope"></i> info@congty.com</a></li>
                        <li><a href="#"><i class="fas fa-clock"></i> Thứ 2 - Thứ 6: 8:00 - 17:00</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Đăng ký nhận tin</h3>
                    <p>Đăng ký để nhận thông báo về tin tức và ưu đãi mới nhất từ chúng tôi.</p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Email của bạn" required>
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2024 Công Ty TNHH ABC. Tất cả các quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('mainMenu').classList.toggle('active');
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mainMenu');
            const menuBtn = document.getElementById('mobileMenuBtn');
            
            if (!menu.contains(event.target) && !menuBtn.contains(event.target)) {
                menu.classList.remove('active');
            }
        });
        
        // Simple form submission
        document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            alert(`Cảm ơn bạn đã đăng ký nhận tin với email: ${email}`);
            this.reset();
        });
    </script>
</body>
</html>