# public/.htaccess
RewriteEngine On

# Không rewrite cho static files (images, css, js)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Rewrite tất cả về index.php
RewriteRule ^(.*)$ index.php [QSA,L]

# Bảo mật: Chặn truy cập trực tiếp vào .php files ngoài public
<Files ~ "\.php$">
    Order allow,deny
    Deny from all
</Files>
<Files "index.php">
    Allow from all
</Files>