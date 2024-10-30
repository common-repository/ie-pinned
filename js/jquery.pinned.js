(function($) {
	//Custom pin
	//task
	//sep
	$.fn.pinned = function(options, callback) {
		var empty = options;
		if(ieCheck())
			return this;

		//Get settings
		var loca = document.location + "wp-content/plugins/ie-pinned/get.php?job=settings";
		var defaultOptions = {
            show_post: '', 
            title: ''
        }
		o = $.extend(true, {}, defaultOptions, options);

        return this.each(function() {
			//Clear the list first
	        $(this).clearList();
			$.getJSON(loca, function(sett) {
				
		        o.title = (o.title ? o.title : sett.post_title);
		     	$(this).setCustomTitle({title: o.title});
		     	//Do call back before anything
		        
		     	o.show_post = (o.show_post ? o.show_post : sett.post);
	            //Show Posts
	            if(o.show_post == "true") {
		            loca = document.location + "wp-content/plugins/ie-pinned/get.php?job=posts";
		            $.getJSON(loca, function(data) {
		            	for(i = data.length - 1; i >= 0; i--) {
		            		$(this).addItem({"name": data[i].title, "url": data[i].url,"icon": data[i].icon});
		            	}
		            	//doCallback(callback, empty);
		            });
				} else {
					//doCallback(callback, empty);
				}
				
	        });
	        //Show the completed jump list	    
			$(this).showList();
			
        }); 
    }
    function doCallback(callback, empty) {
	   	if (typeof callback == 'function') {
			callback.call(this);
		} else if (typeof empty == 'function') {
			empty.call(this);
			console.log("tset");
		}
    }
    //Create custom list item
    $.fn.addItem = function(options) {
    	if(ieCheck())
    		return this;
    	
    	var defaultOptions = {
    		name: 'item_name',
    		url: 'item_url',
    		icon: 'favicon.ico'
    	}
    	o = $.extend({}, defaultOptions, options);
    	return this.each(function() {
    		try {
    			if ($(this).isSiteMode()) {
			    	window.external.msSiteModeAddJumpListItem(o.name, o.url, o.icon);
			    	//console.log(o.name);
		        }
    		} catch(ex) {}
    	});
    }
    //Set custom title - ability for future updates if they allow multiple jump lists
    $.fn.setCustomTitle = function(options) {
		if(ieCheck())
			return this;

		var defaultOptions = {
            title: 'Latest Posts'
        }
		o = $.extend({}, defaultOptions, options);

        return this.each(function() {
			try {				
		        if ($(this).isSiteMode()) {
			    	window.external.msSiteModeCreateJumpList(o.title);
		        }
		    }
		    catch (ex) {
		        // Fail silently.
		    }
        }); 
    }

    //Set up the pin
    $.fn.isSiteMode = function() {
    	if(ieCheck())
			return this;
		try {
			return window.external.msIsSiteMode();
		} catch (ex) {
			//Die without a sound
		}
    }
    //Clear the custom Jump List
    $.fn.clearList = function() {
    	if(ieCheck())
			return this;
		try {
			window.external.msSiteModeClearJumpList();
		} catch (ex) {
			//Die without a sound
		}
    }
	//Show the custom Jump List
	$.fn.showList = function() {
		if(ieCheck())
			return this;
		try {
			window.external.msSiteModeShowJumplist();
		} catch (ex) {
			//Sleep with the fishes
		}
    }
	//Make the taskbar icon flash
	$.fn.flashIcon = function() {
		if(ieCheck())
			return this;
		try {
			window.external.msSiteModeActivate();
		} catch (ex) {
			//Sleep with the fishes
		}
	}
    //Make sure its IE
    function ieCheck() {
		if($.browser.msie == undefined || $.browser.version < 9) {
			return true;
		} else {
			return false;
		}
    }
    /*
    Blank Function
    $.fn.#### = function() {
    	return this.each(function() {
    	
    	}
    }
    */
})(jQuery);
//});