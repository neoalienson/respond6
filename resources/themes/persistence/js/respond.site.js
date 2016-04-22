var respond = respond || {};

respond.site = {
	
	// site settings
	settings: {},
	
	// payment settings
	payment: {},
	
	// options
	options: null,

	// init respond.site
	init:function(){
		
		var language = respond.site.getLanguage();
		
		// set direction
		if(sessionStorage['respond-direction'] != null){
			
			var direction = sessionStorage['respond-direction'];
			
			// translate if the set language is not the default
			if(direction != respond.site.settings.Direction){
				// set language in html
				document.querySelector('html').setAttribute('dir', direction);
			}
			
		}
		else{
			sessionStorage['respond-direction'] = respond.site.settings.Direction;
		}
		
		// setup prettyprint
		prettyPrint();
		
		// setup lightbox
		respond.site.setupLightbox(document);
		
		// load Facebook API
		var fbroot = document.createElement('div');
		fbroot.setAttribute('id', 'fb-root');
		
		document.body.appendChild(fbroot);
		
		var parts = language.split('-');
	
		var fblang = 'en_US';
		
		if(parts.length == 1){
			fblang = parts[0];
		}
		else if(parts.length == 2){
			fblang = parts[0] + '_' + parts[0].toUpperCase();
		}
		
		// setup Facebook sdk
		(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = '//connect.facebook.net/' + fblang + '/sdk.js#xfbml=1&version=v2.3';
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		
		
	},
	
	// translates a page
	translate:function(language){
	
		// select elements
		var els = document.querySelectorAll('[data-i18n]');
	
		// walk through elements
		for(x=0; x<els.length; x++){
			var id = els[x].getAttribute('data-i18n');
		
			// set id to text if empty
			if(id == ''){
				id = els[x].innerText();
			}
			
			// translate
			var html = respond.site.i18n(id);
			
			els[x].innerHTML = html;
		}
		
	},
	
	// translates a text string
	i18n:function(text){
		
		// setup i18next (if not setup)
		if(respond.site.options == null){
			
			var language = respond.site.settings.Language
			
			if(sessionStorage['respond-language'] != null){
				language = sessionStorage['respond-language'];
			}
			
			// set language
			respond.site.options = {
		        lng: language,
		        getAsync : false,
		        useCookie: false,
		        useLocalStorage: false,
		        fallbackLng: 'en',
		        resGetPath: 'locales/__lng__/__ns__.json',
		        defaultLoadingValue: ''
		    };
		
			i18n.init(respond.site.options);
			
		}
		
		return i18n.t(text);	
	},
	
	// set current language
	setLanguage:function(language){
		
		i18n.setLng(language, function(t) { /* loading done */ 
			sessionStorage['respond-language'] = language;
			respond.site.translate(language);
		});
	},
	
	// set current direction
	setDirection:function(direction){
		
		sessionStorage['respond-direction'] = direction;
		
	},
	
	// gets a QueryString by name
	getQueryStringByName:function(name){
		  name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
		  var regexS = "[\\?&]" + name + "=([^&#]*)";
		  var regex = new RegExp(regexS);
		  var results = regex.exec(window.location.href);
		  if(results == null)
		    return null;
		  else
		    return decodeURIComponent(results[1].replace(/\+/g, " "));
	},
	
	// replaces all occurances for a string
	replaceAll:function(src, stringToFind, stringToReplace){
	  	var temp = src;
	
		var index = temp.indexOf(stringToFind);
		
		while(index != -1){
			temp = temp.replace(stringToFind,stringToReplace);
			index = temp.indexOf(stringToFind);
		}
		
		return temp;
	},
	
	// sets up the lightbox for a given node
	setupLightbox:function(node){
		
		var els = $(node).find('[respond-lightbox]');
		
		// walk through elements
		for(x=0; x<els.length; x++){
			
			var href = $(els[x]).attr('href');
			
			var ext = href.split('.').pop().toUpperCase();
			
			if(ext == 'JPG' || ext == 'PNG' || ext == 'GIF'){
				popupType = 'image';
			}
			else{
				popupType = 'iframe';
			}
			
			if(jQuery().magnificPopup){
				$(els[x]).magnificPopup({ 
				  type: popupType
				});
			}
			
		}
		
		// setup lightbox
		if(jQuery().magnificPopup){
			$('[respond-gallery]').magnificPopup({ 
			  type: 'image',
			  gallery:{
				  enabled: true
			  }
			});
		}
		
	},
	
	// get language
	getLanguage: function(){
		
		var language = respond.site.settings.Language;
		
		// translate if needed
		if(sessionStorage['respond-language'] != null){
			
			var language = sessionStorage['respond-language'];
			
			// translate if the set language is not the default
			if(language != respond.site.settings.Language){
				respond.site.translate(language);
			}
			
		}
		else{
			sessionStorage['respond-language'] = respond.site.settings.Language;
		}
		
		return language;
	}
	
};

// fire init
document.addEventListener("DOMContentLoaded", function(event) { 
  respond.site.init();
});


// shims for old browsers
if (!String.prototype.trim) {
   String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g, '');};
}

// set polymer to use shady dom
Polymer = {dom: 'shady'};