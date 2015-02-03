var JqueryDialog = {
	
	"cBackgroundColor"			:	"#2f5ca0",
	"cBorderSize"				:	2,
	"cBorderColor"				:	"#2f5ca0",

	"cHeaderBackgroundColor"	:	"#2f5ca0",
	"cCloseTitle"				:	"Close",
	
	"cBottomBackgroundColor"	:	"#2f5ca0",
	//"cSubmitText"				:	"确 认",
	//"cCancelText"				:	"取 消",
	
	"cDragTime"					:	"100",

	Open:function(dialogTitle, iframeSrc, iframeWidth, iframeHeight){
		JqueryDialog.init(dialogTitle, iframeSrc, iframeWidth, iframeHeight, true, true, true);
	},
	Open1:function(dialogTitle, iframeSrc, iframeWidth, iframeHeight, isSubmitButton, isCancelButton, isDrag){
		JqueryDialog.init(dialogTitle, iframeSrc, iframeWidth, iframeHeight, isSubmitButton, isCancelButton, isDrag);
	},
	
	init:function(dialogTitle, iframeSrc, iframeWidth, iframeHeight, isSubmitButton, isCancelButton, isDrag){
		/********************************/
		/********************************/
		parent.jQuery("embed").css("display","none");
		/********************************/
		/********************************/

		var _client_width = document.body.clientWidth;
		var _client_height = document.documentElement.scrollHeight;

		//document.documentElement.scrollTop = "0px";
		var scrollTop = jQuery(document).scrollTop();

		//create shadow
		if(typeof(jQuery("#jd_shadow")[0]) == "undefined"){
			//jQuery("body").prepend("<div id='jd_shadow' onclick='JqueryDialog.Close();'>&nbsp;</div>");
			jQuery("body").prepend("<div id='jd_shadow'>&nbsp;</div>");
			var _jd_shadow = jQuery("#jd_shadow");
			_jd_shadow.css("width", _client_width + "px");
			_jd_shadow.css("height", (_client_height) + "px");
		}


		//create dialog
		if(typeof(jQuery("#jd_dialog")[0]) != "undefined"){
			jQuery("#jd_dialog").remove();
		}
		jQuery("body").prepend("<div id='jd_dialog'></div>");
	
		//dialog location
		var _jd_dialog = jQuery("#jd_dialog");
		var _left = (_client_width - (iframeWidth + JqueryDialog.cBorderSize * 2 + 5)) / 2;
		_jd_dialog.css("left", (_left < 0 ? 0 : _left) + document.documentElement.scrollLeft + "px");
		
		var _top = scrollTop + ((document.documentElement.clientHeight - (iframeHeight + JqueryDialog.cBorderSize * 2 + 30 + 50 + 5)) / 2 ) + 30;

		_jd_dialog.css("top", (_top < 0 ? 30 : _top) );

		//create dialog shadow
		_jd_dialog.append("<div id='jd_dialog_s'>&nbsp;</div>");
		var _jd_dialog_s = jQuery("#jd_dialog_s");
		//iframeWidth + double border
		_jd_dialog_s.css("width", iframeWidth + JqueryDialog.cBorderSize * 2 + "px");
		//iframeWidth + double border + header + bottom
		_jd_dialog_s.css("height", iframeHeight + JqueryDialog.cBorderSize * 2 + 30 + 50 + "px");

		//create dialog main
		_jd_dialog.append("<div id='jd_dialog_m'></div>");
		var _jd_dialog_m = jQuery("#jd_dialog_m");
//		_jd_dialog_m.css("border", JqueryDialog.cBorderColor + " " + JqueryDialog.cBorderSize + "px solid");
		_jd_dialog_m.css("width", iframeWidth + "px");
//		_jd_dialog_m.css("background-color", JqueryDialog.cBackgroundColor);
	
		//header
		_jd_dialog_m.append("<div id='jd_dialog_m_h'></div>");
		var _jd_dialog_m_h = jQuery("#jd_dialog_m_h");
		_jd_dialog_m_h.css("background-color", JqueryDialog.cHeaderBackgroundColor );

		//header left
		_jd_dialog_m_h.append("<span id='jd_dialog_m_h_l'>" + dialogTitle + "</span>");
		_jd_dialog_m_h.append("<span id='jd_dialog_m_h_r' onclick='JqueryDialog.Close();'></span>");

		//body
		_jd_dialog_m.append("<div id='jd_dialog_m_b'></div>");
		_jd_dialog_m.append("<div id='jd_dialog_m_b_1'></div>");
		var _jd_dialog_m_b_1 = jQuery("#jd_dialog_m_b_1");
		_jd_dialog_m_b_1.css("top", "30px");
		_jd_dialog_m_b_1.css("width", iframeWidth + "px");
		_jd_dialog_m_b_1.css("height", iframeHeight + "px");
		_jd_dialog_m_b_1.css("display", "none");
		
		_jd_dialog_m.append("<div id='jd_dialog_m_b_2'></div>");
		jQuery("#jd_dialog_m_b_2").append("<iframe id='jd_iframe' src='"+iframeSrc+"' scrolling='no' frameborder='0' width='"+iframeWidth+"' height='"+iframeHeight+"' style='visibility:hidden' onload='this.style.visibility=\"visible\"' />");

		//bottom
		/*
		_jd_dialog_m.append("<div id='jd_dialog_m_t' style='background-color:"+JqueryDialog.cBottomBackgroundColor+";'></div>");
		var _jd_dialog_m_t = jQuery("#jd_dialog_m_t");
		if(isSubmitButton){
			_jd_dialog_m_t.append("<span><input id='jd_submit' value='"+JqueryDialog.cSubmitText+"' type='button' onclick='JqueryDialog.Ok();' /></span>");
		}
		if(isCancelButton){
			_jd_dialog_m_t.append("<span class='jd_dialog_m_t_s'><input id='jd_cancel' value='"+JqueryDialog.cCancelText+"' type='button' onclick='JqueryDialog.Close();' /></span>");
		}
		*/
		//register drag
		if(isDrag){
			DragAndDrop.Register(_jd_dialog[0], _jd_dialog_m_h[0]);
		}
	},
	
	/// <summary>关闭模态窗口</summary>
	Close:function(){
		jQuery("#jd_shadow").remove();
		jQuery("#jd_dialog").remove();

		/********************************/
		/********************************/
		parent.jQuery("embed").css("display","");
		/********************************/
		/********************************/
	},
	
	/// <summary>提交</summary>
	/// <remark></remark>
	Ok:function(){
		var frm = jQuery("#jd_iframe");	
		if (frm[0].contentWindow.Ok()){
			JqueryDialog.Close() ;
		}
		else{
			frm[0].focus() ;
		}
	},
	
	SubmitCompleted:function(alertMsg, isCloseDialog, isRefreshPage){
		if(jQuery.trim(alertMsg).length > 0 ){
			alert(alertMsg);
		}
    	if(isCloseDialog){
			JqueryDialog.Close();
			if(isRefreshPage){
				window.location.href = window.location.href;
			}
		}
	}
};

