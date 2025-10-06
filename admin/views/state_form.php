<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= !empty($stateid) ? "Edit State" : "New State" ?></title>
</head>
<body>
  <div class="container mt-5">
    <div class="content">
      <h2><?= !empty($stateid) ? "Edit State" : "New State" ?></h2>
    <?php
    if(isset($state)){
      $stateid = $state['stateid'];
      $statename = $state['state']?? '' ;
      $szoneid = $state['zoneid']?? '' ;
      $statecodeno = $state['statecode']?? '' ;
      } else {
          $stateid = "";
          $statename = "";
          $szoneid = "";
          $statecodeno = "";
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

      <form method="post" action="index.php?action=add_state" enctype="multipart/form-data">
      <input value="<?php echo $stateid; ?>" type="hidden" name="stateid" id="stateid" class="form-control"/>

      <div class="row g-3">
        <div class="col-md-4">
          <label for="state" class="form-label">State</label>
          <input value="<?php echo $statename; ?>" type="text" name="state" id="state" class="form-control"/>
        </div>

        <div class="col-md-4">
          <label for="zoneid" class="form-label">Zone Id</label>
          <input value="<?php echo $szoneid; ?>" type="text" name="zoneid" id="zoneid" class="form-control" />
        </div>
        <div class="col-md-4">
          <label for="statecode" class="form-label">State Code</label>
          <input value="<?php echo $statecodeno; ?>" type="text" name="statecode" id="statecode" class="form-control" />
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
