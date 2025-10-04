<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users</title>
</head>
<body>
<div class="content">
    <h2>Business Categories</h2>
     <div style="text-align:right;">
        <a href="index.php?action=new_category" class="new-btn">+ New Category</a>
    </div>
    <table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Slug</th>
                <th>Icon</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($categories as $row){
               ?>
                <tr>
                  <td>
                    <?php echo $row['id']; ?>
                  </td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['slug']; ?></td>
              <td>
                <img src="<?php echo SITE_URL . 'admin/views/uploads/' . $row['icon']; ?>"
                 alt="<?php echo $row['name']; ?>"
                 style="width:40px; height:40px; object-fit:contain;">
            </td>
            <td>
              <a href="index.php?action=edit_category&id=<?php echo $row['id']; ?>"   class="btn btn-success btn-sm">Edit</a>
              <a href="javascript:void(0)" onclick="deletecategory(<?php echo $row['id']; ?>)" class="action-link remove-link btn btn-danger">Remove</a>
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
function deletecategory(categoryid) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This category will be permanently deleted ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "<?php echo SITE_URL; ?>admin/index.php?action=remove_category",
                type: "POST",
                data: { categoryid: categoryid },
                dataType: "json",
                success: function(response) {
                      console.log("AJAX Response:", response);
                    if (response.success) {
                        $("#row-" + categoryid).remove();
                        Swal.fire(
                            'Deleted!',
                            'Category has been removed.',
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
