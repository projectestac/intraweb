<div class="formContent">
    <div class="z-adminpageicon">{img modname='core' src='group.png' set='icons/large'}</div>
    <h2>{gt text="Add a new group"}</h2>
    <form id="addGroup" class="z-form" action="{modurl modname='IWforms' type='admin' func='addGroup'}" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="fid" value="{$item.fid}" />
        <input type="hidden" name="confirm" value="1" />
        <input type="hidden" name="aio" value="{$aio}" />
        <div class="z-formrow">
            <label for="group">{gt text="Choose the group that should have access to the form"}</label>
            <select name="group">
                {foreach item=group from=$groups}
                <option value="{$group.id}">{$group.name}</option>
                {/foreach}
            </select>
        </div>
        <div class="z-formrow">
            <label for="accessType">{gt text="Type of acces"}</label>
            <select name="accessType">
                <option value="1">{gt text="Only writing"}</option>
                <option value="2">{gt text="Only reading"}</option>
                <option value="3">{gt text="Reading and writing"}</option>
            </select>
        </div>
        <div class="z-formrow">
            <label for="validationNeeded">{gt text="Validation is required"}</label>
            <input id="validationNeeded" name="validationNeeded" type="checkbox" value="1" />
        </div>
        <div class="z-center">
            <span class="z-buttons">
                <a onClick="javascript:forms['addGroup'].submit()">{img modname='core' src='button_ok.png' set='icons/small' __alt="Add" __title="Add"} {gt text="Add"}</a>
            </span>
            <span class="z-buttons">
                {if not isset($aio)}
                <a href="{modurl modname='IWforms' type='admin' func='form' action='group' fid=$item.fid}">
                    {img modname='core' src='button_cancel.png' set='icons/small' __alt="Cancel" __title="Cancel"} {gt text="Cancel"}
                </a>
                {else}
                <a href="{modurl modname='IWforms' type='admin' func='infoForm' fid=$item.fid}">
                    {img modname='core' src='button_cancel.png' set='icons/small' __alt="Cancel" __title="Cancel"} {gt text="Cancel"}
                </a>
                {/if}
            </span>
        </div>
    </form>
</div>