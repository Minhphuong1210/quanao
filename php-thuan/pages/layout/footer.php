<!-- Footer Start -->
<div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="text-white mb-4"><i class="fas fa-shopping-bag me-2"></i>Electro</h4>
                    <p class="text-white mb-3">Chuyên cung cấp quần áo trẻ em chất lượng cao, an toàn và thời trang.</p>
                    <div class="d-flex align-items-center">
                        <a class="btn btn-outline-light btn-social me-2" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social me-2" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social me-2" href="#"><i class="fab fa-instagram"></i></a>
                        <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="text-primary mb-4">Liên hệ</h4>
                    <a href="#" class="text-white mb-2"><i class="fas fa-map-marker-alt me-3"></i>123 Đường ABC, TP.HCM, Việt Nam</a>
                    <a href="#" class="text-white mb-2"><i class="fas fa-phone-alt me-3"></i>+012 345 6789</a>
                    <a href="#" class="text-white"><i class="fas fa-envelope me-3"></i>info@electro.com</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="text-primary mb-4">Thông tin</h4>
                    <a href="#" class="text-white mb-2"><i class="fas fa-angle-right me-2"></i>Giới thiệu</a>
                    <a href="#" class="text-white mb-2"><i class="fas fa-angle-right me-2"></i>Chính sách đổi trả</a>
                    <a href="#" class="text-white mb-2"><i class="fas fa-angle-right me-2"></i>Chính sách bảo mật</a>
                    <a href="#" class="text-white mb-2"><i class="fas fa-angle-right me-2"></i>Điều khoản dịch vụ</a>
                    <a href="#" class="text-white mb-2"><i class="fas fa-angle-right me-2"></i>Liên hệ</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="text-primary mb-4">Quan trọng!</h4>
                    <p class="text-white mb-3">Đăng ký để nhận ưu đãi và sản phẩm mới nhất!</p>
                    <form action="">
                        <input type="email" class="form-control mb-3" placeholder="Email của bạn">
                        <button class="btn btn-primary w-100"><a href="/quanao/php-thuan/register.php">Đăng ký</a></button>
                    </form>
                </div>
                <div>
                    <form action="">
                    <h5 class="text-primary mb-4">Đăng nhập tài khoản</h5>
                    <button class="btn btn-primary w-100"><a href="/quanao/php-thuan/login.php">Đăng nhập</a></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-5 pt-5 border-top border-secondary">
            <div class="col-12 text-center">
                <div class="d-flex justify-content-center mb-3">
                    <a class="me-4" href="#"><img src="/quanao/php-thuan/assets/img/payment-1.png" alt="Visa"></a>
                    <a class="me-4" href="#"><img src="/quanao/php-thuan/assets/img/payment-2.png" alt="MasterCard"></a>
                    <a class="me-4" href="#"><img src="/quanao/php-thuan/assets/img/payment-3.png" alt="PayPal"></a>
                    <a class="me-4" href="#"><img src="/quanao/php-thuan/assets/img/payment-4.png" alt="Apple Pay"></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- Copyright Start -->
<div class="container-fluid copyright bg-dark py-4">
    <div class="container text-center">
        <p class="text-white mb-0">&copy; <a class="text-primary fw-bold" href="#">Electro</a> 2025, All Rights Reserved.</p>
        <p class="text-white mb-0">Designed by <a class="text-primary fw-bold" href="https://htmlcodex.com">HTML Codex</a> | Distributed by <a class="text-primary fw-bold" href="https://themewagon.com">ThemeWagon</a></p>
    </div>
</div>
<!-- Copyright End -->

<!-- Back to Top -->
<a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/quanao/php-thuan/assets/lib/wow/wow.min.js"></script>
<script src="/quanao/php-thuan/assets/lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="/quanao/php-thuan/assets/js/main.js"></script>

<!-- Init Carousel & WOW -->
<script>
    $(document).ready(function() {
        // Carousel chính - All Product Items
        $(".productList-carousel").owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            navText: ['<i class="fa fa-angle-left fa-2x"></i>', '<i class="fa fa-angle-right fa-2x"></i>'],
            responsive: {
                0: {
                    items: 1
                },
                576: {
                    items: 2
                },
                768: {
                    items: 3
                },
                992: {
                    items: 4
                }
            }
        });

        // Carousel con - 4 sản phẩm trong mỗi slide
        $(".productImg-carousel").owlCarousel({
            loop: false,
            margin: 15,
            nav: false,
            dots: false,
            items: 4,
            responsive: {
                0: {
                    items: 1
                },
                576: {
                    items: 2
                },
                768: {
                    items: 3
                },
                992: {
                    items: 4
                }
            }
        });

        // Init WOW.js
        new WOW().init();
    });
</script>
</body>

</html>