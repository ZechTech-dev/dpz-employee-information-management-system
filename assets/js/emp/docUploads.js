const uploadBtn = document.getElementById("uploadBtn");
const modal = document.getElementById("uploadModal");
const closeBtn = document.getElementById("closeUpload");
const cancelBtn = document.getElementById("cancelUpload");
const fileInput = document.getElementById("documentFile");
const fileName = document.getElementById("fileName");
const preview = document.getElementById("filePreview");


uploadBtn.onclick = () => {
    modal.style.display = "flex";
};


closeBtn.onclick = cancelBtn.onclick = () => {
    modal.style.display = "none";
};


modal.onclick = e => {
    if (e.target === modal)
        modal.style.display = "none";
};


fileInput.onchange = () => {

    const file = fileInput.files[0];

    if (!file) return;


    const allowed = [
        "image/png",
        "image/jpeg",
        "application/pdf"
    ];


    if (!allowed.includes(file.type)) {

        alert("Only PNG, JPG, JPEG, and PDF files are allowed.");

        fileInput.value = "";

        fileName.textContent = "No file selected";

        return;
    }


    fileName.textContent = file.name;


    const url = URL.createObjectURL(file);


    if (file.type === "application/pdf") {

        preview.innerHTML =
            `<iframe src="${url}"></iframe>`;

    } else {

        preview.innerHTML =
            `<img src="${url}" alt="Document preview">`;

    }

};

const uploadForm = document.getElementById("uploadForm");

uploadForm.addEventListener("submit", (e) => {
    const confirmed = confirm(
        "Please make sure you have selected the correct document before continuing.\n\n" +
        "Do you want to upload this document?"
    );

    if (!confirmed) {
        e.preventDefault();
    }
});