{include file="IWgroups_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='package_settings.png' set='icons/large'}</div>
    <h2>{gt text="Modify the group module configuration"}</h2>
    {if isset($noGroups) AND $noGroups eq 1}
        {gt text="No groups found"}
    {else}
        <form id="config" class="z-form" action="{modurl modname='IWgroups' type='admin' func='conf_modifica'}" method="post" enctype="application/x-www-form-urlencoded">
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <div class="z-formrow">
                <label for="grupinici">{gt text="Initial group"}</label>
                <select name="grupinici" id="grupinici">
                    <option value="0">{gt text="Choose a group..."}</option>
                    {foreach item=group from=$groups}
                        <option value="{$group.id}" {if $grupinici eq $group.id}selected="selected"{/if}>{$group.name}</option>
                    {/foreach}    
                </select>
            </div>
            <div class="z-formrow">
                <label for="confesb">{gt text="Confirm the deletion of groups membership"}</label>
                <input type="checkbox" name="confesb" value="1" {if $confesb eq 1}checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="confmou">{gt text="Confirm the transfer of members between groups"}</label>
                <input type="checkbox" name="confmou" value="1" {if $confmou eq 1}checked="checked"{/if} />
            </div>
            <div class="z-center">
                <span class="z-buttons">
                    <a onClick="javascript:forms['config'].submit()">
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
    {/if}
</div>
