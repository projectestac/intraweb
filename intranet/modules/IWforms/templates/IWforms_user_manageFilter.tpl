{if $filterType eq 0}
{gt text="It is the same as"}
<select name="filterValue" onchange="javascript:sendChange(0)">
    {if $filter eq 0}
    <option {if $filterValue eq 0}selected{/if} value="0">{gt text="All"}</option>
    {else}
    <option {if $filterValue eq 0}selected{/if} value="0">{gt text="Choose a value..."}</option>
    {/if}
    {foreach item="item" key="k" from="$items"}
    {if $item neq ''}
    <option {if $filterValue eq $k}selected{/if} value="{$k}">{$item}</option>
    {/if}
    {/foreach}
</select>
{else}
{gt text="It have got the words"}
<input type="text" name="filterValue" size="15" value="{$filterValue}">
<span class="z-buttons">
    <a href="#" onClick="javascript:sendChange(0)">
        {img modname='core' src='button_ok.png' set='icons/extrasmall'}
    </a>
</span>
{/if}
<a href="{modurl modname='IWforms' type='user' func='manage' fid=$fid}">
    {gt text="Return to init"}
</a>