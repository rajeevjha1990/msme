<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../Models/CommonModel.php';
require_once __DIR__ . '/../config/config.php';

// SITE_URL already defined check
if (!defined('SITE_URL')) {
    define("SITE_URL", "http://localhost/msme/");
}

class CommonController extends BaseController
{
    private $commonModel;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->commonModel = new CommonModel($db);
    }

    public function businessCategories()
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: " . SITE_URL . "admin/index.php?action=login");
            exit;
        }
        $loggedAdmin = $this->getLoggedAdmin();
        $categories = $this->commonModel->business_categories();

        include __DIR__ . "/../includes/header.php";
        include __DIR__ . "/../includes/sidebar.php";
        include __DIR__ . '/../views/business_categories.php';
        include __DIR__ . "/../includes/footer.php";
    }
    public function new_category()
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: " . SITE_URL . "admin/index.php?action=login");
            exit;
        }
        $loggedAdmin = $this->getLoggedAdmin();
        $categories = $this->commonModel->business_categories();
        include __DIR__ . "/../includes/header.php";
        include __DIR__ . "/../includes/sidebar.php";
        include __DIR__ . '/../views/category_form.php';
        include __DIR__ . "/../includes/footer.php";
    }
    public function edit_category($categoryid)
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: " . SITE_URL . "admin/index.php?action=login");
            exit;
        }
        $loggedAdmin = $this->getLoggedAdmin();
        $category = $this->commonModel->business_category($categoryid);
        include __DIR__ . "/../includes/header.php";
        include __DIR__ . "/../includes/sidebar.php";
        include __DIR__ . '/../views/category_form.php';
        include __DIR__ . "/../includes/footer.php";
    }
    public function test_upload() {
    require_once __DIR__ . '/../views/test_upload.php';
}

public function add_category()
{
    $loggedAdmin = $this->getLoggedAdmin();
    unset($_SESSION['error'], $_SESSION['success']);
    $errors = [];

    $id   = trim($_POST['id'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');

    // Validation
    if (!$name) {
        $errors[] = "Category name is required.";
    }
    if (!$slug) {
        $errors[] = "Slug is required.";
    }

    $iconFileName = "";

    // Ensure upload directory exists
    if (!file_exists(UPLOAD_DIR) && !mkdir(UPLOAD_DIR, 0777, true)) {
        $errors[] = "Failed to create upload directory.";
    }

    if (!is_writable(UPLOAD_DIR)) {
        $errors[] = "Upload directory is not writable.";
    }

    // Handle file upload
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] != UPLOAD_ERR_NO_FILE) {
        $fileTmp  = $_FILES['icon']['tmp_name'];
        $fileName = $_FILES['icon']['name'];
        $fileSize = $_FILES['icon']['size'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
        $minSize = 50 * 1024;      // 50KB
        $maxSize = 2 * 1024 * 1024; // 2MB

        // Type validation
        if (!in_array($fileExt, $allowedExt)) {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }

        // Size validation
        if ($fileSize < $minSize || $fileSize > $maxSize) {
            $errors[] = "Image size must be between 50KB and 2MB.";
        }

        if (empty($errors)) {
            $iconFileName = uniqid("cat_") . "." . $fileExt;
            $destPath = UPLOAD_DIR . $iconFileName;

            if (!move_uploaded_file($fileTmp, $destPath)) {
                $errors[] = "Failed to upload image.";
            }
        }
    } elseif (!$id) {
        $errors[] = "Category icon is required.";
    }

    // Insert / Update category
    if (empty($errors)) {
        $data = [
            'name' => $name,
            'slug' => $slug,
        ];
        if ($iconFileName) {
            $data['icon'] = $iconFileName;
        }

        if ($id) {
            $resp = $this->commonModel->updateCategory($id, $data);
            if ($resp) {
                $_SESSION['success'] = "Category updated successfully!";
                header("Location: index.php?action=bcategories");
                exit;
            } else {
                $_SESSION['error'] = "Update failed! Please try again.";
                header("Location: index.php?action=edit_category&id=$id");
                exit;
            }
        } else {
            $resp = $this->commonModel->insertCategory($data);
            if ($resp) {
                $_SESSION['success'] = "Category added successfully!";
                header("Location: index.php?action=bcategories");
                exit;
            } else {
                $_SESSION['error'] = "Insert failed! Please try again.";
                header("Location: index.php?action=new_category");
                exit;
            }
        }
      } else {
          $_SESSION['error'] = implode("<br>", $errors);
          if ($id) {
              header("Location: index.php?action=edit_category&id=$id");
          } else {
              header("Location: index.php?action=new_category");
          }
          exit;
      }
  }
}
?>
