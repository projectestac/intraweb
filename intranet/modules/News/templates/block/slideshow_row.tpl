<a href="{modurl modname='News' type='user' func='display' sid=$sid}"{if $count eq 1} class="show"{/if}><img src="{$picupload_uploaddir}/pic_sid{$sid}-0-norm.jpg" style="height: 300px; width: 594px;" rel="<h4>{$title|safehtml}<a style='padding-left: 10px;' href='{modurl modname='News' type='user' func='display' sid=$sid}'>[ link ]</a></h4>{$hometext|notifyfilters:'news.hook.articlesfilter.ui.filter'|safehtml}"/></a>
