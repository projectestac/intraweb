{include file="IWgroups_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='editdelete.gif' set='icons/large'}</div>
    <h2>{gt text="Delete the group"}: {$group.name}</h2>
    <form id="deleteGroup" class="z-form" action="{modurl modname='IWgroups' type='admin' func='delete'}" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="confirmation" value="1" />
        <input type="hidden" name="gid" value="{$group.gid}" />
        <div class="z-center">
            <span class="z-buttons">
                <a onClick="javascript:forms['deleteGroup'].submit()">
                    {img modname='core' src='button_ok.png' set='icons/small' __alt="Confirm the deletion of the group" __title="Confirm the deletion of the group"} {gt text="Confirm the deletion of the group"}
                </a>
            </span>
            <span class="z-buttons">
                <a href="{modurl modname='IWgroups' type='admin' func='main'}">
                    {img modname='core' src='button_cancel.png' set='icons/small' __alt="Cancel the deletion" __title="Cancel the deletion"} {gt text="Cancel the deletion"}
                </a>
            </span>
        </div>           
    </form>
</div>