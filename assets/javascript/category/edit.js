document.addEventListener("DOMContentLoaded", function () {
    let tree = window.CATEGORY_TREE;

    let state = {
        editing: false
    }

    let existingData = {
        copyLevelMarkup: function (category){
            let clone = document.querySelector('#levelMarkup').content.cloneNode(true);
            clone.querySelector(".level-title").textContent = category.name;
            clone.querySelector("[data-level]").setAttribute('draggable', true);
            addListeners(clone, category.id);
            return clone
        },
        renderSubLevel: function (categories, parentLevel) {
            categories.forEach(category => {
                let clone = this.copyLevelMarkup(category)

                if (category.children !== []) {
                    this.renderSubLevel(category.children, clone);
                }
                parentLevel.querySelector('ul').appendChild(clone);
            })
        },
        render: function(categories) {
            categories.forEach(category => {
                let clone = this.copyLevelMarkup(category);

                if (category.children.length > 0) {
                    this.renderSubLevel(category.children, clone);
                }
                document.querySelector("#base-ul").appendChild(clone);
            })
        }
    }
    existingData.render(tree);

    let apiController = {
        addCategoryApiRequest : async function (referenceId, action) {
            let name = document.getElementById('new-category-input').value;
            let category = {'name': name, 'referencedId': referenceId, 'action': action};

            let promise = fetch('http://deployertest.local/admin/category/create/api', {
                method: 'POST',
                body: JSON.stringify(category),
            })
            return promise;
        },
        removeCategoryAndChildrenApiRequest: async function (categoryId) {
            let promise = fetch('http://deployertest.local/admin/category/' + categoryId + '/delete/api', {

            })
            return promise;
        }
    }

    let treeviewActions = {
        resetBtnToggle: function () {
            $(".js-treeview")
                .find(".level-add")
                .find("span")
                .removeClass()
                .addClass("fa fa-plus");
            $(".js-treeview")
                .find(".level-add")
                .siblings()
                .removeClass("in");
        },
        addSameLevel: function (target, referenceId) {
            let ulElm = target.closest("ul");
            let clone = document.querySelector('#inputMarkup').content.cloneNode(true);

            clone.querySelector('#category-add-confirm').addEventListener('click', event => {
                apiController.addCategoryApiRequest(referenceId, 'addAfter').then(response => response.json()).then(data => {
                    ulElm.removeChild(event.target.closest('li'));
                    let levelMarkupClone = document.querySelector('#levelMarkup').content.cloneNode(true);
                    levelMarkupClone.querySelector('.level-title').textContent = data.name;
                    addListeners(levelMarkupClone, data.id);
                    ulElm.append(levelMarkupClone);
                    state.editing = false;
                });
            })
            clone.querySelector('#category-deny').addEventListener('click', event => {
                ulElm.removeChild(event.target.closest('li'));
                state.editing = false;
            })
            ulElm.append(clone)
        },
        addSubLevel: function (target, referenceId) {
            let liElm = target.closest("li");

            let clone = document.querySelector('#inputMarkup').content.cloneNode(true);

            clone.querySelector('#category-add-confirm').addEventListener('click', event => {
               apiController.addCategoryApiRequest(referenceId, 'addSub').then(response => response.json()).then(data => {
                    liElm.querySelector('ul').removeChild(event.target.closest('li'));
                    let levelMarkupClone = document.querySelector('#levelMarkup').content.cloneNode(true);
                    levelMarkupClone.querySelector('.level-title').textContent = data.name;
                    addListeners(levelMarkupClone, data.id);
                    liElm.querySelector('ul').append(levelMarkupClone);
                    state.editing = false;
                })
            })
            clone.querySelector('#category-deny').addEventListener('click', event => {
                liElm.querySelector('ul').removeChild(event.target.closest('li'));
                state.editing = false;
            })

            liElm.querySelector('ul').append(clone);
        },
        removeLevel: function (target, categoryId) {
           apiController.removeCategoryAndChildrenApiRequest(categoryId).then(response => response.json()).then(data => {
                console.log(data);
                target.closest("li").remove();
            });


        }
    };

    $(".js-treeview").on("click", ".level-add", function () {
        if (state.editing === false) {
            $(this).find("span").toggleClass("fa-plus").toggleClass("fa-times text-danger");
            $(this).siblings().toggleClass("in");

        }
    });


    function addListeners(clone, categoryId) {
        clone.querySelector(".level-same").addEventListener('click', event => {
            state.editing = true;
            treeviewActions.addSameLevel(event.target, categoryId);
            treeviewActions.resetBtnToggle();
        })
        clone.querySelector('.level-sub').addEventListener('click', event => {
            state.editing = true;
            treeviewActions.addSubLevel(event.target, categoryId);
            treeviewActions.resetBtnToggle();
        })
        clone.querySelector('.level-remove').addEventListener('click', event => {
            treeviewActions.removeLevel(event.target, categoryId);
        })
        clone.querySelector(".level-title").addEventListener('click', event => {
            let isSelected = event.target.closest(".category-treeview__level").classList.contains("selected");
            if(!isSelected){
                event.target.closest(".js-treeview").querySelector(".category-treeview__level").classList.remove("selected");
                event.target.closest(".category-treeview__level").classList.add("selected");
            }
            if(isSelected){
                event.target.closest(".category-treeview__level").classList.remove("selected");
            }
        })
    }

    // confirmbutton, needs to the the categoryId of the category it is made in reference to


});
