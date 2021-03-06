<div class="formContent">
    <div class="z-adminpageicon">{img modname='core' src='edit.png' set='icons/large'}</div>
    <h2>{gt text="Edit the main form"}</h2>
    <form id="formDefinition" class="z-form" action="{modurl modname='IWforms' type='admin' func='update'}" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="fid" value="{$item.fid}" />
        <input type="hidden" name="aio" value="{$aio}" />
        <fieldset>
            <legend>{gt text="General parameters"}</legend>
            <div class="z-formrow">
                <label for="lang">{gt text="Form language"}</label>
                <select id="lang" name="lang">
                    <option value="">{gt text="All languages"}</option>
                    {foreach item=lang from=$languages}
                    <option {if $item.lang eq $lang.code}selected="selected"{/if} value="{$lang.code}">{$lang.name}</option>
                    {/foreach}
                </select>
            </div>
            <div class="z-formrow">
                <label for="formName">{gt text="Form name"}</label>
                <input id="formName" name="formName" type="text" size="50" maxlength="70" value="{$item.formName}" />
            </div>
            <div class="z-formrow">
                <label for="title">{gt text="Title of the annotations "}</label>
                <input id="title" name="title" type="text" size="50" maxlength="255" value="{$item.title}" />
            </div>
            <div class="z-formrow">
                <label for="cid">{gt text="Category"}</label>
                {if $cats|@count > 0}
                <select id="cid" name="cid">
                    <option value="0">{gt text="Chosse one category..."}</option>
                    {foreach item="cat" from=$cats}
                    <option {if $item.cid eq $cat.cid}selected{/if} value="{$cat.cid}">{$cat.catName}</option>
                    {/foreach}
                </select>
                {else}
                <span>{gt text="There is no defined categories"}</span>
                {/if}
            </div>
            <div class="z-formrow">
                <label for="description">{gt text="Form description"}</label>
                <input id="description" name="description" type="text" size="50" maxlength="255" value="{$item.description}" />
            </div>
            <div class="z-formrow">
                <label for="new">{gt text="As a new until a..."}</label>
                <div class="z-formnote">
                    <input size="10" id="newFormDate" name="new" value="{$new}" onfocus="blur();" />
                    <img id="newFormDate_btn" src="modules/IWmain/images/calendar.gif" style="cursor:pointer;" />
                </div>
            </div>
            <div class="z-formrow">
                <label for="caducity">{gt text="Make it expires automatically a..."}</label>
                <div class="z-formnote">
                    <input size="10" id="caducityDate" name="caducity"  value="{$caducity}" onfocus="blur();" />
                    <img id="caducityDate_btn" src="modules/IWmain/images/calendar.gif" style="cursor:pointer;" />
                </div>
            </div>
            <div class="z-formrow">
                <label for="annonimous">{gt text="Anonymous"}</label>
                <input id="annonimous" name="annonimous" type="checkbox" {if $item.annonimous}checked{/if} value="1"/>
            </div>
            <div class="z-formrow">
                <label for="unique">{gt text="Only answer"}</label>
                <input id="unique" name="unique" type="checkbox" {if $item.unique}checked{/if} value="1"/>
            </div>
            {*}
            <div class="z-formrow">
                <label for="closeableNotes">{gt text="Validators can close the annotations"}</label>
                <input id="closeableNotes" name="closeableNotes" type="checkbox" {if $item.closeableNotes}checked{/if} value="1"/>
            </div>
            {*}
            <div class="z-formrow">
                <label for="closeableInsert">{gt text="Validators can close the entries or the release of the book-entry form"}</label>
                <input id="closeableInsert" name="closeableInsert" type="checkbox" {if $item.closeableInsert}checked{/if} value="1"/>
            </div>
            <div class="z-formrow">
                <label for="closeInsert">{gt text="The form is initially closed"}</label>
                <input id="closeInsert" name="closeInsert" type="checkbox" {if $item.closeInsert}checked{/if} value="1"/>
            </div>
        </fieldset>
        <fieldset>
            <legend>{gt text="Notes users read view"}</legend>
            <div class="z-formrow">
                <label for="defaultNumberOfNotes">{gt text="Default number of notes for page in users read view"}</label>
                <select id="defaultNumberOfNotes" name="defaultNumberOfNotes">
                    <option {if $item.defaultNumberOfNotes eq 1}selected="selected"{/if} value="1">10</option>
                    <option {if $item.defaultNumberOfNotes eq 2}selected="selected"{/if} value="2">20</option>
                    <option {if $item.defaultNumberOfNotes eq 3}selected="selected"{/if} value="3">30</option>
                    <option {if $item.defaultNumberOfNotes eq 4}selected="selected"{/if} value="4">50</option>
                    <option {if $item.defaultNumberOfNotes eq 5}selected="selected"{/if} value="5">70</option>
                    <option {if $item.defaultNumberOfNotes eq 6}selected="selected"{/if} value="6">100</option>
                    <option {if $item.defaultNumberOfNotes eq 7}selected="selected"{/if} value="7">500</option>
                </select>
            </div>
            <div class="z-formrow">
                <label for="defaultOrderForNotes">{gt text="Default order for notes"}</label>
                <select id="defaultOrderForNotes" name="defaultOrderForNotes" onChange="orderField(this.value,{$item.fid});">
                    <option {if $item.defaultOrderForNotes eq 1}selected="selected"{/if} value="1">{gt text="Cronologicaly inverse"}</option>
                    <option {if $item.defaultOrderForNotes eq 2}selected="selected"{/if} value="2">{gt text="Cronologicaly"}</option>
                    <option {if $item.defaultOrderForNotes eq 3}selected="selected"{/if} value="3">{gt text="Alphabetical"}</option>
                    <option {if $item.defaultOrderForNotes eq 4}selected="selected"{/if} value="4">{gt text="Random order"}</option>
                </select>
            </div>
            <div id="orderFieldDiv">
                {if $item.defaultOrderForNotes eq 3}
                    {include file="IWforms_admin_formFields.tpl"}
                {/if}
            </div>
            <div class="z-formrow">
                <label for="unregisterednotusersview">{gt text="Unregistered users can not see the data of senders of entries"}</label>
                <input id="unregisterednotusersview" name="unregisterednotusersview" type="checkbox" {if $item.unregisterednotusersview}checked{/if} value="1"/>
            </div>
            <div class="z-formrow">
                <label for="unregisterednotexport">{gt text="Unregistered users can not export the contents of the annotations"}</label>
                <input id="unregisterednotexport" name="unregisterednotexport" type="checkbox" {if $item.unregisterednotexport}checked{/if} value="1"/>
            </div>
            <div class="z-formrow">
                <label for="publicResponse">{gt text="The answer is visible to all users who have access to information"}</label>
                <input id="publicResponse" name="publicResponse" type="checkbox" {if $item.publicResponse}checked{/if} value="1"/>
            </div>
        </fieldset>
        <fieldset>
            <legend>{gt text="Sending notes beheavor"}</legend>
            <div class="z-formrow">
                <label for="allowComments">{gt text="The notes allow comments"}</label>
                <input id="allowComments" name="allowComments" type="checkbox" {if $item.allowComments}checked{/if} value="1" onClick="javascript:allowCommentsControl(0)"/>
            </div>
            {*}
            <div class="z-formrow">
                <label for="allowCommentsModerated">{gt text="The validators can decide if a particular note allow comments"}</label>
                <input id="allowCommentsModerated" name="allowCommentsModerated" type="checkbox" {if $item.allowCommentsModerated}checked{/if} value="1" onClick="javascript:allowCommentsControl(1)"/>
            </div>
            {*}
            <div class="z-formrow">
                <label for="returnURL">{gt text="Return URL after sending a new note"}</label>
                <input id="returnURL" name="returnURL" type="text" size="50" maxlength="150" value="{$item.returnURL}"/>
            </div>
            <div class="z-formrow">
                <label for="filesFolder">{gt text="Form files folder"}</label>
                <div class="z-formnote">
                    <strong>{$filesFolder}/</strong> <input id="filesFolder" name="filesFolder" type="text" size="50" maxlength="30" value="{$item.filesFolder}"/>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>{gt text="Expert mode"}</legend>
            <div class="z-formrow">
                <label for="expertMode">{gt text="Use expert mode"}</label>
                <input id="expertMode" name="expertMode" type="checkbox" {if $item.expertMode}checked{/if} value="1" onClick="javascript:expertModeActivation({$item.fid},0)"/>
            </div>
            <div id="expertModeContent">
                {include file="IWforms_admin_form_definitionExpertMode.tpl"}
            </div>
        </fieldset>
        <fieldset>
            <legend>{gt text="Activation"}</legend>
            <div class="z-formrow">
                <label for="active">{gt text="Active / non-active"}</label>
                <input id="active" name="active" type="checkbox" {if $item.active}checked{/if} value="1" />
            </div>
            <div class="z-center">
                <span class="z-buttons">
                    <a onClick="javascript:document.forms['formDefinition'].submit()">{img modname='core' src='button_ok.png' set='icons/small' __alt="Edit" __title="Edit"} {gt text="Edit"}</a>
                </span>
                <span class="z-buttons">
                    {if not isset($aio)}
                    <a href="{modurl modname='IWforms' type='admin' func='form' fid=$item.fid}">
                        {img modname='core' src='button_cancel.png' set='icons/small' __alt="Cancel" __title="Cancel"} {gt text="Cancel"}
                    </a>
                    {else}
                    <a href="{modurl modname='IWforms' type='admin' func='infoForm' fid=$item.fid}">
                        {img modname='core' src='button_cancel.png' set='icons/small' __alt="Cancel" __title="Cancel"} {gt text="Cancel"}
                    </a>
                    {/if}
                </span>
            </div>
        </fieldset>
    </form>
</div>

<script type="text/javascript">
    var newForm = Calendar.setup({
        onSelect       :    function(newForm) { newForm.hide() }
    });
    newForm.manageFields("newFormDate_btn", "newFormDate", "%d/%m/%y");
    
    var caducity = Calendar.setup({
        onSelect       :    function(caducity) { caducity.hide() }
    });
    caducity.manageFields("caducityDate_btn", "caducityDate", "%d/%m/%y");
</script>
