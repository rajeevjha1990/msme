</div> <!-- end main-container -->
<footer class="footer">
    <p>&copy; <?= date("Y") ?> MSME</p>
</footer>
<script src="<?php echo SITE_URL; ?>admin/assets/js/jquery-3.6.0.min.js"></script>
<script src="<?php echo SITE_URL; ?>admin/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo SITE_URL; ?>admin/assets/js/dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>admin/assets/js/sidebar.js"></script>
<script src="<?php echo SITE_URL; ?>admin/assets/js/select2.min.js"></script>
<script src="<?php echo SITE_URL; ?>admin/assets/js/jquery.dataTables.min.js"></script>

</body>
</html>
<script>
  $(document).ready(function() {
    $('#user').select2({
      placeholder: "Select a user",
      allowClear: true,
      width: '100%',
    });
  });
$('#myTable').DataTable({
    "pageLength": 5,
    "lengthMenu": [5, 10, 25, 50, 100],
    "searching": true,
    "ordering": true,
    "columnDefs": [
      {
        "targets": 3, // status column
        "render": function (data) {
          if (data == "1") return '<span class="status-open">Open</span>';
          if (data == "0") return '<span class="status-closed">Closed</span>';
          if (data == "2") return '<span class="status-pending">Pending</span>';
          return data;
        }
      }
    ],
    "rowCallback": function(row, data, index) {
      if (data[3].includes("Open")) {
        $(row).css("background-color", "#eaffea");
      }
      else if (data[3].includes("Closed")) {
        $(row).css("background-color", "#ffecec");
      }
    }
});
$(document).ready(function() {
    $('#user').change(function() {
        var userId = $(this).val();
        if (userId !== '') {
            var url = '<?php echo SITE_URL; ?>admin/index.php?action=userWalletBalance';

            $.ajax({
                url: url,
                type: 'POST',
                data: { user_id: userId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#wallet_balance').text(response.balance);
                    } else {
                        $('#wallet_balance').text('0.00');
                    }
                },
                error: function(xhr, status, error) {
                    $('#wallet_balance').text('0.00');
                }
            });
        } else {
            $('#wallet_balance').text('0.00');
        }
    });
});
$(document).on("blur", ".editable", function () {
    let Id = $(this).data("id");
    let newValue = $(this).text().trim();
    $.ajax({
        url: "<?php echo SITE_URL; ?>admin/index.php?action=update_single_swap",
        type: "POST",
        data: { id: Id, swap: newValue },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $("#messageBox").html(
                    `<div class="alert-success">${response.message}</div>`
                );
            } else {
                $("#messageBox").html(
                    `<div class="alert-danger">${response.message}</div>`
                );
            }
        },
        error: function (xhr, status, error) {
            $("#messageBox").html(
                `<div class="alert-danger">Error: ${error}</div>`
            );
        }
    });
});
</script>
