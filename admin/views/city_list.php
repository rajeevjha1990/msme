<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cities</title>
</head>
<body>
<div class="content">
    <h2>Cities</h2>
     <div style="text-align:right;">
        <a href="index.php?action=new_city" class="new-btn">+ New City</a>
    </div>
    <table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>City</th>
                <th>PinCode</th>
                <th>State</th>
                <th>Is District</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i=1;
            foreach($cities as $row){
            $isDistrict = ($row['isdistrict'] === 'Y') ? 'Yes' : 'No';

               ?>
                <tr>
                  <td>
                    <?php echo $i++; ?>
                  </td>
                    <td><?php echo $row['city']; ?></td>
                    <td><?php echo $row['pincode']; ?></td>
                    <td><?php echo $row['state']; ?></td>
                    <td><?php echo $isDistrict; ?></td>
            <td>
              <a href="index.php?action=edit_city&id=<?php echo $row['cityid']; ?>"   class="btn btn-success btn-sm">Edit</a>
              <a href="javascript:void(0)" onclick="deletecity(<?php echo $row['cityid']; ?>)" class="action-link remove-link btn btn-danger">Remove</a>
              </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function deletestate(stateid) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This state will be permanently deleted ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo SITE_URL; ?>admin/index.php?action=remove_state",
                type: "POST",
                data: { stateid: stateid },
                dataType: "json",
                success: function(response) {
                      console.log("AJAX Response:", response);
                    if (response.success) {
                        $("#row-" + stateid).remove();
                        Swal.fire(
                            'Deleted!',
                            'State has been removed.',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message || 'Something went wrong.',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                      console.log("AJAX ERROR RESPONSE:", xhr.responseText); // 🔍 debug output

                    Swal.fire(
                        'Error!',
                        'AJAX error: ' + error,
                        'error'
                    );
                }
            });
        }
    });
}
</script>
