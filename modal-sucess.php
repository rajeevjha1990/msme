<!-- modal-success.php -->
<div id="successModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>🎉 Registration Successful!</h2>
    <p>Your account has been created successfully.</p>
    <button id="proceedPaymentBtn">Proceed to Payment</button>
  </div>
</div>

<style>
.modal {
  display: none; 
  position: fixed; 
  z-index: 9999; 
  left: 0; 
  top: 0; 
  width: 100%; 
  height: 100%; 
  background: rgba(0,0,0,0.5); 
}
.modal-content {
  background: #fff;
  border-radius: 12px;
  max-width: 400px;
  margin: 15% auto;
  padding: 20px;
  text-align: center;
  animation: fadeIn 0.4s;
}
.modal-content h2 {
  color: #28a745;
  margin-bottom: 10px;
}
.modal-content button {
  background: #f26522;
  color: #fff;
  border: none;
  padding: 12px 20px;
  border-radius: 25px;
  cursor: pointer;
}
.modal-content button:hover {
  background: #d9531e;
}
.close {
  float: right;
  cursor: pointer;
  font-size: 20px;
}
@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity: 1;}
}
</style>

<script>
function showSuccessModal(redirectUrl) {
  var modal = document.getElementById("successModal");
  var span = document.getElementsByClassName("close")[0];
  var btn = document.getElementById("proceedPaymentBtn");

  modal.style.display = "block";

  span.onclick = function() {
    modal.style.display = "none";
    window.location.href = redirectUrl;
  }
  btn.onclick = function() {
    window.location.href = redirectUrl;
  }
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
      window.location.href = redirectUrl;
    }
  }
}
</script>
