function callUploadScript() {
  var isAdvancedUpload = (function () {
    var div = document.createElement("div");
    return (
      ("draggable" in div || ("ondragstart" in div && "ondrop" in div)) &&
      "FormData" in window &&
      "FileReader" in window
    );
  })();

  let fileInput = document.querySelector(".default-file-input");
  let uploadButton = document.querySelector(".upload-button");
  let uploadedFilesContainer = document.querySelector(
    ".upload-files-container"
  );
  let overlay = document.getElementById("overlay");
  let dragFileArea = document.querySelector(".drag-file-area");
  let dragCounter = 0;

  function showOverlay() {
    overlay.style.display = "flex";
    overlay.style.flexDirection = "column";
  }

  function hideOverlay() {
    overlay.style.display = "none";
  }

  ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
    window.addEventListener(
      eventName,
      function (e) {
        e.preventDefault();
        e.stopPropagation();
      },
      false
    );
  });

  window.addEventListener("dragenter", function (e) {
    dragCounter++;
    showOverlay();
  });

  window.addEventListener("dragleave", function (e) {
    dragCounter--;
    if (dragCounter === 0) {
      hideOverlay();
    }
  });

  window.addEventListener("drop", function (e) {
    dragCounter = 0;
    hideOverlay();
    processFiles(e.dataTransfer.files);
  });

  if (typeof fileInput !== "undefined" && fileInput !== null) {
    // If fileInput exists, attach the event listener
    fileInput.addEventListener("change", function () {
      processFiles(fileInput.files);
    });
  }

  if (typeof dragFileArea !== "undefined" && dragFileArea !== null) {
    // If dragFileArea exists, attach the event listener
    dragFileArea.addEventListener("click", () => {
      let input = document.createElement("input");
      input.type = "file";
      input.onchange = (_this) => {
        let files = Array.from(input.files);
        processFiles(files);
      };
      input.click();
    });
  }

  let allFiles = []; // Array to store all files

  function processFiles(files) {
    Array.from(files).forEach((file) => {
      allFiles.push(file); // Add new file to the array
      processFile(file);
    });
  }

  function processFile(file) {
    let fileBlock = document.createElement("div");
    fileBlock.className = "file-block";
    fileBlock.style.display = "flex";
    fileBlock.innerHTML = `
    <div class="file-info">
     <span class="material-icons-outlined file-icon">description</span>
     <span class="file-name">${file.name}</span> 
     <span class="file-size badge rounded-pill">${(file.size / 1024).toFixed(
       1
     )} KB</span>
    </div>
    <span class="material-icons remove-file-icon" onclick="removeFile(this)">delete</span>
    <div class="progress-bar" style="width: 0; height: 5px; bottom: 0; position: absolute;"></div>`;
    uploadedFilesContainer.appendChild(fileBlock);
  }

  window.removeFile = function (element) {
    let fileName = element
      .closest(".file-block")
      .querySelector(".file-name").textContent;
    allFiles = allFiles.filter((file) => file.name !== fileName); // Remove file from the array
    element.closest(".file-block").remove();
  };
  if (typeof uploadButton !== "undefined" && uploadButton !== null) {
    // If uploadButton exists, attach the event listener
    uploadButton.addEventListener("click", function () {
      // Remove the behavior that opens the file input
      // Instead, call the function to start uploading files
      uploadFiles();
    });
  }

  window.addEventListener("paste", function (e) {
    const items = (e.clipboardData || e.originalEvent.clipboardData).items;
    let files = [];
    for (let item of items) {
      if (item.kind === "file") {
        let file = item.getAsFile();
        files.push(file);
      }
    }
    if (files.length > 0) {
      processFiles(files);
    }
  });

  // Function to simulate the upload process
  function uploadFiles() {
    let fileBlocks = uploadedFilesContainer.querySelectorAll(".file-block");
    fileBlocks.forEach((block) => {
      // Simulate file upload progress
      let progressBar = block.querySelector(".progress-bar");
      let width = 0;
      let intervalId = setInterval(() => {
        if (width >= 100) {
          clearInterval(intervalId);
          progressBar.style.width = "100%";
        } else {
          width++;
          progressBar.style.width = width + "%";
        }
      }, 50);
    });
  }

  // Trigger the upload process when the upload button is clicked
  if (typeof uploadButton !== "undefined" && uploadButton !== null) {
    // If uploadButton exists, attach the event listener
    uploadButton.addEventListener("click", function () {
      // Remove the behavior that opens the file input
      // Instead, call the function to start uploading files
      uploadFiles();
    });
  }

  // Add a click event listener to the cancel (X) button to hide the upload message
  const cancelButton = document.querySelector(".cancel-alert-button");
  if (cancelButton) {
    // If cancelButton exists, attach the event listener
    cancelButton.addEventListener("click", function () {
      // Hide the cannot-upload-message
      const message = document.querySelector(".cannot-upload-message");
      if (message) {
        message.style.display = "none";
      }
    });
  }
}

callUploadScript();
