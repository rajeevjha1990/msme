<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= !empty($industryid) ? "Edit Category" : "New Category" ?></title>
</head>
<body>
  <div class="container mt-5">
    <div class="content">
      <h2><?= !empty($industryid) ? "Edit Category" : "New Category" ?></h2>
    <?php
    if(isset($industry)){
      $industryid = $industry['id'];
      $code = $industry['code'];
      $name = $industry['name'];
      } else {
          $industryid = "";
          $code = "";
          $name = "";
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

      <form method="post" action="index.php?action=add_industry" enctype="multipart/form-data">
      <input value="<?php echo $industryid; ?>" type="hidden" name="id" id="id" class="form-control"/>

      <div class="row g-3">
        <div class="col-md-6">
          <label for="name" class="form-label">Code</label>
          <input value="<?php echo $code; ?>" type="text" name="code" id="code" class="form-control"/>
        </div>

        <div class="col-md-6">
          <label for="name" class="form-label">Name</label>
          <input value="<?php echo $name; ?>" type="text" name="name" id="name" class="form-control" />
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
