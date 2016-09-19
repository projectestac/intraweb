{include file="IWgroups_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='windowlist.png' set='icons/large'}</div>
    <h2>{gt text="Groups list"}</h2>
    <table class="z-datatable">
        <thead>
            <tr>
                <th>{gt text="id"}</th>
                <th>{gt text="Group name"}</th>
                <th>{gt text="Description"}</th>
                <th>{gt text="Options"}</th>
            </tr>
        </thead>
        <tbody>
            {foreach item=group from=$groups}
                <tr class="{cycle values="z-odd,z-even"}">
                    <td align="left" valign="top">{$group.gid}</td>
                    <td align="left" valign="top">{$group.name}</td>
                    <td align="left" valign="top">
                        {if $group.description != ''}
                            {$group.description}
                        {else}
                            ---
                        {/if}
                    </td>
                    <td align="right" valign="top">
                        <a href="{modurl modname='IWgroups' type='admin' func='edita' gid=$group.gid}">{gt text="Edit"}</a><br>
                        <a href="{modurl modname='IWgroups' type='admin' func='delete' gid=$group.gid}">{gt text="Delete"}</a>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</div>