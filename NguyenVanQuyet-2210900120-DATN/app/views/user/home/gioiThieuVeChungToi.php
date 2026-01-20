<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu về chúng tôi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
        }
        
        h1, h2, h3, h4 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            background: linear-gradient(135deg, #1a2980, #26d0ce);
            color: white;
            padding: 1rem 0;
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
        }
        
        .logo i {
            margin-right: 10px;
            color: #ffd700;
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 2rem;
        }
        
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 0;
            position: relative;
        }
        
        nav ul li a:hover {
            color: #ffd700;
        }
        
        nav ul li a:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: #ffd700;
            bottom: 0;
            left: 0;
            transition: width 0.3s ease;
        }
        
        nav ul li a:hover:after {
            width: 100%;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(26, 41, 128, 0.8), rgba(38, 208, 206, 0.8)), url('https://images.unsplash.com/photo-1556761175-b413da4baf72?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 6rem 0;
        }
        
        .hero h1 {
            color: white;
            font-size: 3.2rem;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .hero p {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto 2rem;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }
        
        .btn {
            display: inline-block;
            background-color: #ff6b35;
            color: white;
            padding: 0.9rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }
        
        .btn:hover {
            background-color: #ff824e;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
        }
        
        /* About Section */
        .about {
            padding: 5rem 0;
            background-color: white;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3.5rem;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            display: inline-block;
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title h2:after {
            content: '';
            position: absolute;
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, #1a2980, #26d0ce);
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }
        
        .about-content {
            display: flex;
            align-items: center;
            gap: 4rem;
        }
        
        .about-text {
            flex: 1;
        }
        
        .about-text h3 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: #1a2980;
        }
        
        .about-text p {
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            color: #555;
        }
        
        .about-image {
            flex: 1;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s ease;
        }
        
        .about-image:hover img {
            transform: scale(1.05);
        }
        
        /* Values Section */
        .values {
            padding: 5rem 0;
            background-color: #f0f7ff;
        }
        
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
        }
        
        .value-card {
            background-color: white;
            border-radius: 10px;
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-top: 5px solid #26d0ce;
        }
        
        .value-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .value-icon {
            background: linear-gradient(135deg, #1a2980, #26d0ce);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }
        
        .value-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .value-card p {
            color: #666;
        }
        
        /* CTA Section */
        .cta {
            background: linear-gradient(135deg, #1a2980, #26d0ce);
            color: white;
            padding: 5rem 0;
            text-align: center;
        }
        
        .cta h2 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }
        
        .cta p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2.5rem;
            opacity: 0.9;
        }
        
        .btn-light {
            background-color: white;
            color: #1a2980;
        }
        
        .btn-light:hover {
            background-color: #f0f0f0;
        }
        
        /* Footer */
        footer {
            background-color: #2c3e50;
            color: #ddd;
            padding: 3.5rem 0 1.5rem;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 2rem;
            margin-bottom: 2.5rem;
        }
        
        .footer-column {
            flex: 1;
            min-width: 250px;
        }
        
        .footer-column h3 {
            color: white;
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-column h3:after {
            content: '';
            position: absolute;
            width: 50px;
            height: 3px;
            background: #26d0ce;
            bottom: 0;
            left: 0;
        }
        
        .footer-column p, .footer-column li {
            margin-bottom: 0.8rem;
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column ul li a {
            color: #ddd;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-column ul li a:hover {
            color: #26d0ce;
            padding-left: 5px;
        }
        
        .contact-info li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .contact-info i {
            margin-right: 10px;
            color: #26d0ce;
            width: 20px;
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
            color: white;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: #26d0ce;
            transform: translateY(-3px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: #aaa;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .about-content {
                flex-direction: column;
                gap: 3rem;
            }
            
            .hero h1 {
                font-size: 2.8rem;
            }
        }
        
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            nav ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            nav ul li {
                margin: 0 0.8rem;
            }
            
            .hero h1 {
                font-size: 2.3rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero {
                padding: 4rem 0;
            }
            
            .hero h1 {
                font-size: 1.9rem;
            }
            
            .btn {
                padding: 0.8rem 2rem;
                font-size: 1rem;
            }
            
            .about, .values, .cta {
                padding: 3.5rem 0;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <i class="fas fa-crown"></i>
                <span>ELITE SERVICES</span>
            </div>
            <nav>
                <ul>
                    <li><a href="#home">Trang chủ</a></li>
                    <li><a href="#about">Về chúng tôi</a></li>
                    <li><a href="#values">Giá trị cốt lõi</a></li>
                    <li><a href="#contact">Liên hệ</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <h1>Giới thiệu về chúng tôi</h1>
            <p>Đơn vị cung cấp sản phẩm và dịch vụ uy tín, luôn đặt chất lượng, giá trị thực tế và sự hài lòng của khách hàng làm ưu tiên hàng đầu.</p>
            <a href="#contact" class="btn">Liên hệ ngay</a>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="section-title">
                <h2>Về chúng tôi</h2>
            </div>
            <div class="about-content">
                <div class="about-text">
                    <h3>Chuyên gia trong lĩnh vực bán hàng và cung cấp dịch vụ</h3>
                    <p>Với kinh nghiệm hoạt động trong lĩnh vực bán hàng và cung cấp dịch vụ, chúng tôi cam kết mang đến cho khách hàng những sản phẩm chất lượng cao, nguồn gốc rõ ràng cùng dịch vụ chuyên nghiệp.</p>
                    <p>Mỗi sản phẩm được lựa chọn kỹ lưỡng, mỗi dịch vụ được thực hiện tận tâm nhằm đảm bảo hiệu quả, tiết kiệm chi phí và xây dựng mối quan hệ hợp tác lâu dài với khách hàng.</p>
                    <p>Chúng tôi tin rằng sự thành công của khách hàng chính là thước đo cho sự thành công của chúng tôi. Vì vậy, mọi quyết định và hành động của chúng tôi đều hướng đến việc mang lại giá trị tối ưu cho đối tác.</p>
                </div>
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Đội ngũ chuyên nghiệp của chúng tôi">
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values" id="values">
        <div class="container">
            <div class="section-title">
                <h2>Giá trị cốt lõi</h2>
            </div>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3>Chất lượng hàng đầu</h3>
                    <p>Chúng tôi cam kết cung cấp sản phẩm và dịch vụ với chất lượng tốt nhất, đáp ứng và vượt qua kỳ vọng của khách hàng.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Uy tín và minh bạch</h3>
                    <p>Mọi sản phẩm đều có nguồn gốc rõ ràng, thông tin minh bạch, xây dựng lòng tin vững chắc với khách hàng.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Tận tâm phục vụ</h3>
                    <p>Đội ngũ chuyên nghiệp, tận tâm, luôn lắng nghe và thấu hiểu nhu cầu của khách hàng để mang đến giải pháp tối ưu.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Hiệu quả và tiết kiệm</h3>
                    <p>Giúp khách hàng đạt được mục tiêu với chi phí hợp lý nhất, tối ưu hóa hiệu quả đầu tư.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="contact">
        <div class="container">
            <h2>Sẵn sàng hợp tác cùng chúng tôi?</h2>
            <p>Hãy liên hệ ngay hôm nay để nhận tư vấn và giải pháp tốt nhất cho nhu cầu của bạn. Chúng tôi cam kết mang đến sự hài lòng và giá trị vượt trội.</p>
            <a href="tel:+84987654321" class="btn btn-light">
                <i class="fas fa-phone-alt"></i> Gọi ngay: 0987 654 321
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Về chúng tôi</h3>
                    <p>Đơn vị cung cấp sản phẩm và dịch vụ uy tín hàng đầu, với cam kết về chất lượng, giá trị thực tế và sự hài lòng của khách hàng.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Liên hệ</h3>
                    <ul class="contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Số 123, Đường ABC, Quận XYZ, TP. Hồ Chí Minh</span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span>Hotline: 0987 654 321</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>info@eliteservices.vn</span>
                        </li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Liên kết nhanh</h3>
                    <ul>
                        <li><a href="#home">Trang chủ</a></li>
                        <li><a href="#about">Về chúng tôi</a></li>
                        <li><a href="#values">Giá trị cốt lõi</a></li>
                        <li><a href="#contact">Liên hệ</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2023 ELITE SERVICES. Tất cả các quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('nav a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            });
        });
        
        // Add animation to value cards when they come into view
        const observerOptions = {
            threshold: 0.2,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        // Apply animation to value cards
        document.querySelectorAll('.value-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>