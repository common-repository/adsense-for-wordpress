function pickColor(color) {
	ColorPicker_targetInput.value = color;
	kUpdate(ColorPicker_targetInput.id);
}
function PopupWindow_populate(contents) {
	contents += '<br /><p style="text-align:center;margin-top:0px;"><input type="button" class="button-secondary" value="Close clock picker" onclick="cp.hidePopup(\'prettyplease\')"></input></p>';
	this.contents = contents;
	this.populated = false;
}
function PopupWindow_hidePopup(magicword) {
	if ( magicword != 'prettyplease' )
		return false;
	if (this.divName != null) {
		if (this.use_gebi) {
			document.getElementById(this.divName).style.visibility = "hidden";
		}
		else if (this.use_css) {
			document.all[this.divName].style.visibility = "hidden";
		}
		else if (this.use_layers) {
			document.layers[this.divName].visibility = "hidden";
		}
	}
	else {
		if (this.popupWindow && !this.popupWindow.closed) {
			this.popupWindow.close();
			this.popupWindow = null;
		}
	}
	return false;
}
function PopupWindow_showPopup(anchorname) {
	this.getXYPosition(anchorname);
	this.x += this.offsetX;
	this.y += this.offsetY;
	if (!this.populated && (this.contents != "")) {
		this.populated = true;
		this.refresh();
		}
	if (this.divName != null) {
		// Show the DIV object
		if (this.use_gebi) {
			document.getElementById(this.divName).style.left = this.x + "px";
			document.getElementById(this.divName).style.top = this.y + "px";
			document.getElementById(this.divName).style.visibility = "visible";
			}
		else if (this.use_css) {
			document.all[this.divName].style.left = this.x + "px";
			document.all[this.divName].style.top = this.y + "px";
			document.all[this.divName].style.visibility = "visible";
			}
		else if (this.use_layers) {
			document.layers[this.divName].left = this.x + "px";
			document.layers[this.divName].top = this.y + "px";
			document.layers[this.divName].visibility = "visible";
			}
		}
	else {
		if (this.popupWindow == null || this.popupWindow.closed) {
			// If the popup window will go off-screen, move it so it doesn't
			if (this.x<0) { this.x=0; }
			if (this.y<0) { this.y=0; }
			if (screen && screen.availHeight) {
				if ((this.y + this.height) > screen.availHeight) {
					this.y = screen.availHeight - this.height;
					}
				}
			if (screen && screen.availWidth) {
				if ((this.x + this.width) > screen.availWidth) {
					this.x = screen.availWidth - this.width;
					}
				}
			var avoidAboutBlank = window.opera || ( document.layers && !navigator.mimeTypes['*'] ) || navigator.vendor == 'KDE' || ( document.childNodes && !document.all && !navigator.taintEnabled );
			this.popupWindow = window.open(avoidAboutBlank?"":"about:blank","window_"+anchorname,this.windowProperties+",width="+this.width+",height="+this.height+",screenX="+this.x+",left="+this.x+",screenY="+this.y+",top="+this.y+"");
			}
		this.refresh();
		}
	} // End func PopupWindow_showPopup
function colorSelect(t,p) {
	if ( cp.p == p && document.getElementById(cp.divName).style.visibility != "hidden" )
		cp.hidePopup('prettyplease');
	else {
		cp.p = p;
		cp.select(t,p);
	}
}
function PopupWindow_setSize(width,height) {
	this.width = 162;
	this.height = 210;
}
var cp = new ColorPicker();
function advUpdate(val, obj) {
	document.getElementById(obj).value = val;
	kUpdate(obj);
}
function kUpdate(oid) {
	// 边框颜色
	var list = new Array();
	list['f_color_border']							= 'pick1';
	list['f_color_title']								= 'pick2';
	list['f_color_background']					= 'pick3';
	list['f_color_text']								= 'pick4';
	list['f_color_anchor']							= 'pick5';
	
	list['f_color_border_single']				= 'pick10';
	list['f_color_title_single']				= 'pick11';
	list['f_color_background_single']		= 'pick12';
	list['f_color_text_single']					= 'pick13';
	list['f_color_anchor_single']				= 'pick14';
	
	if ( !list[ oid ] ) return false;
	document.getElementById( list[oid] ).style.backgroundColor = document.getElementById( oid ).value;
}