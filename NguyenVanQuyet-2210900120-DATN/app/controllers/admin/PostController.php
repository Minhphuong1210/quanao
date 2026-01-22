<?php

require_once BASE_PATH . '/app/models/Post.php';
require_once BASE_PATH . '/app/models/CategoryPost.php';
require_once BASE_PATH . '/app/helpers/admin_auth.php';

class PostController
{
    private Post $model;
    private CategoryPost $categoryModel;

    public function __construct()
    {
        checkAdminLogin();
        $this->model = new Post();
        $this->categoryModel = new CategoryPost();
    }

    public function index()
    {
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;
    
        $items = $this->model->paginate($limit, $offset);
        $total = $this->model->countAll();
    
        $lastPage    = (int)ceil($total / $limit);
        $currentPage = $page;
    
        require BASE_PATH . '/app/views/admin/post/index.php';
    }

    public function create()
    {
        $categories = $this->categoryModel->getActive();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = trim($_POST['name'] ?? '');
            $str = mb_strtolower($name, 'UTF-8'); // Bỏ dấu
            $viet = [
                'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
                'd' => 'đ',
                'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
                'i' => 'í|ì|ỉ|ĩ|ị',
                'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
                'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
                'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            ];
            foreach ($viet as $ascii => $regex) {
                $str = preg_replace("/($regex)/u", $ascii, $str);
            }

            $slug = preg_replace('/[^a-z0-9]+/', '-', $str);

            $slug = $slug . '-' . time();

            $description = trim($_POST['description'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $category_post_id = (int)($_POST['category_post_id'] ?? 0);


            // upload ảnh
            $image = $this->uploadMainImage($_FILES['image'] ?? []);
                $this->model->create([
                    'name' => $name ?? '',
                    'slug' => $slug ?? '',
                    'image' => $image ?? '',
                    'description' => $description ?? '',
                    'content' => $content ?? '',
                    'category_post_id' => $category_post_id ?? '',
                    'active'=>1,
                ]);

                $_SESSION['success'] = 'Thêm bài viết thành công';
                header('Location: ' . BASE_URL . 'admin/post');
                exit;
            
        }

        require BASE_PATH . '/app/views/admin/post/add.php';
    }



    public function edit($id)
    {
        $item = $this->model->find($id);
        if (!$item) {
            header('Location: ' . BASE_URL . 'admin/post');
            exit;
        }
    
        $categories = $this->categoryModel->getActive();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    



            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $category_post_id = (int)($_POST['category_post_id'] ?? 0);
            $active = (int)($_POST['active'] ?? 1);


         

            $image = $this->uploadMainImage($_FILES['image'] ?? [], $item['image']);
    
    
            if (!empty($_POST['slug'])) {
                $str = mb_strtolower(trim($name), 'UTF-8');

                $viet = [
                    'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
                    'd' => 'đ',
                    'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
                    'i' => 'í|ì|ỉ|ĩ|ị',
                    'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
                    'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
                    'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
                ];
            
                foreach ($viet as $ascii => $regex) {
                    $str = preg_replace("/($regex)/u", $ascii, $str);
                }
            
                $str = preg_replace('/[^a-z0-9]+/', '-', $str);
                $str = trim($str, '-');
            
                $slug= $str . '-' . time();
            } else {
                $str = mb_strtolower(trim($name), 'UTF-8');

                $viet = [
                    'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
                    'd' => 'đ',
                    'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
                    'i' => 'í|ì|ỉ|ĩ|ị',
                    'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
                    'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
                    'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
                ];
            
                foreach ($viet as $ascii => $regex) {
                    $str = preg_replace("/($regex)/u", $ascii, $str);
                }
            
                $str = preg_replace('/[^a-z0-9]+/', '-', $str);
                $str = trim($str, '-');
            
                $slug= $str . '-' . time();
            }
           
            $this->model->update($id, [
                'name' => $name,
                'slug' => $slug,
                'image' => $image,
                'description' => $description,
                'content' => $content,
                'category_post_id' => $category_post_id,
                'active' => $active
            ]);
    
            $_SESSION['success'] = 'Cập nhật bài viết thành công';
            header('Location: ' . BASE_URL . 'admin/post');
            exit;
        }
    
        require BASE_PATH . '/app/views/admin/post/edit.php';
    }
    

    public function delete($id)
    {
        $this->model->delete($id);

        $_SESSION['success'] = 'Ẩn bài viết thành công';
        header('Location: ' . BASE_URL . 'admin/post');
        exit;
    }

    private function uploadMainImage($file, $oldImage = '')
{
    if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return $oldImage;
    }

    $uploadDir = BASE_PATH . '/public/uploads/posts/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowedExt)) {
        return $oldImage;
    }

    // if ($file['size'] > 5 * 1024 * 1024) {
    //     return $oldImage;
    // }

    $fileName = uniqid('post_', true) . '.' . $ext;
    $target = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $target)) {

        if ($oldImage && file_exists(BASE_PATH . '/public/' . $oldImage)) {
            unlink(BASE_PATH . '/public/' . $oldImage);
        }

        return 'uploads/posts/' . $fileName;
    }

    return $oldImage;
}


}
