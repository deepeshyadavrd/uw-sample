const inputbrowse = document.querySelector(".input-browse"),
fileInput = document.querySelector(".file-input-upload"),
progressArea = document.querySelector(".progress-area"),
uploadedArea = document.querySelector(".uploaded-area")
// dragArea = document.querySelector(".drag-area");
// form click event
// inputbrowse.addEventListener("click", () =>{
//   fileInput.click();
// });
let dragArea = document.querySelector(".drag-area");

// Prevent default drag behaviors
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
  dragArea.addEventListener(eventName, preventDefaults, false);
});

// Highlight drop area when item is dragged over it
['dragenter', 'dragover'].forEach(eventName => {
  dragArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
  dragArea.addEventListener(eventName, unhighlight, false);
});

// Handle dropped files
dragArea.addEventListener('drop', handleDrop, false);

function preventDefaults (e) {
  e.preventDefault();
  e.stopPropagation();
}

function highlight(e) {
  dragArea.classList.add('highlight');
}

function unhighlight(e) {
  dragArea.classList.remove('highlight');
}

function handleDrop(e) {
  let dt = e.dataTransfer;
  let files = dt.files;
  let file = files[0];

  if(file){
    let fileName = file.name;
    if(fileName.length >= 12){
      let splitName = fileName.split('.');
      fileName = splitName[0].substring(0, 13) + "... ." + splitName[1];
    }
    uploadFile(fileName, file);
  }
}

fileInput.onchange = ({target})=>{
  let file = target.files; //getting file [0] this means if user has selected multiple files then get first one only
  for (let i = 0; i < file.length; ++i) {
	if(file){
		let fileName = file[1].name; //getting file name
		if(fileName.length >= 12){ //if file name length is greater than 12 then split it and add ...
		let splitName = fileName.split('.');
		fileName = splitName[0].substring(0, 13) + "... ." + splitName[1];
		}
		uploadFile(fileName); //calling uploadFile with passing file name as an argument
	}
  }
}

// file upload function
function uploadFile(name){
  let xhr = new XMLHttpRequest(); //creating new xhr object (AJAX)
  xhr.open("POST", "php/upload.php"); //sending post request to the specified URL
//   xhr.onreadystatechange = function() {
//     if (xhr.readyState == 4 && xhr.status == 200) {
//         alert(xhr.responseText);
//     }
// }
  xhr.upload.addEventListener("progress", ({loaded, total}) =>{ //file uploading progress event
    let fileLoaded = Math.floor((loaded / total) * 100);  //getting percentage of loaded file size
    let fileTotal = Math.floor(total / 1000); //gettting total file size in KB from bytes
    let fileSize;
    // if file size is less than 1024 then add only KB else convert this KB into MB
    (fileTotal < 1024) ? fileSize = fileTotal + " KB" : fileSize = (loaded / (1024*1024)).toFixed(2) + " MB";
    let progressHTML = `<li class="row2">
                          <i class="fas fa-file-alt"></i>
                          <div class="content">
                            <div class="details">
                              <span class="name">${name} • Uploading</span>
                              <span class="percent">${fileLoaded}%</span>
                            </div>
                            <div class="progress-bar">
                              <div class="progress" style="width: ${fileLoaded}%"></div>
                            </div>
                          </div>
                        </li>`;
    // uploadedArea.innerHTML = ""; //uncomment this line if you don't want to show upload history
    uploadedArea.classList.add("onprogress");
    progressArea.innerHTML = progressHTML;
    if(loaded == total){
      progressArea.innerHTML = "";
      let uploadedHTML = `<li class="row2">
                            <div class="content">
                              <i class="fas fa-file-alt"></i>
                              <div class="details">
                                <span class="name">${name} • Uploaded</span>
                                <span class="size">${fileSize}</span>
                              </div>
                            </div>
                            <i class="fas fa-check"></i>
                            <button type="button" class="border-0"><i class="fas fa-trash"></i> </button>
                          </li>`;
      uploadedArea.classList.remove("onprogress");
      // uploadedArea.innerHTML = uploadedHTML; //uncomment this line if you don't want to show upload history
      uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML); //remove this line if you don't want to show upload history
    }
  });
  let data = {"file" : $(".file-input-upload").val()} //FormData is an object to easily send form data
  xhr.send(data); //sending form data
}
