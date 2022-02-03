let currentTag = document.getElementById("tag-id").value;

document.querySelector("#tag-id").oninput = function () {
    let saveButton = document.querySelector("#save-button");
    let tagText = document.querySelector("#tag-id").value;

    if (tagText != currentTag) {
        saveButton.classList.remove("disabled");
    } else {
        saveButton.classList.add("disabled");
    }
}
