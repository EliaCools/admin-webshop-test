window.addEventListener('load', () => {
    document.querySelector("#add_item_link").addEventListener('click', addFormToCollection);

    const tags = Array.from(document.querySelector('ul.tags').children);
    tags.forEach(tag => {
        addTagFormDeleteLink(tag);
    })

})

function addFormToCollection (e){
    const collectionHolder = document.querySelector(this.dataset.collectionHolderClass);
    const item = document.createElement('li');

    let counter = collectionHolder.dataset.index;
    item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, counter);

    addTagFormDeleteLink(item);
    collectionHolder.appendChild(item);
    collectionHolder.dataset.index++;

}

function addTagFormDeleteLink(tagFormList){
    const button = document.createElement("button");
    button.innerText = "delete this tag";

    button.addEventListener('click', (e) =>{
        e.preventDefault();
        tagFormList.remove();
    })

    tagFormList.appendChild(button);
}
