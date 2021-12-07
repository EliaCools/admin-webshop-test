// window.addEventListener('load', () => {
//     document.querySelector("#add_item_link").addEventListener('click', addFormToCollection);
//
//     const items = Array.from(document.querySelector('ul.tags').children);
//     items.forEach(item => {
//         addTagFormDeleteLink(item, "test");
//     })
//
// })

function showExistingFileNames() {

    let classId = document.querySelector("#add_item_link").dataset.collectionHolderClass

    Array.from(document.querySelector(classId).children).forEach((element, index) => {
        document.querySelector(classId + '_' + index + '_file').parentNode.appendChild(document.querySelector('#existing_file_' + index))
        document.querySelector(classId + '_' + index + '_file').classList.add("transparent")

    })

    fileNameToggle(classId)
}

function fileNameToggle(classId) {
    let oldvalues = {};
    document.querySelector('form').addEventListener("change", () => {

        Array.from(document.querySelector(classId).children).forEach((element, index) => {

            if (document.querySelector('#existing_file_' + index)) {
                if (document.querySelector(classId + '_' + index + '_file').value) {
                    oldvalues[index] = document.querySelector('#existing_file_' + index).innerText
                    document.querySelector('#existing_file_' + index).innerHTML = document.querySelector(classId + '_' + index + '_file').value

                } else {
                    document.querySelector('#existing_file_' + index).innerText = oldvalues[index];
                }
            }
        })
    })
}

function addFormToCollection(string, e) {
    let test = e.target
    const collectionHolder = document.querySelector(test.dataset.collectionHolderClass);
    const item = document.createElement('div');
    item.classList.add("file-drop-area")
    let counter = collectionHolder.dataset.widgetCounter;
    item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, counter);

    addTagFormDeleteLink(item, string);
    collectionHolder.insertAdjacentElement('beforeend', item);
    collectionHolder.dataset.widgetCounter++;

}

function addTagFormDeleteLink(collectionHolder, deleteButtonString) {

    const button = document.createElement("button");
    button.innerText = deleteButtonString;
    button.classList.add('btn')
    button.classList.add('btn-outline-secondary')
    button.addEventListener('click', (e) => {
        e.preventDefault();
        collectionHolder.remove();
    })

    collectionHolder.appendChild(button);
}

export {addFormToCollection, addTagFormDeleteLink, showExistingFileNames}
