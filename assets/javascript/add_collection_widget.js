window.addEventListener('load', function () {
        // add to collection
        document.querySelector("#add-another-collection-widget").addEventListener("click", function(){
            let list = document.querySelector(this.dataset.listSelector);
            let counter = list.dataset.widgetCounter;
            let newWidget = list.dataset.prototype;

            newWidget = newWidget.replace(/__name__/g, counter)
            counter++
            list.dataset.widgetCounter = counter

            let newElement = new DOMParser().parseFromString(list.dataset.widgetTags, 'text/html').body.firstChild;
            newElement.innerHTML = newWidget;
            newElement.appendChild(addDeleteButtons());

            list.appendChild(newElement);

        })

    Array.from(document.querySelector('#friend-fields-list').children).forEach(listItem => {
        listItem.appendChild(addDeleteButtons());
    })

})

function addDeleteButtons() {
        let button = document.createElement('p');
        button.setAttribute("class", "delete-item")
        button.innerHTML= "delete"
        button.addEventListener("click", event =>{
            event.target.parentElement.remove();
        })
    return button;
}
