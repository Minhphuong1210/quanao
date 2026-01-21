<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quanao Admin - <?=$title ?? 'Dashboard'?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=BASE_URL?>assets/admin/css/style.css">

</head>

<body class="d-flex flex-column h-100">

    <?php include BASE_PATH . '/app/views/admin/layout/sidebar.php'; ?>
    <?php include BASE_PATH . '/app/views/admin/layout/header.php'; ?>



    <main class="main-content flex-grow-1 d-flex flex-column">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger rounded-3 mb-4"><?=$error?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success rounded-3 mb-4"><?=$success?></div>
        <?php endif; ?>

        <div class="content-placeholder flex-grow-1">

<!-- đây là nội dung của category  -->


<h4><?=$pageTitle ?? 'Thêm nhà cung cấp'?></h4>

<form action="<?= BASE_URL ?>admin/sizes/create" method="post" class="w-50 mx-auto">

    <!-- Name -->
    <div class="mb-3">
        <label class="form-label">Tên</label>
        <input type="text" name="name" class="form-control" required>
    </div>


    <button type="submit" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm kích thước 
    </button>

    <a href="<?=BASE_URL?>admin/nha_cung_cap" class="btn btn-secondary ms-2">
        Quay lại
    </a>
</form>


<script>
    // Search
    document.getElementById('searchBtn').addEventListener('click', function(){
        const search = document.getElementById('searchInput').value;
        // Reload page với param search
        window.location.href = '?search=' + encodeURIComponent(search);
    });
</script>



        </div>
    </main>



    <?php include BASE_PATH . '/app/views/admin/layout/footer.php'; ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>