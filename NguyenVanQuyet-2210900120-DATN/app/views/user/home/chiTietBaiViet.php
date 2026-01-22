<?php include BASE_PATH . '/app/views/user/layout/header.php'; ?>

<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #3498db;
        --accent-color: #e74c3c;
        --light-color: #f8f9fa;
        --gray-color: #6c757d;
        --border-color: #eaeaea;
        --transition: all 0.3s ease;
        --card-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .article-detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    /* Breadcrumb */
    .article-breadcrumb {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .article-breadcrumb a {
        color: var(--secondary-color);
        text-decoration: none;
        transition: var(--transition);
    }

    .article-breadcrumb a:hover {
        color: var(--primary-color);
        text-decoration: underline;
    }

    .article-breadcrumb .separator {
        margin: 0 0.5rem;
        color: var(--gray-color);
    }

    /* Article Header */
    .article-header {
        margin-bottom: 2.5rem;
    }

    .article-category {
        display: inline-block;
        background-color: var(--secondary-color);
        color: white;
        padding: 0.4rem 1.2rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
        text-decoration: none;
        transition: var(--transition);
    }

    .article-category:hover {
        background-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .article-title {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        color: var(--gray-color);
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .meta-item i {
        color: var(--secondary-color);
    }

    /* Article Image */
    .article-featured-image {
        margin-bottom: 2.5rem;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }

    .article-featured-image img {
        width: 100%;
        height: 500px;
        object-fit: cover;
        transition: var(--transition);
    }

    .article-featured-image:hover img {
        transform: scale(1.02);
    }

    .image-caption {
        text-align: center;
        font-style: italic;
        color: var(--gray-color);
        padding: 0.8rem;
        font-size: 0.9rem;
        background-color: var(--light-color);
    }

    /* Article Content */
    .article-content-wrapper {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 3rem;
    }

    .article-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
    }

    .article-content h2 {
        font-size: 1.8rem;
        color: var(--primary-color);
        margin: 2.5rem 0 1.2rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--border-color);
    }

    .article-content h3 {
        font-size: 1.5rem;
        color: var(--primary-color);
        margin: 2rem 0 1rem;
    }

    .article-content p {
        margin-bottom: 1.5rem;
        text-align: justify;
    }

    .article-content blockquote {
        border-left: 4px solid var(--secondary-color);
        padding: 1.5rem 2rem;
        margin: 2rem 0;
        background-color: var(--light-color);
        font-style: italic;
        font-size: 1.2rem;
        color: var(--primary-color);
    }

    .article-content ul, .article-content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .article-content li {
        margin-bottom: 0.5rem;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
        box-shadow: var(--card-shadow);
    }

    .article-content a {
        color: var(--secondary-color);
        text-decoration: none;
        border-bottom: 1px dashed var(--secondary-color);
        transition: var(--transition);
    }

    .article-content a:hover {
        color: var(--accent-color);
        border-bottom-color: var(--accent-color);
    }

    /* Article Tags */
    .article-tags {
        margin: 3rem 0;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
    }

    .tags-title {
        font-size: 1.2rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .tag-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .tag {
        display: inline-block;
        background-color: var(--light-color);
        color: var(--primary-color);
        padding: 0.5rem 1.2rem;
        border-radius: 20px;
        font-size: 0.9rem;
        text-decoration: none;
        transition: var(--transition);
        border: 1px solid var(--border-color);
    }

    .tag:hover {
        background-color: var(--secondary-color);
        color: white;
        transform: translateY(-2px);
        border-color: var(--secondary-color);
    }

    /* Article Actions */
    .article-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 2.5rem 0;
        padding: 1.5rem;
        background-color: var(--light-color);
        border-radius: 10px;
        box-shadow: var(--card-shadow);
    }

    .share-buttons {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .share-title {
        font-weight: 600;
        color: var(--primary-color);
    }

    .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: white;
        text-decoration: none;
        transition: var(--transition);
    }

    .share-btn:hover {
        transform: translateY(-3px);
    }

    .facebook { background-color: #3b5998; }
    .twitter { background-color: #1da1f2; }
    .linkedin { background-color: #0077b5; }
    .zalo { background-color: #0068ff; }

    .action-buttons {
        display: flex;
        gap: 1rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.8rem 1.5rem;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-primary {
        background-color: var(--secondary-color);
        color: white;
        border: 2px solid var(--secondary-color);
    }

    .btn-primary:hover {
        background-color: #2980b9;
        transform: translateY(-3px);
    }

    .btn-outline {
        background-color: transparent;
        color: var(--primary-color);
        border: 2px solid var(--border-color);
    }

    .btn-outline:hover {
        background-color: var(--light-color);
        border-color: var(--secondary-color);
        transform: translateY(-3px);
    }

    /* Related Articles */
    .related-articles {
        margin-top: 4rem;
        padding-top: 2rem;
        border-top: 3px solid var(--border-color);
    }

    .related-title {
        font-size: 1.8rem;
        color: var(--primary-color);
        margin-bottom: 2rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--secondary-color);
        display: inline-block;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }

    .related-card {
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
    }

    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .related-image {
        height: 200px;
        overflow: hidden;
    }

    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .related-card:hover .related-image img {
        transform: scale(1.05);
    }

    .related-content {
        padding: 1.5rem;
    }

    .related-content h3 {
        font-size: 1.2rem;
        margin-bottom: 0.8rem;
        color: var(--primary-color);
        line-height: 1.4;
    }

    .related-content .date {
        font-size: 0.9rem;
        color: var(--gray-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Article Sidebar - MỞ RỘNG */
    .article-sidebar {
        position: sticky;
        top: 100px;
        height: fit-content;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    /* Sidebar Widgets */
    .sidebar-widget {
        background-color: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
    }

    .widget-title {
        font-size: 1.2rem;
        color: var(--primary-color);
        margin-bottom: 1.2rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .widget-title i {
        color: var(--secondary-color);
    }

    /* Author Card */
    .author-card {
        text-align: center;
    }

    .author-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 1rem;
        border: 3px solid var(--secondary-color);
    }

    .author-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .author-name {
        font-size: 1.1rem;
        color: var(--primary-color);
        margin-bottom: 0.3rem;
    }

    .author-bio {
        color: var(--gray-color);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .author-social {
        display: flex;
        justify-content: center;
        gap: 0.8rem;
    }

    .author-social a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: var(--light-color);
        color: var(--primary-color);
        transition: var(--transition);
        font-size: 0.9rem;
    }

    .author-social a:hover {
        background-color: var(--secondary-color);
        color: white;
        transform: translateY(-2px);
    }

    /* Recent Posts Widget */
    .recent-posts-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .recent-post-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .recent-post-item:last-child {
        border-bottom: none;
    }

    .recent-post-item:hover {
        padding-left: 5px;
    }

    .recent-thumbnail {
        width: 70px;
        height: 70px;
        flex-shrink: 0;
        border-radius: 6px;
        overflow: hidden;
    }

    .recent-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .recent-post-item:hover .recent-thumbnail img {
        transform: scale(1.1);
    }

    .recent-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .recent-content h4 {
        font-size: 0.95rem;
        margin: 0 0 5px;
        line-height: 1.4;
    }

    .recent-content h4 a {
        color: var(--primary-color);
        text-decoration: none;
        transition: var(--transition);
    }

    .recent-content h4 a:hover {
        color: var(--secondary-color);
    }

    .recent-date {
        font-size: 0.8rem;
        color: var(--gray-color);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Categories Widget */
    .categories-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .category-item {
        padding: 10px 0;
        border-bottom: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .category-item:last-child {
        border-bottom: none;
    }

    .category-item:hover {
        padding-left: 5px;
    }

    .category-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--primary-color);
        text-decoration: none;
        transition: var(--transition);
    }

    .category-link:hover {
        color: var(--secondary-color);
    }

    .category-count {
        background-color: var(--light-color);
        color: var(--gray-color);
        font-size: 0.8rem;
        padding: 2px 8px;
        border-radius: 10px;
        transition: var(--transition);
    }

    .category-item:hover .category-count {
        background-color: var(--secondary-color);
        color: white;
    }

    /* Popular Posts Widget */
    .popular-posts-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .popular-post-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .popular-post-item:last-child {
        border-bottom: none;
    }

    .popular-rank {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--light-color);
        color: var(--primary-color);
        font-weight: bold;
        border-radius: 50%;
        font-size: 0.9rem;
    }

    .popular-rank.top-1 { background-color: #ffd700; color: #333; }
    .popular-rank.top-2 { background-color: #c0c0c0; color: #333; }
    .popular-rank.top-3 { background-color: #cd7f32; color: white; }

    .popular-content {
        flex: 1;
    }

    .popular-content h4 {
        font-size: 0.9rem;
        margin: 0 0 3px;
        line-height: 1.3;
    }

    .popular-content h4 a {
        color: var(--primary-color);
        text-decoration: none;
        transition: var(--transition);
    }

    .popular-content h4 a:hover {
        color: var(--secondary-color);
    }

    .popular-views {
        font-size: 0.8rem;
        color: var(--gray-color);
        display: flex;
        align-items: center;
        gap: 3px;
    }

    /* Newsletter Widget */
    .newsletter-widget {
        background: linear-gradient(135deg, var(--secondary-color), #2980b9);
        color: white;
        padding: 1.5rem;
        border-radius: 10px;
    }

    .newsletter-title {
        font-size: 1.2rem;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .newsletter-desc {
        font-size: 0.9rem;
        margin-bottom: 1.2rem;
        opacity: 0.9;
        line-height: 1.5;
    }

    .newsletter-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .newsletter-form input {
        padding: 10px 12px;
        border: none;
        border-radius: 5px;
        font-size: 0.9rem;
        width: 100%;
    }

    .newsletter-form button {
        background-color: white;
        color: var(--secondary-color);
        border: none;
        padding: 10px;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .newsletter-form button:hover {
        background-color: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }

    /* Tags Widget */
    .tags-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .tag-cloud-item {
        display: inline-block;
        background-color: var(--light-color);
        color: var(--primary-color);
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        text-decoration: none;
        transition: var(--transition);
        border: 1px solid var(--border-color);
    }

    .tag-cloud-item:hover {
        background-color: var(--secondary-color);
        color: white;
        transform: translateY(-2px);
        border-color: var(--secondary-color);
    }

    /* Ads Widget */
    .ads-widget {
        text-align: center;
        overflow: hidden;
        border-radius: 8px;
    }

    .ads-content {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }

    .ads-content img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        transition: var(--transition);
    }

    .ads-content:hover img {
        transform: scale(1.05);
    }

    .ads-label {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: var(--accent-color);
        color: white;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    /* Social Media Widget */
    .social-widget {
        background: linear-gradient(135deg, #4267B2, #3b5998);
        color: white;
        border-radius: 10px;
        padding: 1.5rem;
    }

    .social-links {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 1rem;
    }

    .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        color: white;
        text-decoration: none;
        transition: var(--transition);
    }

    .social-link:hover {
        background-color: white;
        color: var(--primary-color);
        transform: translateY(-3px);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .article-content-wrapper {
            grid-template-columns: 1fr;
        }

        .article-sidebar {
            position: static;
            margin-top: 3rem;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .article-title {
            font-size: 2rem;
        }

        .article-featured-image img {
            height: 400px;
        }
    }

    @media (max-width: 768px) {
        .article-actions {
            flex-direction: column;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .related-grid {
            grid-template-columns: 1fr;
        }

        .article-title {
            font-size: 1.8rem;
        }

        .article-featured-image img {
            height: 300px;
        }
    }

    @media (max-width: 576px) {
        .article-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.8rem;
        }

        .action-buttons {
            flex-direction: column;
            width: 100%;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .article-featured-image img {
            height: 250px;
        }
    }
</style>

<div class="article-detail-container">
    <!-- Breadcrumb -->
    <div class="article-breadcrumb">
        <a href="<?= BASE_URL ?>">Trang chủ</a>
        <span class="separator">/</span>
        <a href="<?= BASE_URL ?>/danh-muc/<?= htmlspecialchars($article['category_slug']) ?>">
            <?= htmlspecialchars($article['category_name']) ?>
        </a>
        <span class="separator">/</span>
        <span><?= htmlspecialchars($article['name']) ?></span>
    </div>

    <div class="article-content-wrapper">
        <!-- Main Content -->
        <main class="article-main">
            <!-- Article Header -->
            <header class="article-header">
                <a href="<?= BASE_URL ?>/danh-muc/<?= htmlspecialchars($article['category_slug']) ?>" 
                   class="article-category">
                    <?= htmlspecialchars($article['category_name']) ?>
                </a>
                
                <h1 class="article-title"><?= htmlspecialchars($article['name']) ?></h1>
                
                <div class="article-meta">
                    <div class="meta-item">
                        <i class="far fa-user"></i>
                        <span><?= htmlspecialchars($article['author_name'] ?? 'Quản trị viên') ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <!-- <span><?= date('d/m/Y', strtotime($article['created_at'])) ?></span> -->
                    </div>
                    <div class="meta-item">
                        <i class="far fa-eye"></i>
                        <span><?= number_format($article['views'] ?? 0) ?> lượt xem</span>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            <?php if (!empty($article['image'])): ?>
            <div class="article-featured-image">
                <img src="<?=BASE_URL?>/<?=$article['image']?>" 
                     alt="<?= htmlspecialchars($article['name']) ?>">
                <?php if (!empty($article['image_caption'])): ?>
                <div class="image-caption"><?= htmlspecialchars($article['image_caption']) ?></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Article Content -->
            <div class="article-content">
                <!-- Hiển thị mô tả ngắn -->
                <?php if (!empty($article['description'])): ?>
                <div class="article-excerpt">
                    <p><strong><?= htmlspecialchars($article['description']) ?></strong></p>
                </div>
                <?php endif; ?>

                <!-- Hiển thị nội dung đầy đủ -->
                <div class="article-body">
                    <?= $article['content'] ?? 'Nội dung đang được cập nhật...' ?>
                </div>
            </div>

            <!-- Tags -->
            <?php if (!empty($article['tags'])): ?>
            <div class="article-tags">
                <div class="tags-title">Thẻ bài viết:</div>
                <div class="tag-list">
                    <?php 
                    $tags = explode(',', $article['tags']);
                    foreach ($tags as $tag): 
                        $tag = trim($tag);
                        if (!empty($tag)):
                    ?>
                    <a href="<?= BASE_URL ?>/tag/<?= urlencode($tag) ?>" class="tag">
                        <?= htmlspecialchars($tag) ?>
                    </a>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Share & Actions -->
            <div class="article-actions">
                <div class="share-buttons">
                    <span class="share-title">Chia sẻ:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" 
                       class="share-btn facebook" target="_blank">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($article['name']) ?>" 
                       class="share-btn twitter" target="_blank">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(current_url()) ?>&title=<?= urlencode($article['name']) ?>" 
                       class="share-btn linkedin" target="_blank">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="https://zalo.me/share?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($article['name']) ?>" 
                       class="share-btn zalo" target="_blank">
                        Z
                    </a>
                </div>

                <div class="action-buttons">
                    <a href="<?= BASE_URL ?>/danh-muc/<?= htmlspecialchars($article['category_slug']) ?>" 
                       class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Quay lại danh mục
                    </a>
                    <a href="<?= BASE_URL ?>" class="btn btn-primary">
                        <i class="fas fa-home"></i> Về trang chủ
                    </a>
                </div>
            </div>

            <!-- Related Articles -->
            <?php if (!empty($related_articles)): ?>
            <section class="related-articles">
                <h2 class="related-title">Bài viết liên quan</h2>
                <div class="related-grid">
                    <?php foreach ($related_articles as $related): ?>
                    <article class="related-card">
                        <a href="<?= BASE_URL ?>/chi-tiet-bai-viet/<?= $related['slug'] ?>">
                            <div class="related-image">
                                <img src="<?= htmlspecialchars($related['image']) ?>" 
                                     alt="<?= htmlspecialchars($related['name']) ?>">
                            </div>
                            <div class="related-content">
                                <h3><?= htmlspecialchars($related['name']) ?></h3>
                                <div class="date">
                                    <i class="far fa-calendar"></i>
                                    <?= date('d/m/Y', strtotime($related['created_at'])) ?>
                                </div>
                            </div>
                        </a>
                    </article>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        </main>

        <!-- Sidebar - ĐÃ ĐƯỢC MỞ RỘNG -->
        <aside class="article-sidebar">
            <!-- Author Widget -->
            <div class="sidebar-widget author-card">
                <h3 class="widget-title"><i class="fas fa-user"></i> Tác giả</h3>
                <div class="author-avatar">
                    <img src="<?= $article['author_avatar'] ?? 'https://via.placeholder.com/100' ?>" 
                         alt="<?= htmlspecialchars($article['author_name'] ?? 'Tác giả') ?>">
                </div>
                <h4 class="author-name"><?= htmlspecialchars($article['author_name'] ?? 'Quản trị viên') ?></h4>
                <p class="author-bio">
                    <?= htmlspecialchars($article['author_bio'] ?? 'Chuyên gia chia sẻ kiến thức và kinh nghiệm') ?>
                </p>
                <div class="author-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Recent Posts Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title"><i class="far fa-newspaper"></i> Bài viết mới nhất</h3>
                <ul class="recent-posts-list">
                    <?php if (!empty($recent_posts)): ?>
                        <?php foreach ($recent_posts as $index => $post): ?>
                        <li class="recent-post-item">
                            <div class="recent-thumbnail">
                                <img src="<?= htmlspecialchars($post['image']) ?>" 
                                     alt="<?= htmlspecialchars($post['name']) ?>">
                            </div>
                            <div class="recent-content">
                                <h4>
                                    <a href="<?= BASE_URL ?>/chi-tiet-bai-viet/<?= $post['slug'] ?>">
                                        <?= htmlspecialchars($post['name']) ?>
                                    </a>
                                </h4>
                                <div class="recent-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Categories Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title"><i class="fas fa-folder"></i> Danh mục</h3>
                <ul class="categories-list">
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                        <li class="category-item">
                            <a href="<?= BASE_URL ?>/danh-muc/<?= $category['slug'] ?>" class="category-link">
                                <span><?= htmlspecialchars($category['name']) ?></span>
                                <span class="category-count"><?= $category['post_count'] ?? 0 ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Popular Posts Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title"><i class="fas fa-fire"></i> Bài viết phổ biến</h3>
                <ul class="popular-posts-list">
                    <?php if (!empty($popular_posts)): ?>
                        <?php foreach ($popular_posts as $index => $post): ?>
                        <li class="popular-post-item">
                            <div class="popular-rank top-<?= $index + 1 ?>">
                                <?= $index + 1 ?>
                            </div>
                            <div class="popular-content">
                                <h4>
                                    <a href="<?= BASE_URL ?>/chi-tiet-bai-viet/<?= $post['slug'] ?>">
                                        <?= htmlspecialchars($post['name']) ?>
                                    </a>
                                </h4>
                                <div class="popular-views">
                                    <i class="far fa-eye"></i>
                                    <?= number_format($post['views'] ?? 0) ?> lượt xem
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Newsletter Widget -->
            <div class="sidebar-widget newsletter-widget">
                <h3 class="newsletter-title"><i class="far fa-envelope"></i> Đăng ký nhận tin</h3>
                <p class="newsletter-desc">Nhận thông báo khi có bài viết mới qua email</p>
                <form class="newsletter-form" action="<?= BASE_URL ?>/subscribe" method="POST">
                    <input type="email" name="email" placeholder="Nhập email của bạn" required>
                    <button type="submit">
                        <i class="fas fa-paper-plane"></i> Đăng ký ngay
                    </button>
                </form>
            </div>

            <!-- Tags Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title"><i class="fas fa-tags"></i> Thẻ phổ biến</h3>
                <div class="tags-cloud">
                    <?php if (!empty($popular_tags)): ?>
                        <?php foreach ($popular_tags as $tag): ?>
                        <a href="<?= BASE_URL ?>/tag/<?= urlencode($tag['name']) ?>" class="tag-cloud-item">
                            <?= htmlspecialchars($tag['name']) ?>
                        </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Social Media Widget -->
            <div class="sidebar-widget social-widget">
                <h3 class="widget-title" style="color: white;"><i class="fas fa-users"></i> Theo dõi chúng tôi</h3>
                <p style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 1rem;">Kết nối với chúng tôi trên mạng xã hội</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <!-- Ads Widget -->
            <div class="sidebar-widget ads-widget">
                <div class="ads-content">
                    <span class="ads-label">QUẢNG CÁO</span>
                    <a href="#" target="_blank">
                        <img src="https://via.placeholder.com/350x250" alt="Quảng cáo">
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php include BASE_PATH . '/app/views/user/layout/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to get current URL
        function current_url() {
            return window.location.href;
        }

        // Update view count
        function updateViewCount() {
            const articleId = <?= $article['id'] ?? 0 ?>;
            if (articleId) {
                fetch('<?= BASE_URL ?>/api/update-views/' + articleId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });
            }
        }

        // Newsletter form submission
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const email = this.querySelector('input[name="email"]').value;
                
                if (!email) {
                    alert('Vui lòng nhập email!');
                    return;
                }
                
                // Submit form
                this.submit();
            });
        }

        // Update view count after page load
        updateViewCount();

        // Smooth scroll for TOC links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId !== '#') {
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    });
</script>