function failure()
{

}

function addCategory(a){
    var b={
        categoryId:a
    };
    
    var c=new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=IWdocmanager&func=addCategory",{
        parameters: b,
        onComplete: addCategory_response,
        onFailure: failure
    });
}

function addCategory_response(a){
    if(!a.isSuccess()){
        Zikula.showajaxerror(a.getMessage());
        return
    }
    var b=a.getData();
    $("newCategory_" + b.categoryId).update(b.content);
}

function cancelCreateCategory(a){
    $("newCategory_" + a).update('');
}

function editCategory(a){
    var b={
        categoryId:a
    };
    var c=new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=IWdocmanager&func=editCategory",{
        parameters: b,
        onComplete: editCategory_response,
        onFailure: failure
    });
}

function editCategory_response(a){
    if(!a.isSuccess()){
        Zikula.showajaxerror(a.getMessage());
        return
    }

    var b=a.getData();
    $("newCategory_" + b.categoryId).update(b.content);
}

function deleteCategory(a){
    var r = confirm(deteleText);
    
    if (!r) {
        return true;
    }
    
    var b={
        categoryId:a
    };
    
    var c=new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=IWdocmanager&func=deleteCategory",{
        parameters: b,
        onComplete: deleteCategory_response,
        onFailure: failure
    });
}

function deleteCategory_response(a){
    if(!a.isSuccess()){
        Zikula.showajaxerror(a.getMessage());
        return
    }
    var b=a.getData();
    $("categoryId_" + b.categoryId).update('');
}

function createCategory(categoryId){
    var form = document.forms['add_' + categoryId];
    var categoryName = form.categoryName.value;
    var categoryDescription = form.description.value;
    var categoryGroups = '';
    var categoryGroupsAdd = '';

    if(form.active.checked) {
        categoryActive = '1';
    } else {
        categoryActive = '0';
    }

    var groups = form.elements['groups[]'];
    for (var i = 0; i < groups.length; i++) {
        if (groups[i].checked) {
            categoryGroups = categoryGroups + '$' + groups[i].value + '$';
        }
    }

    var groupsAdd = form.elements['groupsAdd[]'];
    for (var j = 0; j < groupsAdd.length; j++) {
        if (groupsAdd[j].checked) {
            categoryGroupsAdd = categoryGroupsAdd + '$' + groupsAdd[j].value + '$';
        }
    }
    
    var parameters = {
        categoryId: categoryId,
        categoryName: categoryName,
        description: categoryDescription,
        active: categoryActive,
        groups: categoryGroups,
        groupsAdd: categoryGroupsAdd
    };
    var ajaxResponse = new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=IWdocmanager&func=createCategory",{
        parameters: parameters,
        onComplete: createCategory_response,
        onFailure: failure
    });
}

function createCategory_response(ajaxResponse){
    if(!ajaxResponse.isSuccess()){
        Zikula.showajaxerror(ajaxResponse.getMessage());
        return
    }
    var data = ajaxResponse.getData();
    
    $("IWdocmanager_admin").update(data.content);
}

function updateCategory(categoryId){
    var form = document.forms['edit_' + categoryId];
    var categoryName = form.categoryName.value;
    var categoryDescription = form.description.value;
    var categoryGroups = '';
    var categoryGroupsAdd = '';

    if(form.active.checked) {
        categoryActive = '1';
    } else {
        categoryActive = '0';
    }
    
    var groups = form.elements['groups[]'];
    for (var i = 0; i < groups.length; i++) {
        if (groups[i].checked) {
            categoryGroups = categoryGroups + '$' + groups[i].value + '$';
        }
    }
    
    var groupsAdd = form.elements['groupsAdd[]'];
    for (var j = 0; j < groupsAdd.length; j++) {
        if (groupsAdd[j].checked) {
            categoryGroupsAdd = categoryGroupsAdd + '$' + groupsAdd[j].value + '$';
        }
    }
    
    var parameters = {
        categoryId: categoryId,
        categoryName: categoryName,
        description: categoryDescription,
        active: categoryActive,
        groups: categoryGroups,
        groupsAdd: categoryGroupsAdd
    };
    var ajaxResponse = new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=IWdocmanager&func=updateCategory",{
        parameters: parameters,
        onComplete: updateCategory_response,
        onFailure: failure
    });
}

function updateCategory_response(ajaxResponse){
    if(!ajaxResponse.isSuccess()){
        Zikula.showajaxerror(ajaxResponse.getMessage());
        return
    }
    var data = ajaxResponse.getData();
    
    $("IWdocmanager_admin").update(data.content);
}

function openDocumentLink(a){
    var b={
        documentId:a
    };
    
    var c=new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=IWdocmanager&func=openDocumentLink",{
        parameters: b,
        onComplete: openDocumentLink_response,
        onFailure: failure
    });
}

function openDocumentLink_response(a){
    if(!a.isSuccess()){
        Zikula.showajaxerror(a.getMessage());
        return
    }
    var b=a.getData();
    $("documentsContent").update(b.content);
}

function validateDocument(a){
    var b={
        documentId:a
    };
       
    var c=new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=IWdocmanager&func=validateDocument",{
        parameters: b,
        onComplete: validateDocument_response,
        onFailure: failure
    });
}

function validateDocument_response(a){
    if(!a.isSuccess()){
        Zikula.showajaxerror(a.getMessage());
        return
    }
    var b=a.getData();
    
    $("documentsContent").update(b.content);
}

function deleteDocument(a){
    var r = confirm(deteleText);
    
    if (!r) {
        return true;
    }
    var b={
        documentId:a
    };
       
    var c=new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=IWdocmanager&func=deleteDocument",{
        parameters: b,
        onComplete: deleteDocument_response,
        onFailure: failure
    });
}

function deleteDocument_response(a){
    if(!a.isSuccess()){
        Zikula.showajaxerror(a.getMessage());
        return
    }
    var b=a.getData();
    
    $("documentsContent").update(b.content);
}

function viewDocumentVersions(a){
    var b={
        documentId:a
    };
       
    var c=new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=IWdocmanager&func=viewDocumentVersions",{
        parameters: b,
        onComplete: viewDocumentVersions_response,
        onFailure: failure
    });
}

function viewDocumentVersions_response(a){
    if(!a.isSuccess()){
        Zikula.showajaxerror(a.getMessage());
        return
    }
    var b=a.getData();
    
    $("documentsContent").update(b.content);
}

function viewDocuments(a){
    var b={
        documentId:a
    };

    var c=new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=IWdocmanager&func=viewDocuments",{
        parameters: b,
        onComplete: viewDocuments_response,
        onFailure: failure
    });
}

function viewDocuments_response(a){
    if(!a.isSuccess()){
        Zikula.showajaxerror(a.getMessage());
        return
    }
    var b=a.getData();
    
    $("documentsContent").update(b.content);
}