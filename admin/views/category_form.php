<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>New Category</title>
</head>
<body>
  <div class="container mt-5">
    <div class="content">
      <h2>New Slide</h2>
    <?php
    if(isset($category)){
      $id = $category['id'];
      $name = $category['name'];
      $slug = $category['slug'];
      $icon = $category['icon'];
      } else {
          $id = "";
          $name = "";
          $slug = "";
          $icon = "";
      }

      $error = $_SESSION['error'] ?? '';
      $success = $_SESSION['success'] ?? '';
      unset($_SESSION['error'], $_SESSION['success']);
      ?>
      <?php if (!empty($error)) { ?>
        <div class="alert alert-danger mb-3">
          <?php echo $error; ?>
        </div>
      <?php } ?>

      <?php if (!empty($success)) { ?>
        <div class="alert alert-success">
          <?php echo $success; ?>
        </div>
      <?php } ?>

      <form method="post" action="index.php?action=add_category" enctype="multipart/form-data">
      <input value="<?php echo $id; ?>" type="hidden" name="id" id="id" class="form-control"/>

      <div class="row g-3">
        <div class="col-md-6">
          <label for="name" class="form-label">Name</label>
          <input value="<?php echo $name; ?>" type="text" name="name" id="name" class="form-control"/>
        </div>

        <div class="col-md-6">
          <label for="slug" class="form-label">Slug</label>
          <input value="<?php echo $slug; ?>" type="text" name="slug" id="slug" class="form-control" />
        </div>

        <div class="col-md-6">
          <label for="icon" class="form-label">Icon</label>

          <?php if(!empty($icon)) { ?>
            <!-- Show old image preview -->
            <div class="mb-2">
              <img src="<?php echo SITE_URL . 'admin/uploads/' . $icon; ?>"
                   alt="icon"
                   style="width:60px; height:60px; object-fit:contain; border:1px solid #ddd; padding:4px;">
            </div>
            <!-- Hidden field for old image -->
            <input type="hidden" name="old_icon" value="<?php echo $icon; ?>">
          <?php } ?>
          <!-- New upload option -->
          <input type="file" name="icon" accept="image/*">
        </div>
        <div class="col-12 text-center mt-3">
          <button type="submit" class="btn btn-primary w-100">Save</button>
        </div>
      </div>
    </form>
    </div>
  </div>
</body>
</html>
