{if $item.expertMode}
<div class="z-form">
    <fieldset>
        <legend>{gt text="Templates for the notes (expert mode)"}</legend>
        {if not isset($multizk) OR not $multizk}
        <div class="z-formrow">
            <label for="skinByTemplate">{gt text="Use skin by templates"}</label>
            <input id="skinByTemplate" name="skinByTemplate" type="checkbox" {if $item.skinByTemplate}checked{/if} value="1" onClick="javascript:expertModeActivation({$item.fid},1)"/>
        </div>
        {/if}
        {if $item.skinByTemplate}
        <div class="z-formrow">
            <label for="skinFormTemplate">{gt text="Template to aply for the form"}</label>
            <input id="skinFormTemplate" type="text" name="skinFormTemplate" size="70" value="{$item.skinFormTemplate}"/>
        </div>
        <div class="z-formrow">
            <label for="skinTemplate">{gt text="Template to aply for the set of notes"}</label>
            <input id="skinTemplate" type="text" name="skinTemplate" size="70" value="{$item.skinTemplate}"/>
        </div>
        <div class="z-formrow">
            <label for="skinNoteTemplate">{gt text="Template to aply for an alone note"}</label>
            <input id="skinNoteTemplate" type="text" name="skinNoteTemplate" size="70" value="{$item.skinNoteTemplate}"/>
        </div>
        {else}
        <div class="z-formrow">
            <label for="showFormName">{gt text="Show the form name"}</label>
            <input id="showFormName" name="showFormName" type="checkbox" {if $item.showFormName}checked{/if} value="1"/>
        </div>
        <div class="z-formrow">
            <label for="showNotesTitle">{gt text="Show the notes title"}</label>
            <input id="showNotesTitle" name="showNotesTitle" type="checkbox" {if $item.showNotesTitle}checked{/if} value="1"/>
        </div>
        <div class="z-formrow">
            <div>
                {gt text="Form aspect"}
            </div>
            <div>
                <textarea id="skinForm" name="skinForm" cols="70" rows="7">{$item.skinForm}</textarea>
            </div>
        </div>
        <div class="z-informationmsg">
            {gt text="[\$id\$] => Form field"}
        </div>
        <div class="z-formrow">
            <div>
                {gt text="Set of notes of the form"}
            </div>
            <div>
                <textarea id="skin" name="skin" cols="70" rows="7">{$item.skin}</textarea>
            </div>
        </div>
        <div class="z-formrow">
            <div>
                {gt text="An individual note"}
            </div>
            <div>
                <textarea id="skinNote" name="skinNote" cols="70" rows="7">{$item.skinNote}</textarea>
            </div>
        </div>
        <div class="z-informationmsg">
            {gt text="[\$formId\$] =>Identity of the form, [\$noteId\$] =>Identity of the note, [%id%] => Title of the field, [\$id\$] => Content of the field, [\$user\$] => Username, [\$date\$] => Note creation date, [\$time\$] => Note creation time, [\$avatar\$] => User avatar, [\$reply\$] => Reply to the user if the reply is public"}
        </div>
        <div class="z-formrow">
            <label for="skincss">{gt text="Styles sheet to aply (give full URL)"}</label>
            <input id="skincss" type="text" name="skincss" size="70" value="{$item.skincss}"/>
        </div>
        {/if}
    </fieldset>
</div>
{/if}