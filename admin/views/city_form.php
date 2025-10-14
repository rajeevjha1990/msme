<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= !empty($cityid) ? "Edit City" : "New City" ?></title>
</head>
<body>
  <div class="container mt-5">
    <div class="content">
      <h2><?= !empty($cityid) ? "Edit City" : "New City" ?></h2>
    <?php
    if (isset($city) && is_array($city)) {
      $cityid     = $city['cityid'] ?? '';
      $cityname   = $city['city'] ?? '';
      $stateid    = $city['stateid'] ?? $city['stateid'] ?? '';
      $isdistrict = $city['isdistrict'] ?? '';
      $citycodeno = $city['pincode'] ?? $city['pincode'] ?? '';
    } else {
      $cityid     = "";
      $cityname   = "";
      $stateid    = "";
      $isdistrict = "n";
      $citycodeno = "";
    }

    $error = $_SESSION['error'] ?? '';
    $success = $_SESSION['success'] ?? '';
    unset($_SESSION['error'], $_SESSION['success']);
    ?>

    <?php if (!empty($error)) { ?>
      <div class="alert alert-danger mb-3">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php } ?>

    <?php if (!empty($success)) { ?>
      <div class="alert alert-success">
        <?php echo htmlspecialchars($success); ?>
      </div>
    <?php } ?>

    <form method="post" action="index.php?action=add_city" enctype="multipart/form-data">
      <input value="<?php echo htmlspecialchars($cityid); ?>" type="hidden" name="cityid" id="cityid" class="form-control"/>

      <div class="row g-3">
        <!-- STATE SELECT -->
        <div class="col-md-12">
          <label for="state" class="form-label">State</label>
          <select name="state" id="state" class="form-control" required>
            <option value="">-- Select State --</option>
            <?php
            if (!empty($states) && is_array($states)) {
              foreach ($states as $value) {
                // If $value is object or array, adjust accordingly
                $sid   = isset($value->stateid) ? $value->stateid : ($value['stateid'] ?? '');
                $sname = isset($value->state)    ? $value->state    : ($value['state'] ?? '');
                $selected = ($stateid !== '' && (string)$stateid === (string)$sid) ? 'selected' : '';
                echo '<option value="'.htmlspecialchars($sid).'" '.$selected.'>'.htmlspecialchars($sname).'</option>';
              }
            } else {
              echo '<option value="">No states found</option>';
            }
            ?>
          </select>
        </div>

        <!-- CITY NAME -->
        <div class="col-md-4">
          <label for="city" class="form-label">City</label>
          <input value="<?php echo htmlspecialchars($cityname); ?>" type="text" name="city" id="city" class="form-control" required />
        </div>

        <!-- IS DISTRICT -->
        <div class="col-md-4">
          <label for="isdistrict" class="form-label">Is District</label>
          <select name="isdistrict" id="isdistrict" class="form-control" required>
            <option value="y" <?= (strtolower($isdistrict) === 'y') ? 'selected' : '' ?>>Yes</option>
            <option value="n" <?= (strtolower($isdistrict) === 'y') ? '' : 'selected' ?>>No</option>
          </select>
        </div>

        <!-- PINCODE -->
        <div class="col-md-4">
          <label for="citycode" class="form-label">PinCode</label>
          <input value="<?php echo htmlspecialchars($citycodeno); ?>" type="text" name="citycode" id="citycode" class="form-control" />
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
