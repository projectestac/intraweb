/* This compressed file is part of Xinha. For uncompressed sources, forum, and bug reports, go to xinha.org */
/* This file is part of version 0.96beta2 released Fri, 20 Mar 2009 11:01:14 +0100 */
function SuperClean(b,a){this.editor=b;var c=this;b._superclean_on=false;b.config.registerButton("superclean",this._lc("Clean up HTML"),[_editor_url+"iconsets/Tango/ed_buttons_main.png",6,4],true,function(f,g,d){c._superClean(null,d)});b.config.addToolbarElement("superclean","killword",0)}SuperClean._pluginInfo={name:"SuperClean",version:"1.0",developer:"James Sleeman, Niko Sams",developer_url:"http://www.gogo.co.nz/",c_owner:"Gogo Internet Services",license:"htmlArea",sponsor:"Gogo Internet Services",sponsor_url:"http://www.gogo.co.nz/"};SuperClean.prototype._lc=function(a){return Xinha._lc(a,"SuperClean")};Xinha.Config.prototype.SuperClean={tidy_handler:Xinha.getPluginDir("SuperClean")+"/tidy.php",filters:{tidy:Xinha._lc("General tidy up and correction of some problems.","SuperClean"),word_clean:Xinha._lc("Clean bad HTML from Microsoft Word","SuperClean"),remove_faces:Xinha._lc('Remove custom typefaces (font "styles").',"SuperClean"),remove_sizes:Xinha._lc("Remove custom font sizes.","SuperClean"),remove_colors:Xinha._lc("Remove custom text colors.","SuperClean"),remove_lang:Xinha._lc("Remove lang attributes.","SuperClean"),remove_fancy_quotes:{label:Xinha._lc("Replace directional quote marks with non-directional quote marks.","SuperClean"),checked:false}},show_dialog:false};SuperClean.filterFunctions={};SuperClean.prototype.onGenerateOnce=function(){if(this.editor.config.tidy_handler){this.editor.config.SuperClean.tidy_handler=this.editor.config.tidy_handler;this.editor.config.tidy_handler=null}if(!this.editor.config.SuperClean.tidy_handler&&this.editor.config.filters.tidy){this.editor.config.filters.tidy=null}SuperClean.loadAssets();this.loadFilters()};SuperClean.prototype.onUpdateToolbar=function(){if(!(SuperClean.methodsReady&&SuperClean.html)){this.editor._toolbarObjects.superclean.state("enabled",false)}else{this.onUpdateToolbar=null}};SuperClean.loadAssets=function(){var self=SuperClean;if(self.loading){return}self.loading=true;Xinha._getback(Xinha.getPluginDir("SuperClean")+"/pluginMethods.js",function(getback){eval(getback);self.methodsReady=true});Xinha._getback(Xinha.getPluginDir("SuperClean")+"/dialog.html",function(getback){self.html=getback})};SuperClean.prototype.loadFilters=function(){var sc=this;for(var filter in this.editor.config.SuperClean.filters){if(/^(remove_colors|remove_sizes|remove_faces|remove_lang|word_clean|remove_fancy_quotes|tidy)$/.test(filter)){continue}if(!SuperClean.filterFunctions[filter]){var filtDetail=this.editor.config.SuperClean.filters[filter];if(typeof filtDetail.filterFunction!="undefined"){SuperClean.filterFunctions[filter]=filterFunction}else{Xinha._getback(Xinha.getPluginDir("SuperClean")+"/filters/"+filter+".js",function(func){eval("SuperClean.filterFunctions."+filter+"="+func+";");sc.loadFilters()})}return}}};