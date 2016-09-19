{include file="IWgroups_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='filenew.gif' set='icons/large'}</div>
    <h2>{gt text="Add a new group"}</h2>
    <form id="addGroup" class="z-form" action="{modurl modname='IWgroups' type='admin' func='create'}" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <div class="z-formrow">
            <label for="name">{gt text="Group name"}</label>
            <input type="text" name="name" size="50" maxlength="200" value="" />
        </div>
        <div class="z-formrow">
            <label for="description">{gt text="Description"}</label>
            <input type="text" name="description" size="50" maxlength="200" value="" />
        </div>
        <div class="z-center">
            <span class="z-buttons">
                <a onClick="javascript:forms['addGroup'].submit()">
                    {img modname='core' src='button_ok.png' set='icons/small' __alt="Create the group" __title="Create the group"} {gt text="Create the group"}
                </a>
            </span>
            <span class="z-buttons">
                <a href="{modurl modname='IWgroups' type='admin' func='main'}">
                    {img modname='core' src='button_cancel.png' set='icons/small' __alt="Cancel" __title="Cancel"} {gt text="Cancel"}
                </a>
            </span>
        </div>           
    </form>
</div>