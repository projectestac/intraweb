// Copyright Zikula Foundation 2010 - license GNU/LGPLv3 (or at your option, any later version).
function showdynamicsmenu(){var a=$("profileadminlinks");if(a.hasClassName("z-hide")){a.removeClassName("z-hide")}else{a.addClassName("z-hide")}}function liveusersearch(){$("liveusersearch").removeClassName("z-hide");$("modifyuser").observe("click",function(){window.location.href=Zikula.Config.entrypoint+"?module=users&type=admin&func=modify&uname="+$F("username")});$("deleteuser").observe("click",function(){window.location.href=Zikula.Config.entrypoint+"?module=users&type=admin&func=deleteusers&uname="+$F("username")});var a=Zikula.Ajax.Request.defaultOptions({paramName:"fragment",minChars:3,afterUpdateElement:function(b){$("modifyuser").observe("click",function(){window.location.href=Zikula.Config.entrypoint+"?module=users&type=admin&func=modify&userid="+$($(b).value).value});$("deleteuser").observe("click",function(){window.location.href=Zikula.Config.entrypoint+"?module=users&type=admin&func=deleteusers&userid="+$($(b).value).value})}});new Ajax.Autocompleter("username","username_choices",Zikula.Config.baseURL+"ajax.php?module=users&func=getusers",a)};