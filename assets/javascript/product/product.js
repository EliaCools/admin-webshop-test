import {addFormToCollection, addTagFormDeleteLink, showExistingFileNames} from "../add_collection_widget_2";

window.addEventListener('load', () => {
    document.querySelector("#add_item_link").addEventListener('click', e => addFormToCollection("remove image", e));
    Array.from(document.querySelector('div.images').children).forEach(tag => {
        addTagFormDeleteLink(tag, "remove image");
    })

    showExistingFileNames()

})
