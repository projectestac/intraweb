﻿CKEDITOR.dialog.add("osem_googlemaps",function(f){return{title:"Google Maps",minWidth:350,minHeight:100,onShow:function(){this.setupContent(this.getSelectedElement())},onOk:function(){var a=this.getValueOf("info","area").replace(/\"/g,""),b=this.getValueOf("info","zoom"),c=this.getValueOf("info","width").replace(/\D/g,""),d=this.getValueOf("info","height").replace(/\D/g,""),e=this.getValueOf("info","map_type"),a='<img src="http://maps.google.com/maps/api/staticmap?center='+a.replace(/\s/g,"+")+"&markers=color:red|"+
a.replace(/\s/g,"+")+"&zoom="+b+"&size="+c+"x"+d+"&sensor=false&maptype="+e+'" alt="'+a+'" cke_googlemaps_area="'+a+'" cke_googlemaps_zoom="'+b+'" cke_googlemaps_width="'+c+'" cke_googlemaps_height="'+d+'" cke_googlemaps_map_type="'+e+'" onClick="window.open(\'https://www.google.com/maps/place/'+encodeURIComponent(a)+"', '_blank')\" style=\"cursor: pointer\"/>";f.insertHtml(a)},contents:[{id:"info",label:"Google Maps",title:"Google Maps",elements:[{id:"area",label:"Area",type:"text",setup:function(a){this.setValue(a.getAttribute("cke_googlemaps_"+
this.id))}},{id:"zoom",label:"Zoom",type:"select",setup:function(a){this.setValue(a.getAttribute("cke_googlemaps_"+this.id))},labelLayout:"horizontal","default":"14",items:[["20 - closest","20"],["19","19"],["18","18"],["17","17"],["16","16"],["15","15"],["14","14"],["13","13"],["12","12"],["11","11"],["10","10"],["9","9"],["8","8"],["7 - furthest","7"]]},{id:"width",label:"Width",type:"text",setup:function(a){this.setValue(a.getAttribute("cke_googlemaps_"+this.id))},labelLayout:"horizontal",width:"40px",
"default":300},{id:"height",label:"Height",type:"text",setup:function(a){this.setValue(a.getAttribute("cke_googlemaps_"+this.id))},labelLayout:"horizontal",width:"40px","default":300},{id:"map_type",label:"Map Type",type:"select",setup:function(a){this.setValue(a.getAttribute("cke_googlemaps_"+this.id))},labelLayout:"horizontal","default":"hybrid",items:[["roadmap","roadmap"],["satellite","satellite"],["hybrid","hybrid"],["terrain","terrain"]]}]}]}});