var DragAndDrop = function(){
	
	var _clientWidth;
	var _clientHeight;
		
	var _controlObj;
	var _dragObj;
	var _flag = false;
	
	var _dragObjCurrentLocation;
	var _mouseLastLocation;
	
	var getElementDocument = function(element){
		return element.ownerDocument || element.document;
	};
	
	var dragMouseDownHandler = function(evt){

		if(_dragObj){
			
			evt = evt || window.event;
			
			_clientWidth = document.body.clientWidth;
			_clientHeight = document.documentElement.scrollHeight;
			
			jQuery("#jd_dialog_m_b_1").css("display", "");
						
			_flag = true;
			
			_dragObjCurrentLocation = {
				x : jQuery(_dragObj).offset().left,
				y : jQuery(_dragObj).offset().top
			};
	
			_mouseLastLocation = {
				x : evt.screenX,
				y : evt.screenY
			};
			
			jQuery(document).bind("mousemove", dragMouseMoveHandler);
			jQuery(document).bind("mouseup", dragMouseUpHandler);
			
			if(evt.preventDefault)
				evt.preventDefault();
			else
				evt.returnValue = false;
			
		}
	};
	
	var dragMouseMoveHandler = function(evt){
		if(_flag){

			evt = evt || window.event;
			
			var _mouseCurrentLocation = {
				x : evt.screenX,
				y : evt.screenY
			};
			
			_dragObjCurrentLocation.x = _dragObjCurrentLocation.x + (_mouseCurrentLocation.x - _mouseLastLocation.x);
			_dragObjCurrentLocation.y = _dragObjCurrentLocation.y + (_mouseCurrentLocation.y - _mouseLastLocation.y);
			
			_mouseLastLocation = _mouseCurrentLocation;
			
			jQuery(_dragObj).css("left", _dragObjCurrentLocation.x + "px");
			jQuery(_dragObj).css("top", _dragObjCurrentLocation.y + "px");
			
			if(evt.preventDefault)
				evt.preventDefault();
			else
				evt.returnValue = false;
		}
	};
	
	var dragMouseUpHandler = function(evt){
		if(_flag){
			evt = evt || window.event;
			
			jQuery("#jd_dialog_m_b_1").css("display", "none");
			
			cleanMouseHandlers();
			_flag = false;
		}
	};
	
	var cleanMouseHandlers = function(){
		if(_controlObj){
			jQuery(_controlObj.document).unbind("mousemove");
			jQuery(_controlObj.document).unbind("mouseup");
		}
	};
	
	return {
		Register : function(dragObj, controlObj){
			_dragObj = dragObj;
			_controlObj = controlObj;
			jQuery(_controlObj).bind("mousedown", dragMouseDownHandler);			
		}
	}

}();

//-->