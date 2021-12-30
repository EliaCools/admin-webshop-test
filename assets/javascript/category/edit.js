document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.category-tree-names').forEach(element => {
        element.addEventListener('click', event => {
            console.log(element.dataset.categoryId);
            event.stopPropagation();
        })
    })

    document.querySelectorAll('.add-after').forEach(element => {
        element.addEventListener('click', event => {

            let selectedCategoryElement = element.closest(".category-tree-items").id;
            let selectedCategoryId = element.closest(".category-tree-items").dataset.categoryId;
            let li = createInputElement(element, selectedCategoryId);

            document.querySelector('#' + selectedCategoryElement).insertAdjacentElement('afterend', li);
            event.stopPropagation();
        })
    })

    function createInputElement(element, referencedCategoryId){

        let input = document.createElement("input")
        input.setAttribute("type", "text");
        input.setAttribute("id", 'new-category-input');

        let li = document.createElement('li');
        li.appendChild(input);

        let confirmButton = document.createElement('button')
        confirmButton.dataset.referencedCategoryId = referencedCategoryId;
        confirmButton.dataset.action = "addAfter"

        let text = document.createTextNode("confirm");
        confirmButton.appendChild(text);

        confirmButton.addEventListener('click', makeApiRequest , false)

        input.insertAdjacentElement('afterend', confirmButton);
        return li;
    }


    function makeApiRequest(event) {

     event.preventDefault();
     event.stopPropagation();
     request(event.target.dataset.referencedCategoryId, event.target.dataset.action);

    }

    async function request(referencedId, action) {
        let name = document.getElementById('new-category-input').value;
        let category = { 'name' : name , "referencedId" : referencedId, 'action': action };

        let promise = fetch('http://deployertest.local/admin/category/create/api', {
            method: 'POST',
            body: JSON.stringify(category),
        })

        promise.then(response => response.json()).then(
            data => console.log(data));
    }

});
