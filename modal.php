<!-- modal.php -->
<style>
.modal-overlay {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.5);
  display: flex; align-items: center; justify-content: center;
  z-index: 9999;
}
.modal-box {
  background: #fff; padding: 20px 25px; border-radius: 12px;
  width: 350px; text-align: center; position: relative;
  box-shadow: 0 10px 25px rgba(0,0,0,0.3);
  animation: fadeIn 0.3s ease-in-out;
}
.modal-box h2 { margin: 0 0 10px; font-size: 20px; }
.modal-box p { font-size: 14px; color: #444; margin-bottom: 20px; }
.modal-box button {
  background: #0aa54a; color: #fff; border: none;
  padding: 10px 18px; border-radius: 8px; font-weight: bold;
  cursor: pointer; transition: background .2s;
}
.modal-box button:hover { background: #07833b; }
.modal-close {
  position: absolute; top: 8px; right: 12px;
  font-size: 18px; cursor: pointer; color: #666;
}
@keyframes fadeIn { from{opacity:0; transform: scale(.9);} to{opacity:1; transform:scale(1);} }
</style>

<div id="globalModal" class="modal-overlay" style="display:none;">
  <div class="modal-box">
    <span class="modal-close">&times;</span>
    <h2 id="modalTitle"></h2>
    <p id="modalMessage"></p>
    <button id="modalBtn">OK</button>
  </div>
</div>

<script>
let modalStartTime = null;

function showModal(type, message) {
  const modal = document.getElementById("globalModal");
  const title = document.getElementById("modalTitle");
  const msg = document.getElementById("modalMessage");
  const btn = document.getElementById("modalBtn");

  if(type === "loading"){
    title.innerText = "Submitting...";
    msg.innerText = message || "Please wait while we process your request.";
    btn.style.display = "none"; // hide button during loading
    modalStartTime = Date.now(); // mark start time
  } else if(type === "success"){
    title.innerText = "Success ✅";
    msg.innerText = message || "Your form was submitted successfully!";
    btn.style.display = "inline-block";
  } else if(type === "error"){
    title.innerText = "Error ❌";
    msg.innerText = message || "Something went wrong.";
    btn.style.display = "inline-block";
  }

  modal.style.display = "flex";

  // close or reload
  document.querySelector(".modal-close").onclick = () => { location.reload(); };
  btn.onclick = () => { location.reload(); };
}

// Ensure modal stays for at least 5 sec
function showAfterDelay(nextType, msg) {
  const elapsed = Date.now() - modalStartTime;
  const waitTime = Math.max(0, 5000 - elapsed); // 5s - already elapsed
  setTimeout(() => showModal(nextType, msg), waitTime);
}

function hideModal(){ document.getElementById("globalModal").style.display="none"; }
</script>

