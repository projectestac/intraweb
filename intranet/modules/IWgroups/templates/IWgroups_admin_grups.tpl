{include file="IWgroups_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='agt_family.gif' set='icons/large'}</div>
    {if $action eq 1 OR $action eq 3}
        <h2>{gt text="Move users between groups"}</h2>
        {$group1.name} -> {$group2.name}
        <div class="confirmationList">
            {foreach item=user from=$uid0}
                <div>{$usersInfo[$user]}</div>
            {/foreach}
            {foreach item=user from=$uid1}
                <div>{$usersInfo[$user]}</div>
            {/foreach}
        </div>
         <form id="members" class="z-form" action="{modurl modname='IWgroups' type='Admin' func='grups'}" method="post" enctype="application/x-www-form-urlencoded">
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <input type="hidden" name="confirmation" value="1" />
            <input type="hidden" name="action" value="{$action}" />
            <input type="hidden" name="gid" value="{$gid}" />
            <input type="hidden" name="gid1" value="{$gid1}" />
            {foreach item=user from=$uid0}
                <input type="hidden" name="uid0[]" value="{$user}" />
            {/foreach}
            {foreach item=user from=$uid1}
                <input type="hidden" name="uid1[]" value="{$user}" />
            {/foreach}
            <div class="z-center">
                <span class="z-buttons">
                    <a onClick="javascript:forms['members'].submit()">
                        {img modname='core' src='button_ok.png' set='icons/small' __alt="Confirm the transfer of users between groups" __title="Confirm the transfer of users between groups"} {gt text="Confirm the transfer of users between groups"}
                    </a>
                </span>
                <span class="z-buttons">
                    <a href="{modurl modname='IWgroups' type='admin' func='membres' gid=$gid gid1=$gid1}">
                        {img modname='core' src='button_cancel.png' set='icons/small' __alt="Cancel the action" __title="Cancel the action"} {gt text="Cancel the action"}
                    </a>
                </span>
            </div>
         </form>
    {elseif $action eq 5}
        <h2>{gt text="Delete the members of the groups "}</h2>
        {if @count($uid0) gt 0}
            <div>
                <strong>{$group1.name}</strong>
            </div>
            <div class="confirmationList">
                {foreach item=user from=$uid0}
                    <div>
                        {$usersInfo[$user]}
                    </div>
                {/foreach}
            </div>
        {/if}
        {if @count($uid1) gt 0}
            <div>
                <strong>{$group2.name}</strong>
            </div>
            <div class="confirmationList">
                {foreach item=user from=$uid1}
                    <div>
                        {$usersInfo[$user]}
                    </div>
                {/foreach}
            </div>
        {/if}
        <form id="members" class="z-form" action="{modurl modname='IWgroups' type='Admin' func='grups'}" method="post" enctype="application/x-www-form-urlencoded">
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <input type="hidden" name="confirmation" value="1" />
            <input type="hidden" name="action" value="{$action}" />
            <input type="hidden" name="gid" value="{$gid}" />
            <input type="hidden" name="gid1" value="{$gid1}" />
            {foreach item=user from=$uid0}
                <input type="hidden" name="uid0[]" value="{$user}" />
            {/foreach}
            {foreach item=user from=$uid1}
                <input type="hidden" name="uid1[]" value="{$user}" />
            {/foreach}
            <div class="z-center">
                <span class="z-buttons">
                    <a onClick="javascript:forms['members'].submit()">
                        {img modname='core' src='button_ok.png' set='icons/small' __alt="Confirm the action" __title="Confirm the action"} {gt text="Confirm the action"}
                    </a>
                </span>
                <span class="z-buttons">
                    <a href="{modurl modname='IWgroups' type='admin' func='membres' gid=$gid gid1=$gid1}">
                        {img modname='core' src='button_cancel.png' set='icons/small' __alt="Cancel the deletion" __title="Cancel the deletion"} {gt text="Cancel the deletion"}
                    </a>
                </span>
            </div>
         </form>
    {elseif $action eq 6}
        <h2>{gt text="Groups to which the users belong "}</h2>
        {foreach item=user from=$users}
            <div>
                <strong>{$usersInfo[$user]}</strong>
                <div class="usersGroups">
                    {if @count($quinsgrups[$user]) gt 0}
                        {foreach item=group from=$quinsgrups[$user]}
                            <div>
                                {$groups[$group.id]}
                            </div>
                        {/foreach}
                    {else}
                        {gt text="User without groups"}
                    {/if}
                </div>
            </div>
        {/foreach}       
        <div class="z-center">
            <span class="z-buttons">
                <a href="{modurl modname='IWgroups' type='admin' func='membres' gid=$gid gid1=$gid1}">
                    {img modname='core' src='previous.png' set='icons/small' __alt="Return to members table" __title="Return to members table"} {gt text="Return to members table"}
                </a>
            </span>
        </div>
    {/if}
</div>