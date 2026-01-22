 <!-- Header -->
 <?php include BASE_PATH . '/app/views/user/layout/header.php'; ?>
    <style>





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
        /* .hero {
            background: linear-gradient(rgba(44, 62, 80, 0.9), rgba(52, 152, 219, 0.8)), url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 5rem 0;
            margin-bottom: 3rem;
        } */

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



    <!-- Hero Section -->
    <section class="hero">
        <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">
            Danh mục bài viết
        </h1>
        <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
            <li class="breadcrumb-item"><a href="<?=BASE_URL?>">Trang chủ</a></li>
            <li class="breadcrumb-item active text-white">Danh mục bài viết</li>
        </ol>
            </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <div class="main-content">
            <!-- Main News Section -->
            <main>
                <!-- Featured News -->
                <?php if (!empty($posts)): ?>
                <section class="featured-news">
                    <h2 class="section-title">
                        <?= htmlspecialchars($categoryPost['name']) ?>
                    </h2>

                    <?php $featured = $posts[0]; ?>
                    <article class="featured-card">
                        <div class="featured-image">
                            <img src="<?=BASE_URL?>/<?=$featured['image']?>">
                        </div>
                        <div class="featured-content">
                            <span class="category">
                                <?= htmlspecialchars($featured['category_name']) ?>
                            </span>
                            <h2><?= htmlspecialchars($featured['name']) ?></h2>
                            <p><?= htmlspecialchars($featured['description']) ?></p>
                            <a href="<?= BASE_URL ?>chi-tiet-bai-viet/<?= $featured['slug'] ?>"
                               class="read-more">
                                Đọc tiếp <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                </section>
            <?php endif; ?>
                <!-- News List -->
                <section class="latest-news">
                <h2 class="section-title">Tin tức mới nhất</h2>

                <div class="news-list">
                    <?php foreach ($posts as $key => $post): ?>
                        <?php if ($key == 0) continue; ?>

                        <article class="news-card">
                            <div class="news-image">
                                <img src="<?=BASE_URL?>/<?=$post['image']?>"
                                     alt="<?= htmlspecialchars($post['name']) ?>">
                            </div>
                            <div class="news-content">
                                <div class="meta">
                                    <span class="category">
                                        <?= htmlspecialchars($post['category_name']) ?>
                                    </span>
                                    <span class="date">
                                        <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                                    </span>
                                </div>
                                <h3><?= htmlspecialchars($post['name']) ?></h3>
                                <p><?= htmlspecialchars($post['description']) ?></p>
                                <a href="<?= BASE_URL ?>chi-tiet-bai-viet/<?= $post['slug'] ?>"
                                   class="read-more">
                                    Đọc tiếp <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>

                    <?php endforeach; ?>
                </div>
            </section>
            </main>

            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- Categories -->
                <div class="sidebar-widget">
                <h3 class="widget-title">Danh mục tin tức</h3>
                <ul class="categories-list">
                    <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="<?= BASE_URL ?>/danh-muc/<?= $cat['slug'] ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                                <span class="count"><?= $cat['total'] ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
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
                    <?php foreach ($recentPosts as $post): ?>
                        <li>
                            <div class="recent-thumbnail">
                                <img src="<?=BASE_URL?>/<?=$post['image']?>"
                                     alt="<?= htmlspecialchars($post['name']) ?>">
                            </div>

                            <div class="recent-content">
                                <h4>
                                    <a href="<?= BASE_URL ?>chi-tiet-bai-viet/<?= $post['slug'] ?>">
                                        <?= htmlspecialchars($post['name']) ?>
                                    </a>
                                </h4>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
</div>

            </aside>
        </div>
    </div>

<?php include BASE_PATH . '/app/views/user/layout/footer.php'; ?>
