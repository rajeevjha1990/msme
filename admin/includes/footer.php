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
<script src="<?php echo SITE_URL; ?>admin/assets/js/xausd_livedata.js"></script>

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
/*Swap update function*/
function applySwapToOpen() {
    let value = document.getElementById("globalSwap").value;
    if(value === ""){
        alert("Please enter a swap value");
        return;
    }

    let form = document.createElement("form");
    form.method = "POST";
    form.action = "<?php echo SITE_URL; ?>admin/index.php?action=update_swap";

    let input = document.createElement("input");
    input.type = "hidden";
    input.name = "swap";
    input.value = value;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
function fetchUnreadCount() {
  $.ajax({
    url: "<?php echo SITE_URL; ?>admin/index.php?action=getUnreadTicketCountAjax",
    type: "POST",
    dataType: "json",
    success: function(response) {
      $("#unreadCount").text(response.count);
      $("#sidebarUnreadCount").text(response.count);
    }
  });
}
fetchUnreadCount();
setInterval(fetchUnreadCount, 5000);

function loadNotificationCount() {
    $.ajax({
        url: "<?php echo SITE_URL; ?>admin/index.php?action=notification_count",
        method: "GET",
        dataType: "json",
        success: function(res) {
            console.log("Notification Response:", res); // debugging

            let count = res.count ?? 0;

            if (count > 0) {
                $("#notifCount").text(count).show();
            } else {
                $("#notifCount").hide();
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });
}

// First load
$(document).ready(function(){
    loadNotificationCount();
    setInterval(loadNotificationCount, 10000);
});
function markAsRead(card){
    var notifId = $(card).data('id'); // data-id से id लो
    if(!notifId) return;

    $.ajax({
        url: "<?php echo SITE_URL; ?>admin/index.php?action=mark_notification_read",
        method: "POST",
        data: { id: notifId },
        dataType: "json",
        success: function(res){
            if(res.success){
                // Card fade out
                $(card).fadeOut(300, function(){ $(this).remove(); });

                // Notification count update
                var countElem = $('#notifCount');
                var count = parseInt(countElem.text()) || 0;
                count = Math.max(count - 1, 0);
                if(count > 0){
                    countElem.text(count).show();
                } else {
                    countElem.hide();
                }
            } else {
                alert('Failed to mark as read');
            }
        },
        error: function(xhr, status, error){
            console.error(error);
            alert('AJAX error!');
        }
    });
}

</script>
