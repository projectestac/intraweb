{include file="IWgroups_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='edit.gif' set='icons/large'}</div>
    <h2>{gt text="Edit the group"}</h2>
    <form id="editGroup" class="z-form" action="{modurl modname='IWgroups' type='admin' func='update'}" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="gid" value="{$group.gid}" />
        <div class="z-formrow">
            <label for="name">{gt text="Group name"}</label>
            <input type="text" name="name" size="50" maxlength="200" value="{$group.name}" />
        </div>
        <div class="z-formrow">
            <label for="description">{gt text="Description"}</label>
            <input type="text" name="description" size="50" maxlength="200" value="{$group.description}" />
        </div>
        <div class="z-center">
            <span class="z-buttons">
                <a onClick="javascript:forms['editGroup'].submit()">
                    {img modname='core' src='button_ok.png' set='icons/small' __alt="Modify" __title="Modify"} {gt text="Modify"}
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