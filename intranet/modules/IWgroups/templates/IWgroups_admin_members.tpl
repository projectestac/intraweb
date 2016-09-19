{include file="IWgroups_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='agt_family.gif' set='icons/large'}</div>
    <h2>{gt text="Groups membership administration"}</h2>
    <form id="members" class="z-form" action="{modurl modname='IWgroups' type='admin' func='grups'}" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="action" value="0" />
        <div style="float: left; width: 470px;">
            <div class="columheader">
                {gt text="Group"} {$nom_grup.name}
            </div>
            <div class="groupsSelector">
                <select name="gid" onchange="this.form.submit();">
                    {foreach item=group from=$groups}
                        <option value="{$group.id}" {if $group.id eq $gid}selected="selected"{/if}>{$group.name}</option>
                    {/foreach}
                </select>
            </div>
            <div class="usersList">
                {if $nomembres eq 1}
                    <div>
                        {gt text="Empty Group"}
                    </div>
                {else}
                    {if $gid eq 0 OR $gid eq ''}
                        <div>
                            {gt text="Without group"}
                        </div>
                    {/if}
                    {foreach item=membre from=$membres}
                        <div id="gid-{$membre.id}"  class="userRow">
                            <div style="float:left;">
                                {$usersInfo[$membre.id]}
                            </div>
                            <div style="float: right;">
                                <input type="checkbox" name="uid0[]" id="cbgid-{$membre.id}" value="{$membre.id}" onChange="javascript:changeColor('gid-'+{$membre.id})" />
                            </div>
                            <div class="z-clearer"></div>
                        </div>
                    {/foreach}
                {/if}
            </div>
        </div>
        <div style="float: right; width: 470px;">
            <div class="columheader">
                {gt text="Group"} {$nom_grup1.name}
            </div>
            <div class="groupsSelector">
                <select name="gid1" onchange="this.form.submit();">
                    {foreach item=group from=$groups1}
                        <option value="{$group.id}" {if $group.id eq $gid1}selected="selected"{/if}>{$group.name}</option>
                    {/foreach}
                </select>
            </div>
            <div class="usersList">
                {if $nomembres1 eq 1}
                    <div>
                        {gt text="Empty Group"}
                    </div>
                {else}
                    {if $gid1 eq 0 OR $gid1 eq ''}
                        <div>
                            {gt text="Without group"}
                        </div>
                    {/if}
                    {foreach item=membre from=$membres1}
                        <div id="gid1-{$membre.id}" class="userRow">
                            <div style="float:left;">
                                {$usersInfo[$membre.id]}
                            </div>
                            <div style="float:right;">
                                <input type="checkbox" name="uid1[]" id="cbgid1-{$membre.id}" value="{$membre.id}" onClick="javascript:changeColor('gid1-'+{$membre.id})" />
                            </div>
                            <div class="z-clearer"></div>
                        </div>
                    {/foreach}
                {/if}
            </div>
        </div>
        <div style="margin-left: 470px; margin-right: 470px; background-color: #C2E0EF;">
            <div class="columheader">
                {gt text="Options"}
            </div>
            <div class="buttonsBlock">
                <div class="z-buttons rowButton">
                    <a onClick="javascript:forms['members'].action.value=1;forms['members'].submit();">
                        {img modname='core' src='player_fwd.png' set='icons/small' __alt="Move to the group >> " __title="Move to the group >> "} {gt text="Move to the group >> "}
                    </a>
                </div>
                <div class="z-buttons rowButton">
                    <a onClick="javascript:forms['members'].action.value=2;forms['members'].submit();">
                        {img modname='core' src='add_group.png' set='icons/small' __alt="Add to the group >> " __title="Add to the group >> "} {gt text="Add to the group >> "}
                    </a>
                </div>
                <hr />
                <div class="z-buttons rowButton">
                    <a onClick="javascript:forms['members'].action.value=3;forms['members'].submit();">
                        {img modname='core' src='player_rew.png' set='icons/small' __alt="<< Move to the group" __title="<< Move to the group"} {gt text="<< Move to the group"}
                    </a>
                </div>
                <div class="z-buttons rowButton">
                    <a onClick="javascript:forms['members'].action.value=4;forms['members'].submit();">
                        {img modname='core' src='add_group.png' set='icons/small' __alt="<< add to the group" __title="<< add to the group"} {gt text="<< add to the group"}
                    </a>
                </div>
                <hr />
                <div class="z-buttons rowButton">
                    <a onClick="javascript:forms['members'].action.value=5;forms['members'].submit();">
                        {img modname='core' src='delete_group.png' set='icons/small' __alt="Remove from the groups" __title="Remove from the groups"} {gt text="Remove from the groups"}
                    </a>
                </div>
                <div class="z-buttons rowButton">
                    <a onClick="javascript:forms['members'].action.value=6;forms['members'].submit();">
                        {img modname='core' src='info.png' set='icons/small' __alt="Member of the following groups" __title="Member of the following groups"} {gt text="Member of the following groups"}
                    </a>
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
    </form>
</div>