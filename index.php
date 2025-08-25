<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hit Machine - Modern Interface</title>
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #eef2ff, #e0e7ff);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    @keyframes hit-bounce {
      0% { transform: scale(1); }
      40% { transform: scale(1.08); }
      100% { transform: scale(1); }
    }
    .hit-animate { animation: hit-bounce 0.5s ease-in-out; }

    .modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.5);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 50;
    }
    .modal-backdrop.visible { display: flex; }
    #gifArea {
  background: url('whitebackground.png') center/cover no-repeat;
}

  </style>
</head>
<body>

  <!-- Card Container -->
  <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 text-center">
    <h1 class="text-4xl font-extrabold text-black mb-2">WHIP A NIGGA</h1>
    <p class="text-gray-500 mb-6"> </p>

    <!-- Image/GIF Area -->
    <div id="gifArea" class="relative h-64 flex items-center justify-center bg-gray-100 rounded-2xl shadow-inner overflow-hidden mb-8 border border-gray-300">
      <img id="hitImage" src="first.png" alt="Hit Animation" class="max-h-full max-w-full object-contain rounded-lg">
    </div>

    <!-- Counter -->
    <div class="mb-8">
      <span class="block text-sm font-medium text-gray-500 tracking-wider uppercase">Total Whips</span>
      <p id="count-value" class="text-6xl font-extrabold text-black mt-2">0</p>
    </div>

    <!-- Buttons -->
    <div class="flex flex-col gap-4">
      <button id="hitBtn" 
        class="bg-black hover:bg-gray-800 text-white font-bold py-4 rounded-xl shadow-lg transform transition-all hover:scale-105 active:scale-95">
        WHIP
      </button>
      <button id="resetBtn" 
        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 rounded-xl transition-all">
        RESET
      </button>
    </div>
  </div>

  <!-- Audio -->
  <audio id="hitSound" src="whip.mp3" preload="auto"></audio>

  <!-- PIN Modal -->
  <div id="pinModal" class="modal-backdrop">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-80 text-center">
      <h2 class="text-2xl font-bold mb-3">Reset Whips</h2>
      <p class="text-gray-500 mb-6">Enter your PIN to confirm.</p>
      <input id="pinInput" type="password" placeholder="••••" 
        class="w-full border rounded-lg px-4 py-3 text-lg text-center mb-6 focus:ring-2 focus:ring-black outline-none">
      <div class="flex gap-3">
        <button id="submitPinBtn" 
          class="flex-1 bg-black hover:bg-gray-800 text-white font-bold py-2 rounded-lg">Submit</button>
        <button id="cancelPinBtn" 
          class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 rounded-lg">Cancel</button>
      </div>
    </div>
  </div>

  <!-- Message Box -->
  <div id="messageBox" class="modal-backdrop">
    <div class="bg-white p-6 rounded-2xl shadow-lg w-80 text-center">
      <p id="messageText" class="text-lg mb-6"></p>
      <button id="messageOkBtn" 
        class="w-full bg-black hover:bg-gray-800 text-white font-bold py-2 rounded-lg">OK</button>
    </div>
  </div>

  <script>
    const hitBtn = document.getElementById("hitBtn");
    const resetBtn = document.getElementById("resetBtn");
    const hitImage = document.getElementById("hitImage");
    const counterValueDisplay = document.getElementById("count-value");
    const hitSound = document.getElementById("hitSound");
    const pinModal = document.getElementById("pinModal");
    const pinInput = document.getElementById("pinInput");
    const submitPinBtn = document.getElementById("submitPinBtn");
    const cancelPinBtn = document.getElementById("cancelPinBtn");
    const messageBox = document.getElementById("messageBox");
    const messageText = document.getElementById("messageText");
    const messageOkBtn = document.getElementById("messageOkBtn");

    // Replace with your files
    const idleImageSrc = "first.png";
    const hitGifSrc = "7UqM.gif";
    const endImageSrc = "last.png";
    const gifDuration = 500; // adjust to your gif length

    function showMessage(msg) {
      messageText.textContent = msg;
      messageBox.classList.add("visible");
    }

    async function loadCount() {
      let res = await fetch("counter.php?action=get");
      let data = await res.json();
      counterValueDisplay.textContent = data.count;
    }

    hitBtn.addEventListener("click", async () => {
      hitBtn.disabled = true;
      let res = await fetch("counter.php?action=hit");
      let data = await res.json();
      counterValueDisplay.textContent = data.count;

      hitImage.src = hitGifSrc + "?rand=" + Math.random();
      hitImage.classList.add("hit-animate");

      hitSound.currentTime = 0;
      hitSound.play();

      setTimeout(() => {
        hitImage.src = endImageSrc;
        hitImage.classList.remove("hit-animate");
        hitBtn.disabled = false;
      }, gifDuration);
    });

    resetBtn.addEventListener("click", () => {
      pinInput.value = "";
      pinModal.classList.add("visible");
      pinInput.focus();
    });

    submitPinBtn.addEventListener("click", async () => {
      let pin = pinInput.value;
      let res = await fetch("counter.php?action=reset&pin=" + pin);
      let data = await res.json();
      if (data.success) {
        counterValueDisplay.textContent = "0";
        showMessage("Whips reset successfully!");
      } else {
        showMessage("Wrong PIN!");
      }
      pinModal.classList.remove("visible");
    });

    cancelPinBtn.addEventListener("click", () => pinModal.classList.remove("visible"));
    messageOkBtn.addEventListener("click", () => messageBox.classList.remove("visible"));

    document.addEventListener("DOMContentLoaded", loadCount);
  </script>
</body>
</html>
