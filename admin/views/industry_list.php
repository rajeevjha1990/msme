<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Industries</title>
</head>
<body>
<div class="content">
    <h2>Industries</h2>
     <div style="text-align:right;">
        <a href="index.php?action=new_industry" class="new-btn">+ New Industry</a>
    </div>
    <table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
              $i=1;
              foreach($industries as $row){
              ?>
                <tr>
                  <td><?php echo $i++; ?></td>
                    <td><?php echo $row['code']; ?></td>
                    <td><?php echo $row['name']; ?></td>
            <td>
              <a href="index.php?action=edit_industry&id=<?php echo $row['id']; ?>"   class="btn btn-success btn-sm">Edit</a>
              <a href="javascript:void(0)" onclick="deleteindusty(<?php echo $row['id']; ?>)" class="action-link remove-link btn btn-danger">Remove</a>
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
function deleteindusty(industryid) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This Industry will be permanently deleted ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo SITE_URL; ?>admin/index.php?action=remove_industry",
                type: "POST",
                data: { industryid: industryid },
                dataType: "json",
                success: function(response) {
                      console.log("AJAX Response:", response);
                    if (response.success) {
                        $("#row-" + industryid).remove();
                        Swal.fire(
                            'Deleted!',
                            'Industry has been removed.',
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
