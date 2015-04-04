var uiElements = $H();

/*--- Custom Exceptions ---------------------------------------------------------------------------*/

var UIException = Class.create({

	_exceptionTypes: {
	
		ElementDoesNotExistException: {
			message: 'The specified DOM element does not exist, but is required for this object to function'
		},
		
		MissingRelSettingException: {
			message: 'An HTML option from an element\'s rel attribute is required, but not found'
		}
		
 	},
 	
 	initialize: function(exceptionType) {
 		this._exceptionType = exceptionType;
 	},
 	
 	toString: function() {
 		return this._exceptionTypes[this._exceptionType].message;
 	},
 	
 	addExceptionType: function(newExceptionType) {
 		UIException.prototype._exceptionTypes[newExceptionType.name] = { message: newExceptionType.message }
 	}
	
});



/*--- UIFormListener ---*/

var UIFormListener = Class.create({

	initialize: function(element) {
	
		this.element = $(element);
		this.element.identify();
		
		this.options = Object.extend({
			message:	'There are unsaved changes on this page. Are you sure you want to leave?'
		}, arguments[1] || {});
		
		this._keys = $A();
		
		this._init();
	
	},
	
	_init: function() {
		
		this.element.getElements().each(function(elm) {

			elm.observe(elm.type == 'radio' || elm.type == 'checkbox' ? 'change' : 'focus', function() {
				this.alert = true;
			}.bindAsEventListener(this));
			
		}.bind(this));
		
		window.onbeforeunload = this._onBeforeUnload.bindAsEventListener(this);
		
	},
	
	_onFormSubmit	: function(e) {
		this.alert = false;
	},
	
	_onBeforeUnload	: function(e) {
		if (this.alert)
			return this.options.message;
	},
	
	_loadHtmlOptions: function() {
	
		if (this.element.getAttribute('rel')) {
	
			// Load the HTML options
			var attributes = this.element.getAttribute('rel').split(';');
	
			for (var i = 0; i < attributes.length; i++) {
			
				var parts = attributes[i].split('=');
				
				var optionValue;
				
				if (this._getAvailableOptions().include(parts[0])) {

					switch (parts[0]) {
							
						default:
							optionValue = parts[1];
							break;
						
					}

					this.options[parts[0]] = optionValue;
					
				}
				
			}
			
		}

	},
	
	_getAvailableOptions: function() {
		if (!this._keys.length) {
			for (var key in this.options)
				this._keys.push(key);
		}
		return this._keys;
	},
	
});

Event.observe(document, 'dom:loaded', function() {
	$$('.uiFormListener').each(function(elm) {
		uiElements.set(elm.identify(), new UIFormListener(elm));
	});
});





/*--- UIAutoCheck ---*/

var UIAutoCheck = Class.create({

	initialize: function(element) {
	
		this.element = $(element);
		this.element.identify();
		
		this.options = Object.extend({
		
		}, arguments[1] || {});
		
		this._keys = $A();
		
		this._init();
	
	},
	
	_init: function() {

		if (this._isSelect())
			this.element.observe('change', this._doSelectChange.bind(this));
		else if (this._isCheckbox())
			this.element.observe('click', this._doCheckboxClick.bind(this));
			
		this.checkboxes = $$('.' + this._getAutoCheckId());
	
	},
	
	_doSelectChange: function(e) {
		this._doSelects(this.element.options[this.element.selectedIndex].getAttribute('rel'), true);
	},
	
	_doCheckboxClick: function(e) {
		this._doSelects(this.element.getAttribute('rel'), this.element.checked);
	},
	
	_doSelects: function(elmList, check) {

		var checkElms = new Array();
		var uncheckElms = new Array();
		
		var wildcardAction = '';
	
		var attributes = elmList.split(';');
	
		for (var i = 0; i < attributes.length; i++) {
		
			var parts = attributes[i].split('=');
			var elmCollection = new Array();
			
			if (parts[1] == 'all')
				elmCollection = this.checkboxes.pluck('id');
			else if (parts[1] == '*')
				wildcardAction = parts[0];
			else
				elmCollection = parts[1].split(',');
			
			if (parts[0] == 'check')
				checkElms = elmCollection;
			else if (parts[0] == 'uncheck')
				uncheckElms = elmCollection;
				
		}
		
		if (wildcardAction == 'check')
			checkElms = this.checkboxes.pluck('id').collect(function(elm) {
				return uncheckElms.indexOf(elm) > -1 ? null : elm;
			});
		else if (wildcardAction == 'uncheck')
			uncheckElms = this.checkboxes.pluck('id').collect(function(elm) {
				return checkElms.indexOf(elm) > -1 ? null : elm;
			});
			
		// If the call is to uncheck, then we ignore the 'uncheck' element list and only uncheck the ones which were checked by this object to begin with
		if (!check)
			uncheckElms = checkElms;
			
		checkElms.compact().each(function(elm) {
			$(elm).checked = true;
		});

		uncheckElms.compact().each(function(elm) {
			$(elm).checked = false;
		});
	},
	
	_getAutoCheckId: function() {

		if (this._isSelect())
			return 'uiAutoCheck-' + this.element.identify();
		else if (this._isCheckbox())
			return 'uiAutoCheck-' + /^([\w\d]+)_/.exec(this.element.identify())[1];
			
	},
	
	_isSelect: function() {
		return this.element.tagName.toLowerCase() == 'select';
	},
	
	_isCheckbox: function() {
		return this.element.tagName.toLowerCase() == 'input' && this.element.type.toLowerCase() == 'checkbox';
	},
	
	_loadHtmlOptions: function() {
	
		if (this.element.getAttribute('rel')) {
	
			// Load the HTML options
			var attributes = this.element.getAttribute('rel').split(';');
	
			for (var i = 0; i < attributes.length; i++) {
			
				var parts = attributes[i].split('=');
				
				var optionValue;
				
				if (this._getAvailableOptions().include(parts[0])) {

					switch (parts[0]) {
							
						default:
							optionValue = parts[1];
							break;
						
					}

					this.options[parts[0]] = optionValue;
					
				}
				
			}
			
		}

	},
	
	_getAvailableOptions: function() {
		if (!this._keys.length) {
			for (var key in this.options)
				this._keys.push(key);
		}
		return this._keys;
	},

});

Event.observe(document, 'dom:loaded', function() {
	$$('.uiAutoCheck').each(function(elm) {
		uiElements.set(elm.identify(), new UIAutoCheck(elm));
	});
});





/*--- UIPasswordStrength ---*/

var UIPasswordStrength = Class.create({

	initialize: function(element) {
	
		this.element = $(element);
		this.element.identify();
	
		this.options = Object.extend({
			field:			'',
			label:			'Password Strength',
			match:			'',
			require:		'letters,numbers,symbols',
			requireCount:	2,
			minLength:		7,
			secureLength:	10
		}, arguments[1] || {});
		
		this._keys = $A();
		
		this.score = 0;
		this.previousScore = 0;
		this.valid = false;
		
		this.ratings = $A(new Array('', 'Bad', 'Weak', 'Moderate', 'Good', 'Secure'));

		this._init();
	
	},
	
	_init: function() {
	
		if (!this.element.hasClassName('uiInitialized')) {

			this._loadHtmlOptions();
			
			this.fieldElm = $(this.options.field);
			
			this.ratings.each(function(rating) {
				if (rating == '') return;
				this.element.insert(this._createLabelObject(rating));
			}.bind(this));

			this.element.insert({
				bottom: this.options.label
			});
			
			this.fieldElm.observe('keyup', this._validate.bind(this));
			
			if (this.options.match && $(this.options.match))
				$(this.options.match).observe('keyup', this._validateMatch.bind(this));

			this.element.addClassName('uiInitialized');
			
		}
	
	},
	
	_createLabelObject: function(label) {
		var elm = new Element('div', {
			className: 'uiPasswordLabel ui' + label + 'PasswordLabel',
		}).update(label);
		
		elm.hide();
		
		return elm;
		
	},
	
	_loadHtmlOptions: function() {
	
		if (this.element.getAttribute('rel')) {
	
			// Load the HTML options
			var attributes = this.element.getAttribute('rel').split(';');
	
			for (var i = 0; i < attributes.length; i++) {
			
				var parts = attributes[i].split('=');
				
				var optionValue;
				
				if (this._getAvailableOptions().include(parts[0])) {

					switch (parts[0]) {
							
						default:
							optionValue = parts[1];
							break;
						
					}

					this.options[parts[0]] = optionValue;
					
				}
				
			}
			
		}

	},
	
	_getAvailableOptions: function() {
		if (!this._keys.length) {
			for (var key in this.options)
				this._keys.push(key);
		}
		return this._keys;
	},
	
	_validate: function(e) {
	
		this.previousScore = this.score;
		this.score = 0;
		
		var value = $F(this.fieldElm);
		
		var hasLetter = false;
		var hasNumber = false;
		var hasSymbol = false;
		var requirements = 0;
		
		if (/[a-z]/.exec(value)) {
			this.score++;
			hasLetter = true;
		}
			
		if (/[A-Z]/.exec(value)) {
			this.score++;
			hasLetter = true;
		}
			
		if (/[0-9]/.exec(value)) {
			this.score++;
			hasNumber = true;
		}
			
		if (/[`~!@#$%^&*{}\[\];:'"<,>.\/\\=-]/.exec(value)) {
			this.score++;
			hasSymbol = true;
		}
			
		if (value.length >= this.options.secureLength)
			this.score++;
			
		if (hasLetter)
			requirements++;
			
		if (hasNumber)
			requirements++;
			
		if (hasSymbol)
			requirements++;

		if (requirements < this.options.requireCount || value.length < this.options.minLength)
			this.valid = false;
		else
			this.valid = true;
			
		this._update();
		
		if (this.options.match && $F(this.options.match).length > 0)
			this._validateMatch();
	
	},
	
	_validateMatch: function() {
	
		var elm = $(this.options.match);

		if ($F(elm) != $F(this.fieldElm) && $F(elm).length > 0) {
			this.element.addClassName('uiNoMatch');
			this.element.update(this.element.innerHTML.replace(this.options.label, 'Passwords Don\'t Match'));
		} else {
			this.element.removeClassName('uiNoMatch');
			this.element.update(this.element.innerHTML.replace('Passwords Don\'t Match', this.options.label));
		}
	
	},
	
	_update: function() {
	
		if (!this.element.visible())
			this.element.show();
	
		if (!this.valid) {
			this.element.addClassName('uiInvalid');
			this.element.update(this.element.innerHTML.replace(this.options.label, 'Invalid Password'));
		} else {
		
			if (this.element.hasClassName('uiInvalid')) {
				this.element.removeClassName('uiInvalid');
				this.element.update(this.element.innerHTML.replace('Invalid Password', this.options.label));
			}
	
			if (this.previousScore != 0)
				this.element.down('div.ui' + this.ratings[this.previousScore] + 'PasswordLabel').hide();
	
			if (this.score != 0)
				this.element.down('div.ui' + this.ratings[this.score] + 'PasswordLabel').show();

			this.element.addClassName('ui' + this.ratings[this.score] + 'Password');
			
			if (this.score != this.previousScore)
				this.element.removeClassName('ui' + this.ratings[this.previousScore] + 'Password');
			
		}
		
	},
	
	isValid: function() {
		return this.valid;
	}

});

Event.observe(document, 'dom:loaded', function() {
	$$('.uiPasswordStrength').each(function(elm) {
		uiElements.set(elm.identify(), new UIPasswordStrength(elm));
	});
});






/*--- UIInlineSearch ---*/

var UIInlineSearch = Class.create({

	initialize: function(element) {

		this.element = $(element);
		this.element.identify();

		this.options = Object.extend({
			submitTo:			'/_ajax/ajax.active_member_search.php',
			minCharacters:		3,
			delay:				300,
			animationDuration:	0.25,
			fitResultsToWidth:	false,
			updateElement:		'',
			afterUpdate:		'',
			afterRemove:		''
		}, arguments[1] || {});
		
		this._keys = $A();
		
		this._init();
	
	},
	
	_init: function() {
	
		this._loadHtmlOptions();
		
		this.itemIdx = 0;
	
		this.element.removeClassName('uiInlineSearch');
		this.element.addClassName('uiText');

		this.wrapper = this.element.wrap('ul', {
			className: 'uiInlineSearch'
		});
		
		this.inputWrapper = this.element.wrap('li', {
			className: 'uiInlineSearchInput'
		});
		
		this.wrapper.setStyle({
			width: (parseInt(this.element.getAttribute('size')) - 11) + 'em'
		});

		if ($F(this.element) && $F(this.options.updateElement)) {
			this.element.up('li').hide();
			this._addSearchResult($F(this.options.updateElement), $F(this.element));
		}
			
		this.element.setValue('');

		this.element.observe('keyup', this._handleKeyPress.bindAsEventListener(this));
		this.element.observe('focus', this._handleInputFocus.bindAsEventListener(this));
		
		this.uiText = new UIText(this.element);
	
	},
	
	_handleInputFocus: function() {

		if ($F(this.element).length >= this.options.minCharacters && this.results && !this.results.visible()) {
		
			Event.observe(this.element, 'blur', this._handleInputBlur.bind(this));

			new Effect.SlideDown(this.results, {
				duration: this.options.animationDuration
			});
			
		}
		
	},
	
	_handleKeyPress: function(e) {

		if ([Event.KEY_UP, Event.KEY_DOWN].indexOf(e.keyCode) > -1 || (e.keyCode == Event.KEY_RETURN && this.results && this.results.visible())) {

			Event.stop(e);

			var matches = this.results.select('li.uiSearchMatch');
			
			if (e.keyCode == Event.KEY_RETURN)
				this._selectItem(matches[this.itemIdx - 1]);
			else if (e.keyCode == Event.KEY_UP && this.itemIdx > 1) {
			
				if (matches[this.itemIdx - 1])
					matches[this.itemIdx - 1].removeClassName('uiCurrentItem');
					
				if (matches[this.itemIdx - 2]) {
					this.itemIdx--;
					matches[this.itemIdx - 1].addClassName('uiCurrentItem');
				}
				
			} else if (e.keyCode == Event.KEY_DOWN && this.itemIdx < matches.length) {
			
				if (matches[this.itemIdx - 1])
					matches[this.itemIdx - 1].removeClassName('uiCurrentItem');
					
				if (matches[this.itemIdx]) {
					this.itemIdx++;
					matches[this.itemIdx - 1].addClassName('uiCurrentItem');
				}
			}
			
		} else {
		
			if ($F(this.element) == this.lastValue)
				return;
				
			if ($F(this.element).length < this.options.minCharacters) {
			
				if (this.results && this.results.visible())
					this._hideSearchResults(false, true);
					
				return;
			
			}
		
			if (this.timeout)
				clearTimeout(this.timeout);
				
			this.lastValue = $F(this.element);
			
			this.timeout = setTimeout(this._doSearch.bind(this), this.options.delay);
			
		}
	
	},
	
	_doSearch: function() {
	
		this.wrapper.addClassName('uiIndicator');
	
		new Ajax.Request(this.options.submitTo, {
			method: 'post',
			onComplete: this._searchComplete.bind(this),
			parameters: {
				action: 	'search',
				searchTerm:	$F(this.element)
			}
		});
	
	},
	
	_searchComplete: function(transport, json) {
	
		this.wrapper.removeClassName('uiIndicator');

		if (!this.wrapper.down('div.uiSearchResults')) {

			this.results = new Element('div', {
				className: 'uiSearchResults',
			}).update(transport.responseText);
		
			this.results.setStyle({
				top: 	(this.wrapper.getHeight() - 2) + 'px'
			});
			
			if (this.options.fitResultsToWidth)
				this.results.setStyle({
					width:	this.wrapper.getWidth() + 'px'
			});
			
			this.results.hide();
			this.wrapper.insert({
				bottom: this.results
			});
			new Effect.SlideDown(this.results, {
				duration: this.options.animationDuration
			});
			
		} else {
			this.results.select('li.uiSearchMatch').invoke('stopObserving', 'click');
			this.results.select('li.uiSearchMatch').invoke('stopObserving', 'mouseover');
			this.results.update(transport.responseText);
		}
		
		Event.observe(this.element, 'blur', this._handleInputBlur.bind(this));
		
		this.results.select('li.uiSearchMatch').invoke('observe', 'mouseover', this._doMouseOver.bind(this));
		this.results.select('li.uiSearchMatch').invoke('observe', 'click', this._doSearchResultClick.bind(this));
		
	},
	
	_addSearchResult: function(resultId, resultText) {
	
		var searchResult = new Element('li', {
			className: 'uiInlineSearchResult',
			id:			'uiResult_' + resultId
		}).update(resultText);
		
		searchResult.insert({
			bottom:	new Element('a', {
				className:	'uiCloseButton',
				href:		'#'
			}).observe('click', this._removeSearchResult.bind(this))
		});

		this.wrapper.insert({
			bottom: searchResult
		});

	},
	
	_removeSearchResult: function(e) {
	
		Event.stop(e);
	
		var elm = Event.findElement(e, 'li');
		
		var resultId = /_(\d+)$/.exec(elm.id);
		
		if (this.options.afterRemove && typeof this.options.afterRemove == 'function')
			this.options.afterRemove(resultId, this.options.updateElement);
		else if (this.options.afterRemove && typeof this.options.afterRemove == 'string') {
			eval(this.options.afterRemove + '(\'' + elm.id + '\', \'' + this.options.updateElement + '\')');
		}
		
		elm.remove();
		
		this.element.up('li').show();
		this.element.focus();
	
	},
	
	_doSearchResultClick: function(e) {
		this._selectItem(Event.findElement(e, 'li'));
	},
	
	_selectItem: function(elm) {

		clearTimeout(this.timeout);
	
		this._addSearchResult(elm.id, this._collectTextNodesIgnoreClass(elm, 'uiIgnore'));
		
		if (this.options.updateElement && $(this.options.updateElement))
			$(this.options.updateElement).setValue(elm.id);
			
		if (this.options.afterUpdate && typeof this.options.afterUpdate == 'function')
			this.options.afterUpdate(elm.id, this.options.updateElement);
		else if (this.options.afterUpdate && typeof this.options.afterUpdate == 'string')
			eval(this.options.afterUpdate + '(\'' + elm.id + '\', \'' + this.options.updateElement + '\')');
		
		this.element.up('li').hide();
		this.element.setValue('');

		this._hideSearchResults(true);
	
	},
	
	_doMouseOver: function(e) {
	
		if (this.results.down('li.uiCurrentItem')) {
			this.results.down('li.uiCurrentItem').removeClassName('uiCurrentItem');
			this.itemIdx = 0;
		}
			
	},
	
	_hideSearchResults: function() {
	
		if (arguments.length && arguments[0]) {
			new Effect.DropOut(this.results, {
				duration: 		this.options.animationDuration,
				afterFinish:	function() {
					if (this.results)
						this.results.remove();
					delete this.results;
					this.itemIdx = 0;
				}.bind(this)
			});
		
		} else {
			new Effect.SlideUp(this.results, {
				duration:	this.options.animationDuration / 2,
				afterFinish:	arguments[1] ? function() {
					if (this.results) {
						this.results.remove();
						delete this.results;
						this.itemIdx = 0;
					}
				}.bind(this) : function(){}
			});
		}
	
	},
	
	// Copied from script.aculo.us (effects.js) collectTextNodesIgnoreClass() function
	_collectTextNodesIgnoreClass: function(element, className) {
		return text = $A($(element).childNodes).collect(function(node) {
			return (node.nodeType==3 ? node.nodeValue :
				((node.hasChildNodes() && !Element.hasClassName(node,className)) ?
				Element.collectTextNodesIgnoreClass(node, className) : ''));
		}).flatten().join('');
	},
	
	_handleInputBlur: function(e) {
		if (this.results)
			this.timeout = setTimeout(this._hideSearchResults.bind(this), 300);
	},
	
	_loadHtmlOptions: function() {
	
		if (this.element.getAttribute('rel')) {

			// Load the HTML options
			var attributes = this.element.getAttribute('rel').split(';');
	
			for (var i = 0; i < attributes.length; i++) {
			
				var parts = attributes[i].split('=');
				
				var optionValue;
				
				if (this._getAvailableOptions().include(parts[0])) {

					switch (parts[0]) {
							
						default:
							optionValue = parts[1];
							break;
						
					}

					this.options[parts[0]] = optionValue;
					
				}
				
			}
			
		}

	},
	
	_getAvailableOptions: function() {
		if (!this._keys.length) {
			for (var key in this.options)
				this._keys.push(key);
		}
		return this._keys;
	}

});

Event.observe(document, 'dom:loaded', function() {
	$$('.uiInlineSearch').each(function(elm) {
		uiElements.set(elm.identify(), new UIInlineSearch(elm));
	});
});





/*--- UIAutoHide ---*/

var UIAutoHide = Class.create({

	initialize: function(element) {

		this.element = $(element);
		this.element.identify();
		
		this.options = Object.extend({
			animationDuration:	0.5,
			animation:			'slide',
			show:				'',
			hide:				'',
			showElements:		$A(),
			hideElements:		$A()
		}, arguments[1] || {});
		
		this._keys = $A();
		
		this._init();
	
	},
	
	_init: function() {
	
		this._loadHtmlOptions();

		this.options.showElements = this._splitIds(this.options.show);
		this.options.hideElements = this._splitIds(this.options.hide);
		
		this.element.observe('click', this._toggleElements.bind(this));
	
	},
	
	_splitIds: function(idList) {

		var splitIds = $A();

		if (typeof(idList == 'string')) {
		
			idList.split(',').each(function(id) {
				if ($(id))
					splitIds.push($(id));
			});
		
		}
		
		return splitIds;
	
	},
	
	_toggleElements: function(e) {

		this.options.showElements.each(function(elm) {
			this._showElement(elm);
		}.bind(this));
		
		this.options.hideElements.each(function(elm) {
			this._hideElement(elm);
		}.bind(this));
		
	},
	
	_showElement: function(elm) {
	
		if (elm.visible())
			return;

		switch (this.options.animation) {
		
			case 'fade':
				new Effect.Appear(elm, {
					duration: this.options.animationDuration
				});
				break;
		
			case 'blind':
				new Effect.BlindDown(elm, {
					duration: this.options.animationDuration
				});
				break;
		
			case 'slide':
			default:
				new Effect.SlideDown(elm, {
					duration: this.options.animationDuration
				});
		
		}
		
	},
	
	_hideElement: function(elm) {
	
		if (!elm.visible())
			return;
		
		switch (this.options.animation) {
		
			case 'fade':
				new Effect.Fade(elm, {
					duration: this.options.animationDuration
				});
				break;
		
			case 'blind':
				new Effect.BlindUp(elm, {
					duration: this.options.animationDuration
				});
				break;
		
			case 'slide':
			default:
				new Effect.SlideUp(elm, {
					duration: this.options.animationDuration
				});
				break;
		
		}
		
	},
	
	_loadHtmlOptions: function() {
	
		if (this.element.getAttribute('rel')) {
	
			// Load the HTML options
			var attributes = this.element.getAttribute('rel').split(';');
	
			for (var i = 0; i < attributes.length; i++) {
			
				var parts = attributes[i].split('=');
				
				var optionValue;
				
				if (this._getAvailableOptions().include(parts[0])) {

					switch (parts[0]) {
							
						default:
							optionValue = parts[1];
							break;
						
					}

					this.options[parts[0]] = optionValue;
					
				}
				
			}
			
		}

	},
	
	_getAvailableOptions: function() {
		if (!this._keys.length) {
			for (var key in this.options)
				this._keys.push(key);
		}
		return this._keys;
	}

});

Event.observe(document, 'dom:loaded', function() {
	$$('.uiAutoHide').each(function(elm) {
		uiElements.set(elm.identify(), new UIAutoHide(elm));
	});
});





/*--- UIMultiDirectionalAccordion ----------------------------------------------------------------*/

var UIMultiDirectionalAccordion = Class.create({

	initialize: function(element) {
	
		this.element = $(element);
		this.element.identify();
		
		this.options = Object.extend({
			animationDuration:	0.5,
			collapsedWidth:		237,
			expandedWidth:		478
		}, arguments[1] || {});
		
		this._keys = $A();
		
		this._init();
	
	},
	
	deinit: function() {
	
		this.element.select('dl.uiExpandedPanel > dt').concat(this.element.select('dl.uiCollapsedPanel > dt')).each(function(elm) {
			elm.stopObserving();
		});
	
	},
	
	_init: function() {
	
		this.currentPanel = this.element.select('dd').detect(function(elm) {
			if (elm.visible()) {
				elm.addClassName('uiCurrentPanel');
				elm.up().removeClassName('uiCollapsedPanel');
				elm.up().addClassName('uiExpandedPanel');
				elm.up().setStyle({
					width: this.options.expandedWidth + 'px'
				});
				return elm;
			}
		}.bind(this));
		
		this.element.select('dd').each(function(elm) {
		
			if (!elm.hasClassName('uiCurrentPanel')) {
			
				elm.hide();
				
				if (!elm.up().down('dd.uiCurrentPanel')) {
					elm.up().removeClassName('uiExpandedPanel');
					elm.up().addClassName('uiCollapsedPanel');
					elm.up().setStyle({
						width: this.options.collapsedWidth + 'px'
					});
				}
				
				
			}
				
			elm.down('ul').wrap('div');
		
		}.bind(this));

		this.element.select('dl.uiExpandedPanel > dt').concat(this.element.select('dl.uiCollapsedPanel > dt')).each(function(elm) {
			elm.identify();
			elm.addClassName('uiClickable');
			elm.observe('click', this._handleClick.bind(this));
			elm.next().id = 'uiChildPanel_' + this._getPanelId(elm);
		}.bind(this));
	
	},
	
	_handleClick: function(e) {

		var elm = Event.findElement(e, 'dt.uiClickable');
		
		if (elm.hasClassName('uiCurrentPanel'))
			return;
			
		elm.addClassName('uiCurrentPanel');

		new Ajax.Request('/_ajax/ui/ajax.multi_directional_accordion.php', {
			method:		'post',
			parameters:	{
				action: 'setPreference',
				name:	'currentPanel',
				value: this._getPanelId(elm)
			}
		});
		
		new Effect.SlideUp('uiChildPanel_' + this._getPanelId(this.currentPanel), {
			duration: this.options.animationDuration,
			afterFinish: function(effect) {
				effect.element.previous().removeClassName('uiCurrentPanel');
			}
		});
		
		new Effect.SlideDown('uiChildPanel_' + this._getPanelId(elm), {
			duration: this.options.animationDuration
		});
		
		if (elm.up() != this.currentPanel.up()) {
		
			new Effect.Morph(this.currentPanel.up(), {
				style: 'width: ' + this.options.collapsedWidth + 'px',
				duration: this.options.animationDuration,
				afterFinish: function(effect) {
					effect.element.addClassName('uiCollapsedPanel');
					effect.element.removeClassName('uiExpandedPanel');
				}
			});
			
			new Effect.Morph(elm.up(), {
				style: 'width: ' + this.options.expandedWidth + 'px',
				duration: this.options.animationDuration,
				afterFinish: function(effect) {
					effect.element.addClassName('uiExpandedPanel');
					effect.element.removeClassName('uiCollapsedPanel');
				}
			});
		
		}
		
		this.currentPanel = elm;

	},
	
	_getPanelId: function(elm) {
		return /_(.+)$/.exec(elm.identify())[1];
	},
	
	_loadHtmlOptions: function() {
	
		if (this.element.getAttribute('rel')) {
	
			// Load the HTML options
			var attributes = this.element.getAttribute('rel').split(';');
	
			for (var i = 0; i < attributes.length; i++) {
			
				var parts = attributes[i].split('=');
				
				var optionValue;
				
				if (this._getAvailableOptions().include(parts[0])) {

					switch (parts[0]) {
							
						default:
							optionValue = parts[1];
							break;
						
					}

					this.options[parts[0]] = optionValue;
					
				}
				
			}
			
		}

	},
	
	_getAvailableOptions: function() {
		if (!this._keys.length) {
			for (var key in this.options)
				this._keys.push(key);
		}
		return this._keys;
	}
	

});

Event.observe(document, 'dom:loaded', function() {
	$$('.uiMultiDirectionalAccordion').each(function(elm) {
		uiElements.set(elm.identify(), new UIMultiDirectionalAccordion(elm));
	});
});





/*--- UISlideOutDrawer ---*/

var UISlideOutDrawer = Class.create({

	initialize: function(element) {
	
		this.element = $(element);
		this.element.identify();
		
		this.options = Object.extend({
			tabIcon:		'cog',
			tabName:		'Page Options',
			width:			250,
			height:			.3,
			minHeight:		225,
			side:			'right',
			tabOpacity:		0.6
		}, arguments[1] || {});
		
		this._keys = $A();
		
		this.visible = false;
		
		this._init();
	
	},
	
	_init: function() {
	
		this._loadHtmlOptions();
		
		this.tabImage = new Element('img', {
			src: '/_images/icons/' + this.options.tabIcon + '.png',
			className: 'icon'
		});
		
		this.tab = new Element('div', {
			className: 'uiSlideOutDrawerTab'
		}).insert({
			top: this.tabImage,
			bottom: this.options.tabName
		});
		
		this.wrapper = Element.wrap(this.element.down('.uiContent'), new Element('div'));
		
		this.wrapper.insert({
			bottom: this.tab
		});
		
		this.tab.observe('click', this.show.bind(this));

//		this.tabImage.observe('load', function() {

			this.element.setStyle({
				height: this.tab.getHeight() + 'px',
				left: '0px'
			});
			
			this.wrapper.setStyle({
				position: 'relative',
				top: (this.element.down('.uiContent').getHeight() * -1) + 'px'
			});
			
//		}.bind(this));
	
	},
	
	show: function(e) {
		
		if (this.isVisible()) {
		
			new Effect.Morph(this.element, {
				duration: 0.5,
				style: 'height: ' + this.tab.getHeight() + 'px'
			});
			
			new Effect.Morph(this.wrapper, {
				duration: 0.5,
				style: 'top: -' + this.element.down('.uiContent').getHeight() + 'px'
			});

			var intPadding = parseInt($('maincontent').getStyle('padding-top').substr(0, $('maincontent').getStyle('padding-top').length - 2)) - this.element.down('.uiContent').getHeight();
			
			new Effect.Morph('maincontent', {
				duration: 0.5,
				style: 'padding-top: ' + intPadding + 'px'
			});
			
		} else {
		
			var intHeight = this.element.down('.uiContent').getHeight() + this.tab.getHeight();
		
			new Effect.Morph(this.element, {
				duration: 0.5,
				style: 'height: ' + intHeight + 'px'
			});
			
			new Effect.Morph(this.wrapper, {
				duration: 0.5,
				style: 'top: 0px'
			});

			var intPadding = parseInt($('maincontent').getStyle('padding-top').substr(0, 2)) + this.element.down('.uiContent').getHeight();
		
			new Effect.Morph('maincontent', {
				duration: 0.5,
				style: 'padding-top: ' + intPadding + 'px'
			});
			
		}
			
		this.visible = !this.visible;
		
	},
	
	isVisible: function() {
		return this.visible;
	},
	
	_loadHtmlOptions: function() {
	
		if (this.element.getAttribute('rel')) {
	
			// Load the HTML options
			var attributes = this.element.getAttribute('rel').split(';');
	
			for (var i = 0; i < attributes.length; i++) {
			
				var parts = attributes[i].split('=');
				
				var optionValue;
				
				if (this._getAvailableOptions().include(parts[0])) {

					switch (parts[0]) {
							
						default:
							optionValue = parts[1];
							break;
						
					}

					this.options[parts[0]] = optionValue;
					
				}
				
			}
			
		}

	},
	
	_getAvailableOptions: function() {
		if (!this._keys.length) {
			for (var key in this.options)
				this._keys.push(key);
		}
		return this._keys;
	},

});

Event.observe(document, 'dom:loaded', function() {
	$$('.uiSlideOutDrawer').each(function(elm) {
		uiElements.set(elm.identify(), new UISlideOutDrawer(elm));
	});
});





/*--- UIText Definition ---*/

var UIText = Class.create({

	initialize: function(element) {
	
		this.element = $(element);
		this.element.identify();
		
		this.options = Object.extend({
			mask: 			'',
			placeholder:	''
		}, arguments[1] || {});
		
		this._keys = $A();
		
		this._init();
	
	},
	
	_init: function() {
	
		this._loadHtmlOptions();

		switch (this.options.mask) {
		
			case 'us':
			case 'usa':
			case 'canada':
			case 'nanp':
				this.element.observe('blur', this.mask.bind(this));
				break;
		
		}
		
		if (this.options.placeholder) {

			if (!$F(this.element))
				this._showPlaceholderText();
		
			this.element.observe('focus', this._doFocus.bindAsEventListener(this));
			this.element.observe('blur', this._doBlur.bindAsEventListener(this));
			
		}
	
	},
	
	_showPlaceholderText: function() {
		this.element.setValue(this.options.placeholder);
		this.element.addClassName('uiPlaceholder');
		this.isPlaceholder = true;
	},
	
	_doFocus: function(e) {
	
		if (this.isPlaceholder && $F(this.element) == this.options.placeholder) {
			this.element.setValue('');
			this.element.removeClassName('uiPlaceholder');
			this.isPlaceholder = false;
		}
	
	},
	
	_doBlur: function(e) {
		if (this.options.placeholder && $F(this.element).empty())
			this._showPlaceholderText();
	},
	
	_loadHtmlOptions: function() {
	
		if (this.element.getAttribute('rel')) {
	
			// Load the HTML options
			var attributes = this.element.getAttribute('rel').split(';');
	
			for (var i = 0; i < attributes.length; i++) {
			
				var parts = attributes[i].split('=');
				
				var optionValue;
				
				if (this._getAvailableOptions().include(parts[0])) {

					switch (parts[0]) {
							
						default:
							optionValue = parts[1];
							break;
						
					}

					this.options[parts[0]] = optionValue;
					
				}
				
			}
			
		}

	},
	
	_getAvailableOptions: function() {
		if (!this._keys.length) {
			for (var key in this.options)
				this._keys.push(key);
		}
		return this._keys;
	},
	
	mask: function(e) {
		
		switch (this.options.mask) {
		
			case 'us':
			case 'usa':
			case 'canada':
			case 'nanp':

				var eVal = Event.element(e).value.gsub(/[^0-9]/, '');
				var offset = 0;
				var returnValue = "";
				
				// Does the phone number begin with a number one?
				if (eVal.substring(0,1) == '1') {
				
					offset = 1;
					returnValue = "1 ";
				}
			
				// Full phone number provided
				if(eVal.length >= (10 + offset)) {
			
					// Phone number with area code 
					returnValue += "(" + eVal.substring(0 + offset,3 + offset) + ") " + eVal.substring(3 + offset,6 + offset) + "-" + eVal.substring(6 + offset,10 + offset);
					
					// Extension
					if(eVal.length > (10 + offset))
						returnValue += " Ext " + eVal.substring(10 + offset);
					
					Event.element(e).value = returnValue;
					
				// Only first 3 characters present
				} else if(eVal.length == (3 + offset)) {
			
					// Phone number with area code 
					returnValue += "(" + eVal.substring(0 + offset,3 + offset) + ") ";
					
					Event.element(e).value = returnValue;
				
				} else if(eVal.length >= 6 + offset) {
			
					// Phone number with area code 
					returnValue += "(" + eVal.substring(0 + offset,3 + offset) + ") " + eVal.substring(3 + offset,6 + offset) + "-" + eVal.substring(6 + offset,10 + offset);
				
					Event.element(e).value = returnValue;
					
				}
				
				
				break;
				
		}
		
	}

});

Event.observe(document, 'dom:loaded', function() {
	$$('.uiText').each(function(elm) {
		uiElements.set(elm.identify(), new UIText(elm));
	});
});



/*--- UICarousel Definition ---*/

var UIVerticalCarousel = Class.create({

	initialize: function(element) {

		this.element = $(element);
		this.element.identify();
		
		this.options = Object.extend({
			animationDuration: 0.5
		}, arguments[1] || {});
		
		this._init();
	
	},
	
	_init: function(element) {

		this.element.addClassName('uiVerticalCarousel');

		this.element.immediateDescendants().each(function(li) {
			
			var wrapper = new Element('div', {
				className: 'uiWrapper'
			});
			
			li.immediateDescendants().each(function(elm) {
				elm.remove();
				wrapper.insert(elm);
			});
			
			wrapper.down('.uiPanelTitle').insert({
				top: '<span class="uiCarouselPreviousNavigationTitle">&laquo;</span><span class="uiCarouselNextNavigationTitle">&raquo;</span>'
			}).hide();

			li.insert(wrapper);

			this._hidePanel(li);
			
		}.bind(this));
		
		var currentPanel = this._getCurrentPanel();
		
		this._showPanel(currentPanel, true);
		
		if (this._hasPreviousPanel())
			this._createPreviousPanel(this._getPreviousPanel());
		else
			currentPanel.addClassName('uiCarouselNoPreviousPanel');
		
		if (this._hasNextPanel())
			this._createNextPanel(this._getNextPanel(), false);
		else
			currentPanel.addClassName('uiCarouselNoNextPanel');
		
	},
	
	_createPreviousPanel: function(element, animate) {
	
		element.addClassName('uiCarouselPreviousPanel');
		element.removeClassName('uiCurrentPanel');
		element.down('.uiPanelTitle').show();
		element.observe('click', this.previous.bind(this));
		this._showPanel(element, false, animate);
			
	},
	
	_createNextPanel: function(element, animate) {
	
		element.addClassName('uiCarouselNextPanel');
		element.removeClassName('uiCurrentPanel');
		element.down('.uiPanelTitle').show();
		element.observe('click', this.next.bind(this));
		this._showPanel(element, false, animate);
	
	},
	
	_hasPreviousPanel: function() {
		return this._getPreviousPanel();
	},
	
	_hasNextPanel: function() {
		return this._getNextPanel();
	},
	
	_getPreviousPanel: function() {
		return this._getCurrentPanel().previous();
	},
	
	_getCurrentPanel: function() {
		return this.element.down('li.uiCurrentPanel');
	},
	
	_getNextPanel: function() {
		return this._getCurrentPanel().next();
	},
	
	_getLastPanel: function() {
		return this.element.immediateDescendants().last();
	},
	
	_getPanelContent: function(element) {
		return element.down('.uiPanelContent');
	},
	
	_getPanelTitle: function(element) {
		return element.down('.uiPanelTitle');
	},
	
	_hidePanel: function(element) {

		if (typeof arguments[1] == 'boolean' && arguments[1]) {
			new Effect.BlindUp(element, {
				duration: this.options.animationDuration
			});
		} else
			element.hide();
			
		element.stopObserving('click');
			
		if (element.down('.uiCarouselNavigationTitle'))
			element.down('.uiCarouselNavigationTitle').remove();
			
		this._hidePanelContents(element, typeof arguments[1] == 'boolean' && arguments[1] ? true : false);
		
	},
	
	_hidePanelContents: function(element) {

		if (arguments[1])
			Effect.BlindUp(this._getPanelContent(element), {
				duration: this.options.animationDuration,
				afterFinish: arguments[2] ? arguments[2] : function() {}
			});
		else {
			this._getPanelContent(element).hide();
		}
			
	},
	
	_showPanelContents: function(element) {

		if (!element.visible())	
			element.show();
	
		if (arguments[1]) {
			new Effect.BlindDown(this._getPanelContent(element), {
				duration: this.options.animationDuration,
				afterFinish: arguments[2] ? arguments[2] : function() {}
			});
		} else
			this._getPanelContent(element).show();
	},
	
	_showPanelTitle: function(element) {
	
		if (arguments[1]) {
			new Effect.SlideDown(this._getPanelTitle(element), {
				duration: this.options.animationDuration,
				afterFinish: arguments[2] ? arguments[2] : function() {}
			});
		} else
			this._getPanelTitle(element).show();
		
	},
	
	_hidePanelTitle: function(element) {
	
		if (arguments[1]) {
			new Effect.SlideUp(this._getPanelTitle(element), {
				duration: this.options.animationDuration,
				afterFinish: arguments[2] ? arguments[2] : function() {}
			});
		} else
			this._getPanelTitle(element).hide();
		
	},
	
	_showPanel: function(element, showContents) {

		if (typeof arguments[2] == 'boolean' && arguments[2]) {
			new Effect.BlindDown(element, {
				duration: this.options.animationDuration
			});
		} else
			element.show();
			
		if (showContents) this._showPanelContents(element, typeof arguments[2] == 'boolean' && arguments[2] ? true : false);
		
	},
	
	hideNavigation: function() {
	
		if (this._hasPreviousPanel())
			this._hidePanel(this._getPreviousPanel(), arguments[0] ? true : false);
	
		if (this._hasNextPanel())
			this._hidePanel(this._getNextPanel(), arguments[0] ? true : false);
			
		if (arguments[0]) {
			this._getCurrentPanel().addClassName('uiCarouselNoNextPanel');
			this._getCurrentPanel().addClassName('uiCarouselNoPreviousPanel');
		}
	
	},
	
	previous: function(e) {

		if (this.suspendNavigation)
			return;
	
		this.suspendNavigation = true;
		
		this.timeout = setTimeout(function() {
			this.suspendNavigation = false
		}.bind(this), this.options.animationDuration * 1.1 * 1000);
	
		if (this._hasNextPanel()) {

			this._hidePanel(this._getNextPanel(), true);
			this._getNextPanel().removeClassName('uiCarouselNextPanel');
		}

		this._transitionToNextPanel(this._getCurrentPanel());
		this._transitionToCurrentPanel(Event.findElement(e, 'li'));
		
		if (this._hasPreviousPanel(Event.findElement(e, 'li').previous()))
			this._transitionToPreviousPanel(Event.findElement(e, 'li').previous());
		else
			Event.findElement(e, 'li').addClassName('uiCarouselNoPreviousPanel');
	
	},
	
	next: function() {

		if (this._hasPreviousPanel()) {
			this._hidePanel(this._getPreviousPanel(), true);
			this._getPreviousPanel().removeClassName('uiCarouselPreviousPanel');
		}

		var nextPanel = this._getNextPanel();
		var currentPanel = this._getCurrentPanel();

		this._transitionToPreviousPanel(this._getCurrentPanel());
		this._transitionToCurrentPanel(nextPanel);

		if (this._hasNextPanel(nextPanel.next()))
			this._transitionToNextPanel(nextPanel.next());
		else
			nextPanel.addClassName('uiCarouselNoNextPanel');
	
	},
	
	jumpTo: function(panelIndex) {
	
		if (panelIndex == 'last' || panelIndex == -1) {
			
			var previousPanel = this._getPreviousPanel();
			var currentPanel = this._getCurrentPanel();
			var nextPanel = this._getNextPanel();
			var lastPanel = this._getLastPanel();
			
			if (currentPanel == lastPanel)
				return;
				
			if (this._getNextPanel() == lastPanel)
				this.next();
			else if (this._getNextPanel() == lastPanel.previous()) {
			
				this._hidePanel(previousPanel, true);
				this._hidePanel(currentPanel, true);
			
				nextPanel.removeClassName('uiCarouselNextPanel');
				nextPanel.addClassName('uiCarouselPreviousPanel');
				
				this._showPanelContents(lastPanel, true);
				
				lastPanel.addClassName('uiCarouselNoNextPanel');
				lastPanel.addClassName('uiCurrentPanel');
				
			} else {

				currentPanel.removeClassName('uiCarouselNoPreviousPanel');
				currentPanel.removeClassName('uiCarouselNoNextPanel');
				currentPanel.removeClassName('uiCurrentPanel');
				nextPanel.removeClassName('uiCarouselNextPanel');
				
				nextPanel.stopObserving('click');
		
				if (previousPanel) {
					previousPanel.stopObserving('click');
					previousPanel.removeClassName('uiCarouselPreviousPanel');
					this._hidePanel(previousPanel, true);
				}
					
				this._hidePanel(currentPanel, true);
				this._hidePanel(nextPanel, true);

				this._createPreviousPanel(lastPanel.previous());
				this._showPanelContents(lastPanel, true);
				
				lastPanel.addClassName('uiCarouselNoNextPanel');
				lastPanel.addClassName('uiCurrentPanel');
			
			}
			
		}
	
	},
	
	_transitionToPreviousPanel: function(element) {

		if (element.visible()) {

			this._hidePanelContents(element, true);
			this._showPanelTitle(element, true);

			element.addClassName('uiCarouselPreviousPanel');
			element.removeClassName('uiCarouselNoPreviousPanel');
			element.removeClassName('uiCarouselNoNextPanel');
			element.removeClassName('uiCurrentPanel');
			
			element.observe('click', this.previous.bind(this));

		} else
			this._createPreviousPanel(element, true);
		
	},
	
	_transitionToCurrentPanel: function(element) {
	
		this._showPanelContents(element, true);
		this._hidePanelTitle(element, true);
		
		element.addClassName('uiCurrentPanel');
		element.removeClassName('uiCarouselPreviousPanel');
		element.removeClassName('uiCarouselNextPanel');
		
		element.stopObserving('click');
		
	},
	
	_transitionToNextPanel: function(element) {

		if (element.visible()) {
		
			this._hidePanelContents(element, true);
			this._showPanelTitle(element, true);
			
			element.addClassName('uiCarouselNextPanel');
			element.removeClassName('uiCarouselNoPreviousPanel');
			element.removeClassName('uiCarouselNoNextPanel');
			element.removeClassName('uiCurrentPanel');
			
			element.observe('click', this.next.bind(this));
		
		} else
			this._createNextPanel(element, true);
		
	}

});

Event.observe(document, 'dom:loaded', function() {

	$$('ul.uiVerticalCarousel').each(function(elm) {
		new UIVerticalCarousel(elm);
	});

});



/*--- UICalendar Definition ---*/

var UICalendarGroup = Class.create({

	initialize: function(element) {
	
		this.element = $(element);
		this.element.identify();
	
		this.options = Object.extend({
			date: '',
			clickable: false,
			selectable: false,
			onNavigationPrevious: function() {},
			onNavigationNext: function() {},
			onDaySelect: function() {},
			onDayClick: function() {}
		}, arguments[1] || {});
		
		this._init();
	
	},
	
	init: function() {
	
	}

});





var UICalendar = Class.create({

	initialize: function(element) {
	
		this.element = $(element);
		this.element.identify();
	
		this.options = Object.extend({
			date: '',
			clickable: false,
			selectable: false,
			onNavigationPrevious: function() {},
			onNavigationNext: function() {},
			onDaySelect: function() {},
			onDayClick: function() {}
		}, arguments[1] || {});
		
		this._keys = $A();
		this._currentActionDates = $H();
		this._selectedDates = $H();
		
		this._mouseDown = false;
		this._dragStart = '';
		this._selecting = false;

		this._loadHtmlOptions();
		this._init();
	
	},
	
	_loadHtmlOptions: function() {

		if (this.element.hasClassName('uiCalendarSelectable'))
			this.options.selectable = true;
	
		if (this.element.hasClassName('uiCalendarClickable'))
			this.options.clickable = true;

		if (this.element.getAttribute('rel')) {
	
			// Load the HTML options
			var attributes = this.element.getAttribute('rel').split(';');
	
			for (var i = 0; i < attributes.length; i++) {
			
				var parts = attributes[i].split('=');
				
				var optionValue;
				
				if (this._getAvailableOptions().include(parts[0])) {

					switch (parts[0]) {
						
						case 'fixed':
							optionValue = parts[1].toLowerCase() == 'no' || parts[1].toLowerCase() == 'n' ? false : true;
							break;
							
						default:
							optionValue = parts[1];
							break;
						
					}

					this.options[parts[0]] = optionValue;
					
				}
				
			}
			
		}

	},
	
	_getAvailableOptions: function() {
		if (!this._keys.length) {
			for (var key in this.options)
				this._keys.push(key);
		}
		return this._keys;
	},
	
	_init: function() {

		this.date = this._getDate(this.options.date);
		
		this._initObservers();
	},
	
	_initObservers: function() {
	
		this.element.select('a.uiCalendarNavigationPrevious').invoke('observe', 'click', this._doNavigationPrevious.bind(this));
		this.element.select('a.uiCalendarNavigationNext').invoke('observe', 'click', this._doNavigationNext.bind(this));
		
		if (this.options.selectable) {

			this.element.select('td.uiCalendarDay').invoke('observe', 'mouseup', this._doDayMouseUp.bind(this));
			this.element.select('td.uiCalendarDay').invoke('observe', 'mousedown', this._doDayMouseDown.bind(this));
			this.element.select('td.uiCalendarDay').invoke('observe', 'mouseover', this._doDayMouseOver.bind(this));
			this.element.select('th').invoke('observe', 'click', this._selectDayOfWeek.bind(this));
			this.element.select('td.uiCalendarWeekSelector').invoke('observe', 'click', this._selectWeek.bind(this));
			
			this.element.onselectstart = function() { return false; };
			this.element.onmousedown = function() { return false; };
			
		} else if (this.options.clickable) {
			this.element.select('td.uiCalendarDay').invoke('observe', 'click', this._doDayClick.bind(this));
		}
	
	},
	
	_doNavigationPrevious: function(e) {

		Event.stop(e);

		var date = new Date(this.date.year, this.date.month - 2, 1);
		
		var newCalendar = this._createNewCalendar(date);
			
		this.element.replace(newCalendar);
		this.element = newCalendar;
		
		this.date = {
			year: date.getFullYear(),
			month: date.getMonth() + 1,
			mday: date.getDate() + 1
		};
		
		this._initObservers();
		
		this.options.onNavigationPrevious(this);
		
	},
	
	_doNavigationNext: function(e) {

		Event.stop(e);
		
		var date = new Date(this.date.year, this.date.month, 1);
		
		var newCalendar = this._createNewCalendar(date);
			
		this.element.replace(newCalendar);
		this.element = newCalendar;
		
		this.date = {
			year: date.getFullYear(),
			month: date.getMonth() + 1,
			mday: date.getDate() + 1
		};
		
		this._initObservers();
		
		this.options.onNavigationNext(this);
		
	},
	
	_createNewCalendar: function(date) {

		var months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		
		var newCalendar = new Element('table', {
			className: this.element.className,
			id: this.element.identify() + '_new',
			rel: this.element.getAttribute('rel')
		});
		
		var caption = new Element('caption').update(months[date.getMonth()] + ' ' + (date.getFullYear()));
		
		if (this.element.select('a.uiCalendarNavigationPrevious'))
			caption.insert({
				top: new Element('a', {
					href: '#',
					className: 'uiCalendarNavigationPrevious'
				}).update('\u00AB')
			});
		
		if (this.element.select('a.uiCalendarNavigationNext'))
			caption.insert({
				bottom: new Element('a', {
					href: '#',
					className: 'uiCalendarNavigationNext'
				}).update('\u00BB')
			});
		
		newCalendar.insert(caption);
		
		var thead = new Element('thead');
		var tr = new Element('tr');
		
		if (this.element.select('td.uiCalendarWeekSelector').length)
			tr.insert(
				new Element('th', {
					className: 'uiCalendarWeekSelector'
				}).update('\u00A0')
			);
			
		var thCount = this.element.select('th').length;
			
		tr.insert(new Element('th', {
			className: 'dayOfTheWeek_0'
		}).update(this.element.down('thead').down('th', thCount - 7).innerHTML));
		tr.insert(new Element('th', {
			className: 'dayOfTheWeek_1'
		}).update(this.element.down('thead').down('th', thCount - 6).innerHTML));
		tr.insert(new Element('th', {
			className: 'dayOfTheWeek_2'
		}).update(this.element.down('thead').down('th', thCount - 5).innerHTML));
		tr.insert(new Element('th', {
			className: 'dayOfTheWeek_3'
		}).update(this.element.down('thead').down('th', thCount - 4).innerHTML));
		tr.insert(new Element('th', {
			className: 'dayOfTheWeek_4'
		}).update(this.element.down('thead').down('th', thCount - 3).innerHTML));
		tr.insert(new Element('th', {
			className: 'dayOfTheWeek_5'
		}).update(this.element.down('thead').down('th', thCount - 2).innerHTML));
		tr.insert(new Element('th', {
			className: 'dayOfTheWeek_6'
		}).update(this.element.down('thead').down('th', thCount - 1).innerHTML));
		
		newCalendar.insert({
			bottom: thead.insert(tr)
		});
		
		var tbody = new Element('tbody');
		var tr = new Element('tr');
		
		if (this.element.select('td.uiCalendarWeekSelector').length)
			tr.insert(
				new Element('td', {
					className: 'uiCalendarWeekSelector'
				}).update('\u00BB')
			);
		
		var weekday = date.getDay();
		var counter = 0;
		var currentMonth = date.getMonth();
		
		while (currentMonth == date.getMonth()) {

			if (counter++ < date.getDay()) {
				tr.insert({
					bottom: new Element('td', {
						className: 'uiCalendarEmptyDay'
					}).update('\u00A0')
				});
				continue;
			}
			
			tr.insert({
				bottom: new Element('td', {
					className: 'uiCalendarDay dayOfTheWeek_' + date.getDay() + ' date_' + date.getFullYear() + (date.getMonth() + 1 < 10 ? '0' : '') + (date.getMonth() + 1) + (date.getDate() < 10 ? '0' : '') + date.getDate()
				}).insert(
					new Element('div', {
						className: 'uiCalendarDay'
					}).insert(
						new Element('div', {
							className: 'uiCalendarDayLabel'
						}).update(date.getDate())
					)
				)
			});
			
			if (date.getDay() == 6) {
			
				counter = 0;
				
				tbody.insert({
					bottom: tr
				});
				
				tr = new Element('tr');
		
				if (this.element.select('td.uiCalendarWeekSelector').length)
					tr.insert(
						new Element('td', {
							className: 'uiCalendarWeekSelector'
						}).update('\u00BB')
					);
				
			}
		
			date = new Date(date.getFullYear(), date.getMonth(), date.getDate() + 1);

		}
		
		if (counter != 0) {

			while (counter++ <= 6)
				tr.insert({
					bottom: new Element('td', {
						className: 'uiCalendarEmptyDay'
					}).update('\u00A0')
				});
				
			tbody.insert({
				bottom: tr
			});
			
		}
		
		newCalendar.insert({
			bottom: tbody
		});
		
		return newCalendar;
	
	},
	
	_selectDayOfWeek: function(e) {
		
		var elm = Event.element(e);
		
		var dayOfTheWeek = /dayOfTheWeek_(\d)/.exec(elm.className)[1];
		
		if (elm.hasClassName('uiCalendarSelectedDayOfWeek'))
			elm.removeClassName('uiCalendarSelectedDayOfWeek');
		else
			elm.addClassName('uiCalendarSelectedDayOfWeek');
		
		this.element.select('td.dayOfTheWeek_' + dayOfTheWeek).each(function(tdElm) {
			if (elm.hasClassName('uiCalendarSelectedDayOfWeek')) {
				tdElm.addClassName('uiCalendarSelectedDay');
				this._selectedDates.set(this._getDateString(tdElm), true);
				this.options.onDaySelect(new Array(this._getDate(tdElm)), true, elm, e);

			} else {
				tdElm.removeClassName('uiCalendarSelectedDay');
				this._selectedDates.set(this._getDateString(tdElm), false);
				this.options.onDaySelect(new Array(this._getDate(tdElm)), false, elm, e);
			}
		}.bind(this));
	
	},
	
	_selectWeek: function(e) {

		var elm = Event.findElement(e, 'td.uiCalendarWeekSelector');
		var parentElm = elm.up('tr');
		
		if (elm.hasClassName('uiCalendarSelectedWeek'))
			elm.removeClassName('uiCalendarSelectedWeek');
		else
			elm.addClassName('uiCalendarSelectedWeek');

		parentElm.select('td.uiCalendarDay').each(function(tdElm) {
			if (elm.hasClassName('uiCalendarSelectedWeek')) {
				tdElm.addClassName('uiCalendarSelectedDay');
				this._selectedDates.set(this._getDateString(tdElm), true);
				this.options.onDaySelect(new Array(this._getDate(tdElm)), true, elm, e);

			} else {
				tdElm.removeClassName('uiCalendarSelectedDay');
				this._selectedDates.set(this._getDateString(tdElm), false);
				this.options.onDaySelect(new Array(this._getDate(tdElm)), false, elm, e);
			}
		}.bind(this));
	
	},
	
	selectDays: function(intervalType, intervalStep, days, setPosition) {
	
		var dayIndexes = {
			SU: 0,
			MO: 1,
			TU: 2,
			WE: 3,
			TH: 4,
			FR: 5,
			SA: 6
		}
		
		days = days.split(',');

		switch (intervalType) {
		
			case 'DAILY':
			
				break;
				
			case 'WEEKLY':
			
				for (var i = 0; i < days.length; i++)
				
					this.element.select('td.dayOfTheWeek_' + dayIndexes[days[i]]).inGroupsOf(intervalStep).each(function(elmGroup) {
						if (elmGroup.last())
							this.selectDay(this._getDateString($A(elmGroup).last()));
					}, this);
			
				break;
				
			case 'MONTHLY':
			
				if (days.length)
			
					for (var i = 0; i < days.length; i++) {
					
						var elms = this.element.select('td.dayOfTheWeek_' + dayIndexes[days[i]]);
					
						if (setPosition == 'FIRST')
							this.selectDay(this._getDateString(elms.first()));
						else if (setPosition == 'LAST')
							this.selectDay(this._getDateString(elms.last()));
						
					}
					
				else
					this.selectDay(this._getDateString(this.element.select('td.calendarDay').last()));
				
				break;
		
		}
	
	},
	
	selectDay: function(dateString) {

		var elm = this.element.down('td.date_' + dateString);

		if (!elm)
			throw new UIException('ElementDoesNotExistException');
	
		elm.addClassName('uiCalendarSelectedDay');
		
		this.options.onDaySelect(new Array(this._getDate(elm)), true, elm, this);

	},
	
	_doDayMouseDown: function(e) {

		var elm = Event.findElement(e, 'td.uiCalendarDay');

		if (!this._selectedDates.get(this._getDateString(elm))) {
			elm.addClassName('uiCalendarSelectedDay');
			this._currentActionDates.set(this._getDateString(elm), true);
			this._selecting = true;
		} else {
			elm.removeClassName('uiCalendarSelectedDay');
			this._currentActionDates.set(this._getDateString(elm), false);
			this._selecting = false;
		}
		
		this._dragStart = this._getDateString(elm);
		this._mouseDown = true;
	
	},
	
	_doDayMouseUp: function(e) {
		
		var elm = Event.findElement(e, 'td.uiCalendarDay');

		var isSelected = true;

		if (this._dragStart == this._getDateString(elm) && !this._currentActionDates.get(this._getDateString(elm))) {
			isSelected = false;
			elm.removeClassName('uiCalendarSelectedDay');
		}
		
		var dates = $A();
		
		if (this._currentActionDates.keys().length) {

			this._currentActionDates.keys().each(function(date) {
				dates.push(this._getDate(date));
			}.bind(this));
			
		} else
			dates.push(this._getDate(elm));
	
		this._dragStart = '';

		this.options.onDaySelect(dates, isSelected, elm, this);

		this._selectedDates = this._selectedDates.merge(this._currentActionDates);
		this._currentActionDates = $H();
		
		this._mouseDown = false;
		
	},
	
	_doDayClick: function(e) {
		
		var elm = Event.findElement(e, 'td.uiCalendarDay');
		this.options.onDayClick(this._getDate(elm), elm, this);
			
	},
	
	_doDayMouseOver: function(e) {
	
		var elm = Event.findElement(e, 'td.uiCalendarDay');
		
		if (this._mouseDown) {

			var startDate = this._getDate(this._dragStart);
			var endDate = this._getDate(elm);
		
			this._currentActionDates.each(function(date) {
				if (this._selecting)
					this.element.down('td.date_' + date).removeClassName('uiCalendarSelectedDay');
				else
					this.element.down('td.date_' + date).addClassName('uiCalendarSelectedDay');
			}.bind(this));
		
			this._currentActionDates = $H();
				
			var range = (startDate.mday < endDate.mday) ? $R(startDate.mday, endDate.mday) : $R(endDate.mday, startDate.mday);
			
			range.each(function(idx) {
			
				var dateString = endDate.year + '0'.times(2 - endDate.month.toString().length) + endDate.month + '0'.times(2 - idx.toString().length) + idx;
				this._currentActionDates.set(dateString, this._selecting);
				
				if (this._selecting)
					this.element.down('td.date_' + dateString).addClassName('uiCalendarSelectedDay');
				else
					this.element.down('td.date_' + dateString).removeClassName('uiCalendarSelectedDay');
				
			}.bind(this));
		
		}
	
	},
	
	_getDateString: function(elm) {
	
		var date = this._getDate(elm);
		
		return '' + date.year + '0'.times(2 - date.month.toString().length) + date.month + '0'.times(2 - date.mday.toString().length) + date.mday;
	
	},
	
	_getDate: function(elm) {
	
		var regx = /(\d{4})(\d{2})(\d{2})?/;
	
		if (typeof(elm) == 'object') {
			regx = /date_(\d{4})(\d{2})(\d{2})/;
			regString = elm.className;
		} else
			regString = elm;

		var regs = regx.exec(regString);

		if (regs[1])
			return {
				year: parseInt(regs[1]),
				month: parseInt(/0?(\d+)/.exec(regs[2])[1]),
				mday: regs[3] ? parseInt(/0?(\d+)/.exec(regs[3])[1]) : ''
			};
		else
			throw new UIException('MissingRelSettingException');
			
	
	}

});

Event.observe(document, 'dom:loaded', function() {
	$$('table.uiCalendar').each(function(elm) {
//		new UICalendar(elm);
	});
});





/*--- UIDialogBox Definition ---------------------------------------------------------------------*/

var UIDialogBox = Class.create({

	initialize: function(element) {

		if (typeof(element) == 'string') {
		
			this.element = new Element('div', {
				className: arguments[2] ? arguments[2] : 'uiDialogBox'
			});

			$$('body').first().insert({
				bottom: this.element
			});

		} else
			this.element = $(element);
		
		if (!this.element)
			throw new UIException('ElementDoesNotExistException');

		this.options = Object.extend({
			title: '',
			content: '',
			fixed: true,
			animationDuration: 0.25,
			showCloseButton: true,
			modal: true,
			screen: false,
			closeOnScreenClick: true,
			button: '',
			showOnInit: false
		}, arguments[1] || {});
		
		this.keys = $A();

		this._loadHtmlOptions();

		this._init();

		if (this.options.showOnInit)
			this.show();
	
	},
	
	_loadHtmlOptions: function() {

		if (this.element.hasAttribute('rel')) {
		
			var attributes = this.element.getAttribute('rel').split(';');

			for (var i = 0; i < attributes.length; i++) {
			
				var parts = attributes[i].split('=');
				
				var optionValue;
				
				if (this._getAvailableOptions().include(parts[0])) {

					switch (parts[0]) {
						
						case 'fixed':
						case 'autoshow':
						case 'stem':
						case 'showCloseButton':
						case 'closeOnScreenClick':
						case 'modal':
						case 'screen':
						case 'showOnInit':
							optionValue = parts[1].toLowerCase() == 'no' || parts[1].toLowerCase() == 'n' ? false : true;
							break;
							
						case 'animationDuration':
							optionValue = isNaN(parseFloat(parts[1])) ? 0 : parseFloat(parts[1]);
							break;
							
						default:
							optionValue = parts[1];
							break;
						
					}
				
					this.options[parts[0]] = optionValue;
					
				}
				
			}
		
		}
	
	},
	
	_getAvailableOptions: function() {
		if (!this.keys.length) {
			for (var key in this.options)
				this.keys.push(key);
		}
		return this.keys;
	},
	
	_init: function() {
	
		if (this.options.content) {
			this.element.update(this.options.content);
		}
		
		if (this.options.button != '') {
		
			this.options.closeOnScreenClick = false;

			this.addButton(this.options.button, {
				onClick: this.options.onClick ? this.options.onClick.bind(this) : this.hide.bind(this)
			});

		}

		if (this.options.title) {
		
			this.titleElement = new Element('div', {
					className: 'uiDialogBoxTitle'
				}).update(this.options.title);
		
			this.element.insert({
				top: this.titleElement
			});
			
			this.titleElement.setStyle({
				paddingRight: (parseInt(/^(\d+)px$/.exec(this.titleElement.getStyle('paddingRight'))[1]) + 17) + 'px',
				width: (parseInt(/^(\d+)px$/.exec(this.titleElement.getStyle('width'))[1]) - 17) + 'px',
			});

			if (this.options.showCloseButton)
				this._createCloseButton();

			this.element.setStyle({
				paddingTop: (parseInt(/^(\d+)px$/.exec(this.element.getStyle('paddingTop'))[1]) + this.titleElement.getHeight()) + 'px'
			});

		}
		
		if (!this.options.fixed) {
		
			if (this.options.title)
				this.titleElement.setStyle({
					cursor: 'move'
				});
			else
				this.element.setStyle({
					cursor: 'move'
				});
		/*
			new Draggable(this.element, {
				handle: this.options.title ? '.uiDialogBoxTitle' : '.uiDialogBox',
				zindex: 2500,
				starteffect: function() {},
				endeffect: function() {}
			});
		*/
		}

		this.element.hide();

		$$('.uiDialogBoxTrigger[rel=dialogBox=' + this.element.id + ']').each(function(elm) {
			elm.observe('click', this.show.bind(this));
		}.bind(this));
	
	},
	
	_createCloseButton: function() {

		var closeButton = new Element('a', {
			href: '#', className: 'uiCloseButton'
		});
		
		closeButton.observe('click', this.hide.bind(this));
		
		this.element.down('.uiDialogBoxTitle').insert({
			top: closeButton
		});
		
		this.element.addClassName('uiHasCloseButton');
		
		closeButton.insert({
			after: new Element('div', {
				className: 'clear'
			})
		});
		
	},
	
	show: function() {
		
		if (arguments[0])
			Event.stop(arguments[0]);

		var onClick = (this.options.screen || this.options.modal) && this.options.closeOnScreenClick ? this.hide.bind(this) : function() {};
		
		if (this.options.modal && this.options.screen) {
			this.screen = new UIScreen({
				onClick: onClick
			});
			this.screen.show();
		} else if (this.options.modal) {
			this.screen = new UIScreen({
				transparent: true,
				onClick: onClick
			});
			this.screen.show();
		}
		
		if (this.element.visible())
			new Effect.Shake(this.element);
		else {
			
			this._setPosition();

			new Effect.Appear(this.element, {
				duration: this.options.animationDuration
			});
		
		}
		
	},
	
	hide: function() {
		
		if (arguments[0])
			Event.stop(arguments[0]);
		
		if (this.options.modal || this.options.screen)
			this.screen.hide();
	
		new Effect.Fade(this.element, {
			duration: this.options.animationDuration
		});
		
	},
	
	_setPosition: function() {
	
		var position = this._calculatePosition();

		this.element.setStyle({
			top: position.top + 'px',
			left: position.left + 'px',
			position: 'fixed'
		});
	
	},
	
	_calculatePosition: function() {
	
		var position = {
			top: 0,
			left: 0
		};
		
		position.left = (document.viewport.getWidth() / 2) - (this.element.getWidth() / 2);
		position.top = (document.viewport.getHeight() / 2) - (this.element.getHeight() / 2);
		
		return position;
	
	},
	
	addButton: function(buttonText) {

		var button = new UIButton(buttonText, arguments[1] || {});

		this._getButtonWrapper().insert({
			bottom: button.element
		});
	
	},
	
	_getButtonWrapper: function() {
	
		if (!this.element.select('div.uiButtonWrapper').length) {

			this.element.insert({
				bottom: new Element('div', {
					className: 'uiButtonWrapper'
				})
			});
		}
		
		return this.element.down('div.uiButtonWrapper');
	
	}

});

Event.observe(document, 'dom:loaded', function() {
	$$('div.uiDialogBox').each(function(elm) {
		new UIDialogBox(elm);
	});
});




/*--- UIAlertDialogBox ---*/

var UIAlertDialogBox = Class.create(UIDialogBox, {

	initialize: function($super, element) {

		options = Object.extend({
			title: '',
			content: '',
			fixed: true,
			animationDuration: 0.25,
			screen: false,
			button: 'OK'
		}, arguments[2] || {});

		this.keys = $A();
		
		options.showCloseButton = false;
		options.modal = true;
		options.closeOnScreenClick = false;

		$super(element, options, 'uiAlertDialogBox');
	
	}

});

Event.observe(document, 'dom:loaded', function() {
	$$('div.uiAlertDialogBox').each(function(elm) {
		new UIAlertDialogBox(elm);
	});
});



/*--- UIConfirmDialogBox ---*/

var UIConfirmDialogBox = Class.create(UIDialogBox, {

	initialize: function($super, element) {

		options = Object.extend({
			title: '',
			content: '',
			fixed: true,
			animationDuration: 0.25,
			screen: false,
			positiveButton: 'OK',
			negativeButton: 'Cancel',
			onConfirm: this.hide.bind(this),
			onCancel: this.hide.bind(this)
		}, arguments[2] || {});

		this.keys = $A();
		
		options.showCloseButton = false;
		options.modal = true;
		options.closeOnScreenClick = false;
		options.button = '';

		$super(element, options, 'uiConfirmDialogBox');
	
	},
	
	_init: function($super) {
	
		$super();
		
		this.addButton(this.options.positiveButton, {
			onClick: this.options.onConfirm.bind(this)
		});
		
		this.addButton(this.options.negativeButton, {
			onClick: this.options.onCancel.bind(this)
		});
	
	}

});

Event.observe(document, 'dom:loaded', function() {
	$$('div.uiConfirmDialogBox').each(function(elm) {
		new UIConfirmDialogBox(elm);
	});
});





/*--- UIErrorDialogBox ---*/

var UIErrorDialogBox = Class.create(UIDialogBox, {

	initialize: function($super, content) {

		options = Object.extend({
			title: arguments[2] ? arguments[2] : 'Oops! There was an error!',
			content: content,
			fixed: true,
			animationDuration: 0.25,
			screen: false,
			button: 'OK',
			showOnInit: true,
			onClick: function() {
				this.hide();
			}.bind(this)
		}, arguments[2] || {});

		this.keys = $A();
		
		options.showCloseButton = false;
		options.modal = true;
		options.closeOnScreenClick = true;

		$super('errorDialogBox_' + Element.idCounter++, options, 'uiErrorDialogBox');
	
	}

});

Event.observe(document, 'dom:loaded', function() {
	$$('div.uiAlertDialogBox').each(function(elm) {
		new UIAlertDialogBox(elm);
	});
});




/*--- UIScreen Definition ------------------------------------------------------------------------*/

var UIScreen = Class.create({

	initialize: function() {

		this.options = Object.extend({
			transparent: false,
			animationDuration: 0.25,
			opacity: 0.5,
			onClick: function() {}
		}, arguments[0] || {});
		
		this._init();
	
	},
	
	_init: function() {
	
		this.element = new Element('div', {
			className: this.options.transparent ? 'uiBlankScreen' : 'uiScreen'
		});
		
		this.autosize();
		
		this.element.hide();
		
		if (this.options.onClick)
			this.element.observe('click', this._handleClick.bind(this));
	
	},
	
	autosize: function() {
	
		var size = {
			width: 0,
			height: 0
		};
		
		size.width = document.viewport.getWidth();
		size.height = document.viewport.getHeight();

		this.element.setStyle({
			width: size.width + 'px',
			height: size.height + 'px'
		});
	
	},
	
	show: function() {
	
		$$('body').first().insert({
			top: this.element
		});
	
		this._position();
		
		if (this.options.transparent)
			this.element.show();
		else
			new Effect.Appear(this.element, {
				duration: this.options.animationDuration,
				to: this.options.opacity
			});
	
	},
	
	hide: function() {
	
		if (this.options.transparent)
			this.element.remove();
		else
			new Effect.Fade(this.element, {
				duration: this.options.animationDuration,
				from: this.options.opacity,
				afterFinish: function() {
					this.element.remove();
				}.bind(this)
			});
	
	},
	
	_handleClick: function(e) {
		
		if (this.options.onClick)
			this.options.onClick(e, this);
		
	},
	
	_position: function() {
	
		var position = {
			left: 0,
			top: 0
		}

		this.element.setStyle({
			left: position.left + 'px',
			top: position.top + 'px',
			position: 'fixed'
		});
	
	}

});







var uiDualLists;

var UIDualList = Class.create({

	initialize: function(element) {

		this.element = $(element);
		this.sortables = $A();
		
		this.options = Object.extend({
			borderWidth: 1
		} || arguments[1] || {});
		
		if (this.element.tagName == 'SELECT')
			this._convertElement();

		this.leftList = this.element.down('div');
		this.rightList = this.leftList.next();
	
		this.autosize();
		
		this._initSortables(this.rightList.down('ul.uiDraggableList'));

	},
	
	autosize: function() {
	
		var leftChildrenHeight = this._getChildrenHeight(this.leftList);
		var rightChildrenHeight = this._getChildrenHeight(this.rightList);

		if (leftChildrenHeight > rightChildrenHeight) {
			new Effect.Morph(this.rightList, {
				duration: 0.125,
				style: { height: leftChildrenHeight + 'px' }
			});
			new Effect.Morph(this.leftList, {
				duration: 0.125,
				style: { height: leftChildrenHeight + 'px' }
			});
		} else {
			new Effect.Morph(this.rightList, {
				duration: 0.125,
				style: { height: rightChildrenHeight + 'px' }
			});
			new Effect.Morph(this.leftList, {
				duration: 0.125,
				style: { height: rightChildrenHeight + 'px' }
			});
		}
	
	},
	
	_convertElement: function() {
	
		var newListElm = new Element('div', {
			className: 'uiDualList',
			name: this.element.getAttribute('name'),
			id: this.element.id
		});

		var newLeftListElm = new Element('div', {
			className: 'uiList uiDualListLeft'
		});
		
		var newRightListElm = new Element('div', {
			className: 'uiList uiDualListRight'
		});
		
		newListElm.insert({ top: newLeftListElm });
		newListElm.insert({ bottom: newRightListElm });
		newListElm.insert({ bottom: new Element('div', { className: 'clear' }) });
		
		var newRightDraggableListElm = new Element('ul', {
			className: 'uiDraggableList'
		});
		
		var rightListElements = $A();
		
		if (this.element.select('optgroup')) {
			
			this.element.select('optgroup').each(function(optGroupElm) {

				var newDualListOptionGroupElm = new Element('div', {
					className: 'uiDualListOptionGroup'
				});
				
				var newDualListOptionGroupId = /_(\d+)$/.exec(newDualListOptionGroupElm.identify())[1];
				
				newDualListOptionGroupElm.id = 'uiDualListOptionGroup_' + newDualListOptionGroupId;
				
				newDualListOptionGroupElm.insert({
					top: new Element('div', {
						className: 'uiDualListGroupName'
					}).update(optGroupElm.getAttribute('label'))
				});
				
				var newDraggableListElm = new Element('ul', {
					className: 'uiDraggableList'
				});
				
				optGroupElm.select('option').each(function(option) {
					
					if (option.selected) {
					
						newLeftListElm.insert({
							before: new Element('input', {
								name: this.element.getAttribute('name'),
								type: 'hidden',
								value: option.value,
								className: 'uiDraggableListItemId uiDraggableListItemId_' + option.value
							})
						})
						
						rightListElements.push(
							this._convertListItem(
								new Element('li', {
									className: option.className + 'uiDualListOption uiDualListOptionParentGroup_' + newDualListOptionGroupId + (option.hasClassName('sortable') ? ' sortable' : ''),
									id: this.element.identify() + '_' + option.value
								}).insert(
									new Element('span', {
										className: 'uiDualListOptionLabel'
									}).update(option.text)
								)
							)
						);
						
					} else {
					
						var li = new Element('li', {
							className: option.className + 'uiDualListOption uiDualListOptionParentGroup_' + newDualListOptionGroupId + (option.hasClassName('sortable') ? ' sortable' : ''),
							id: this.element.identify() + '_' + option.value
						}).insert(
							new Element('span', {
								className: 'uiDualListOptionLabel'
							}).update(option.text)
						);
						
						this._convertListItem(li);
						
						newDraggableListElm.insert(li);
												
					}
						
				}, this);

				newDualListOptionGroupElm.insert({
					bottom: newDraggableListElm
				});
				
				newDualListOptionGroupElm.insert({
					bottom: new Element('div', {
						className: 'clear'
					})
				});
				
				if (!newDualListOptionGroupElm.select('li').length)
					newDualListOptionGroupElm.down('ul').update(
						new Element('li', {
							className: 'uiDualListEmptyOption'
						}).insert(new Element('div').update('There are no fields in this group'))
					);
				
				newLeftListElm.insert({ bottom:
					newDualListOptionGroupElm
				});

			}, this);
			
		}
		
		rightListElements = rightListElements.sortBy(function(elm) {
			var regs = /uiSortOrder_(\d+)/.exec(elm.className);
			return parseInt(regs[1]);
		});
		
		rightListElements.each(function(elm) {
			newRightDraggableListElm.insert({
				bottom: elm
			})
		});
		
		newRightListElm.insert({ top: newRightDraggableListElm });
		
		this.element.replace(newListElm);
		this.element = newListElm;
	
	},
	
	_convertListItem: function(li) {
	
		li.insert(
			new Element('a', {
				href: '#'
			}).observe('click', this._switchLists.bind(this)).insert(
				new Element('img', {
					src: '/_images/icons/right_arrow.png',
					className: 'uiRightArrow'
				})
			)
		);
						
		li.observe('dblclick', this._switchLists.bind(this));
		
		li.insert(
			new Element('a', {
				href: '#'
			}).observe('click', this._switchLists.bind(this)).insert(
				new Element('img', {
					src: '/_images/icons/close.png',
					className: 'uiCancel'
				})
			)
		);
		
		return li;
		
	},
	
	_switchLists: function(e) {

		Event.stop(e);
		
		var elm = Event.element(e);

		if (!elm.hasClassName('uiDualListOption'))
			elm = elm.up('li.uiDualListOption');

		var newParentElm = elm.up('.uiDualListLeft') ? this.rightList : this.leftList;

		new Effect.Fade(elm, {
			duration: 0.125,
			afterFinish: function() {

				elm.remove();

				elm.setStyle({
					position: 'relative'
				});

				optionGroupId = /uiDualListOptionParentGroup_(\d+)/.exec(elm.className)[1];
				
				Sortable.destroy(this.rightList);

				if (newParentElm == this.leftList) {

					if ($('uiDualListOptionGroup_' + optionGroupId).select('li.uiDualListEmptyOption').length) {

						new Effect.Fade($('uiDualListOptionGroup_' + optionGroupId).down('li.uiDualListEmptyOption'), {
							duration: 0.25,
							afterFinish: function(effect) {

								effect.element.remove();

								$('uiDualListOptionGroup_' + optionGroupId).down('ul').insert({
									bottom: elm
								});

							}
						});
					} else
						$('uiDualListOptionGroup_' + optionGroupId).down('ul').insert({
							bottom: elm
						});
						
				} else {

					newParentElm.down('ul').insert({
						bottom: elm
					});
					
					if ($('uiDualListOptionGroup_' + optionGroupId).select('li.uiDualListOption').length == 0) {
						
						var emptyElm = new Element('li', {
							className: 'uiDualListEmptyOption'
						});
						
						emptyElm.insert(new Element('div').update('There are no fields in this group'));
						emptyElm.hide();
						
						$('uiDualListOptionGroup_' + optionGroupId).down('ul.uiDraggableList').insert(emptyElm);
						
						new Effect.Appear(emptyElm, {
							duration: 0.25
						});
						
					}
					
				}
					
				this._initSortables(this.rightList.down('ul.uiDraggableList'));
				
				new Effect.Appear(elm, {
					duration: 0.125,
					afterFinish: function() {
						this.autosize();
					}.bind(this)
				});
				
				this._updateIds();
				
			}.bind(this)
		});
	
	},
	
	_updateSortableFields: function() {
	
		var sortableFields = $H();
	
		this.rightList.select('li.sortable').each(
		
			function(elm) {
		
				var elmLabel = elm.down('span.uiDualListOptionLabel').innerHTML;
				var elmId = /(\d+)$/.exec(elm.id)[0];
				
				sortableFields.set(elmId, elmLabel);
			
			}
		
		);

		$('sortableFields').select('select.orderFieldId').each(
		
			function(elm) {
			
				var selectedValue = $F(elm);

				elm.update('<option value=""> - - </option>');
				
				if (!sortableFields.collect(
				
					function(sortableField) {
						var option = new Element('option', {
							value: sortableField.key
						}).update(sortableField.value);
						if (sortableField.key == selectedValue) option.selected = true;
						elm.insert(option);
						return sortableField.key == selectedValue;
					}
					
				).any()) {
					
					if (elm.up('ul').select('li').length > 1)				
						new Effect.SlideUp(elm.up('li'), {
							duration: 0.25,
							afterFinish: function() {
								elm.up('li').remove();
							}
						});
					else
						elm.selectedIndex = 0;
			
				}
				
			}	
		
		);

	},
	
	_removeFromExportList: function(e) {
	
		Event.stop(e);
		
		var elm = Event.element(e);

		if (!elm.hasClassName('uiDualListOption'))
			elm = elm.up('li.uiDualListOption');
			
		new Effect.Fade(elm, {
			duration: 0.125,
			afterFinish: function() {
			
				elm.remove();
	
				this.leftList.down('ul').insert({
					bottom: elm
				});
				
				new Effect.SlideDown(elm, {
					duration: 0.125,
					afterFinish: function() {
						this.autosize();
					}.bind(this)
				});
				
			}.bind(this)
		});
	
	},
	
	_updateIds: function() {
	
		this._updateSortableFields();

		this.element.select('input.uiDraggableListItemId').invoke('remove');
			
		Sortable.sequence(this.rightList.down('ul.uiDraggableList')).each(function(id) {
			this.leftList.insert({
				before: new Element('input', {
					type: 'hidden',
					name: this.element.getAttribute('name'),
					value: id,
					className: 'uiDraggableListItemId uiDraggableListItemId_' + id
				})
			});
		}, this);
	
	},
	
	_initSortables: function(elm) {

		var containment = this.element.select('ul.uiDraggableList');
	
		Sortable.create(elm, {
			constraint: 'vertical',
			containment: containment,
			only: 'uiDualListOption',
			dropOnEmpty: true,
			hoverclass: 'uiDroppableHover',
			scroll: elm.up('div.uiList'),
			onChange: this.autosize.bind(this),
			onUpdate: this._updateIds.bind(this)
		});

	},
	
	_getChildrenHeight: function(elm) {
		
		var sum = 0;
		
		var childrenHeight = elm.immediateDescendants().inject(0, function(sum, elm2) {

			var marginTop = parseInt(elm2.getStyle('marginTop').replace('px', ''));
			var marginBottom = parseInt(elm2.getStyle('marginBottom').replace('px', ''));

			return sum + elm2.getHeight() + marginTop;
		});
		
		if (elm.childElements().last().tagName == 'UL')
			return childrenHeight + parseInt(elm.select('li').last().getStyle('marginBottom').replace('px', '')) + parseInt(elm.select('li').last().getStyle('marginTop').replace('px', ''));
		else
			return childrenHeight + parseInt(elm.childElements().last().getStyle('marginBottom').replace('px', ''));
	
	}

});

Event.observe(document, 'dom:loaded', function() {

	uiDualLists = $H();
	
	$$('select.uiDualList').each(function(elm) {
		uiDualLists.set(elm.identify(), new UIDualList(elm));
	});

});




/*--- UIButton Definition ------------------------------------------------------------------------*/

var UIButton = Class.create({
		
	initialize:	function(element) {

		if (typeof(element) == 'string') {
		
			this.element = new Element('button', {
				className: 'uiButton'
			}).update(element);
			
		} else
			this.element = $(element);
			
		this.options = Object.extend({
			onClick: function() {}
		}, arguments[1] || {});
		
		this._init();

	},
	
	_init: function() {
		if (!this.element.hasClassName('uiInitialized')) {
			Event.observe(this.element, 'click', this._handleClick.bind(this));
			this._doSpanWrap();
			this.element.addClassName('uiInitialized');
		}
	},
	
	_handleClick: function(e) {
		this.options.onClick(e);
	},
	
	_doSpanWrap: function() {
		this.element.update('<span>' + this.element.innerHTML + '</span>');
	}

});

/*----- Auto-initialize UIButtons -----*/
Event.observe(document, 'dom:loaded', function() {

	$$('.uiButton').each(function(elm) {
		new UIButton(elm);
	});

});




/*--- UILink Definition ------------------------------------------------------------------------*/

var UILink = Class.create({
		
	initialize:	function(element) {

		this.element = $(element);
			
		this.options = Object.extend({
			popup: false
		}, arguments[1] || {});
		
		this.keys = $A();
		
		this._init();

	},
	
	_init: function() {
		
		if (this.popup)
			this.element.observe('click', this._handleClick.bind(this));
		
	},
	
	_handleClick: function(e) {
		window.open(this.element.href);
	},
	
	_loadHtmlOptions: function() {
	
		if (this.element.getAttribute('rel')) {
	
			// Load the HTML options
			var attributes = this.element.getAttribute('rel').split(';');
	
			for (var i = 0; i < attributes.length; i++) {
			
				var parts = attributes[i].split('=');
				
				var optionValue;
				
				if (this._getAvailableOptions().include(parts[0])) {

					switch (parts[0]) {
					
						case 'popup':
							optionValue = parts[1].toLowerCase() == 'no' || parts[1].toLowerCase() == 'n' ? false : true;
							break;
							
						default:
							optionValue = parts[1];
							break;
						
					}

					this.options[parts[0]] = optionValue;
					
				}
				
			}
			
		}

	},
	
	_getAvailableOptions: function() {
		if (!this._keys.length) {
			for (var key in this.options)
				this._keys.push(key);
		}
		return this._keys;
	}

});

/*----- Auto-initialize UILinks -----*/
Event.observe(document, 'dom:loaded', function() {

	$$('.uiLink').each(function(elm) {
		new UILink(elm);
	});

});





/*--- UITooltip Definition -----------------------------------------------------------------------*/

UIException.prototype.addExceptionType({
	name: 'TooltipTextDoesNotExistException',
	message: 'The tooltip\'s parent element does not contain tooltip text in the \'title\' attribute or a custom tooltip object'
});

var UITooltip = Class.create({

	initialize: function(element) {

		this.element = $(element);

		if (!this.element)
			throw new UIException('ElementDoesNotExistException');

		var options = {};

		if (typeof arguments[1] == 'string')
			options.content = arguments[1];
		else
			options = arguments[1];

		this.options = Object.extend({
			title: '',
			content: '',
			fixed: true,
			offsetX: 0,
			offsetY: 0,
			showDelay: 0.5,
			hideDelay: 0.125,
			animationDuration: 0.125,
			hook: 'element',
			position: 'top right',
			persistent: false,
			autohide: true,
			autoshow: true,
			showOnClick: true,
			stayInViewport: true,
			viewportPadding: 10,
			stem: true,
			borderWidth: 2,
			borderRadius: 5
		}, options || {});

		this.keys = $A();
		this._loadHtmlOptions();
		this._initTooltip();
		
		if (this.options.persistent) {
			this.options.autohide = false;
			this.options.autoshow = false;
			this.options.showOnClick = false;
			this.options.stayInViewport = false;
		}

		if (this.options.autoshow)
			this.element.observe('mouseover', this.show.bind(this));
		else if (this.options.showOnClick) {
			this.element.observe('click', this.show.bind(this));
			this.options.showDelay = 0;
		}

		if (this.options.autohide)
			this.element.observe('mouseout', this.hide.bind(this));
		
	},
	
	_loadHtmlOptions: function() {
	
		if (this.element.hasAttribute('rel')) {
		
			var attributes = this.element.getAttribute('rel').split(';');
			
			for (var i = 0; i < attributes.length; i++) {
			
				var parts = attributes[i].split('=');
				
				var optionValue;
				
				if (this._getAvailableOptions().include(parts[0])) {
				
					switch (parts[0]) {
						
						case 'fixed':
						case 'autohide':
						case 'autoshow':
						case 'stem':
						case 'showOnClick':
						case 'persistent':
							optionValue = parts[1].toLowerCase() == 'no' || parts[1].toLowerCase() == 'n' ? false : true;
							break;
							
						case 'offsetX':
						case 'offsetY':
						case 'borderRadius':
						case 'borderWidth':
							optionValue = isNaN(parseInt(parts[1])) ? 0 : parseInt(parts[1]);
							break;
							
						case 'showDelay':
						case 'hideDelay':
						case 'animationDuration':
							optionValue = isNaN(parseFloat(parts[1])) ? 0 : parseFloat(parts[1]);
							break;
							
						default:
							optionValue = parts[1];
						
					}
				
					this.options[parts[0]] = optionValue;
					
				}
				
			}
		
		}
	
	},
	
	_initTooltip: function() {

		if ($(this.element.id + '_tooltip') || Object.isElement(this.options.content)) {
		
			this.tooltip = $(this.element.id + '_tooltip') ? $(this.element.id + '_tooltip') : this.options.content;
			this.tooltip.id = 'tt_' + this.element.identify();
			this.tooltip.hide();
			this.tooltip.addClassName('uiTooltip');
			this.tooltip.update('<div class="uiTooltipContent">' + this.tooltip.innerHTML + '</div>');
		
		} else if (this.element.getAttribute('title') || this.options.content) {

			this.tooltip = new Element('div', {
				id: 'tt_' + this.element.identify(),
				className: 'uiTooltip',
				style: 'display: none;'
			});
			this.tooltip.update('<div class="uiTooltipContent">' + (this.element.getAttribute('title') ? this.element.getAttribute('title') : this.options.content) + '</div>');
			this.element.writeAttribute('title', '');
			document.body.insert({
				bottom: this.tooltip
			});
				
		} else
			throw new UIException('TooltipTextDoesNotExistException');

		if (!this.options.title && !this.options.stem)
			this.options.borderWidth = 1;
			
		if (this.options.title) {
			this.tooltip.addClassName('uiTitledTooltip');
			this.tooltip.insert({
				top: '<div class="uiTooltipTitle">' + this.options.title + '</div>'
			});
		}
		
		this.tooltip.setStyle({
			borderWidth: this.options.borderWidth + 'px',
			WebkitBorderRadius: this.options.borderRadius + 'px',
			MozBorderRadius: this.options.borderRadius + 'px'
		});
		
		if (this.options.stem)
			this._createStem();
			
		if (!this.options.autohide && !this.options.persistent)
			this._createCloseButton();
			
		this._originalPosition = this.options.position;
		
		if (this.options.persistent) {
			this.show();
		}
		
	},
	
	show: function() {
	
		if (arguments.length) var event = arguments[0];

		if (this.tooltip.visible()) return;
		window.clearTimeout(this.hideTimeout);
		this.showTimeout = this._showTooltip.bind(this).delay(this.options.showDelay, event);
	},
	
	hide: function(event) {
		if (event.type == 'click') Event.stop(event);
		window.clearTimeout(this.showTimeout);
		this.showTimeout = this._hideTooltip.bind(this).delay(this.options.hideDelay, event);
	},
	
	_showTooltip: function(event) {

		this._positionTooltip(event);
		
		if (this._followMouse())
			this.element.observe('mousemove', this._move.bind(this));

		this.tooltip.appear({
			duration: this.options.animationDuration
		});
		
	},
	
	_hideTooltip: function(event) {
		this.tooltip.fade({
			duration: this.options.animationDuration
		});
	},
	
	_move: function(event) {
		this._positionTooltip(event);
	},
	
	_positionTooltip: function() {

		var position = this._calculateTooltipPosition(arguments[0]);

		if (this.options.stayInViewport) {

			var scrollOffset = document.viewport.getScrollOffsets();
			var viewportDimensions = document.viewport.getDimensions();
			
			var positionChanged = false;
			
			if (position.left - scrollOffset.left < this.options.viewportPadding)
				position.left = this.options.viewportPadding;
				
			if (position.top - scrollOffset.top < this.options.viewportPadding) {
				this._makePositionedBottom();
				positionChanged = true;
			}
				
			if (position.top + this.tooltip.getHeight() > viewportDimensions.height + scrollOffset.top - this.options.viewportPadding) {
				this._makePositionedTop();
				positionChanged = true;
			}
				
			if (position.left + this.tooltip.getWidth() > viewportDimensions.width + scrollOffset.left - this.options.viewportPadding)
				position.left = viewportDimensions.width + scrollOffset.left - (this.options.viewportPadding * 2) - this.tooltip.getWidth();
				
			if (positionChanged) {
				this._positionStem();
				position = this._calculateTooltipPosition(arguments[0]);
				this.options.position = this._originalPosition;
			}

		}

		this.tooltip.setStyle({
			left:	position.left + 'px',
			top:	position.top + 'px'
		});
	
	},
	
	_calculateTooltipPosition: function() {
	
		var position = {
			top: 0,
			left: 0
		};
	
		if (this.options.hook == 'element' || this.options.hook == 'parent' ||  Object.isElement(this.options.hook)) {

			var hookObj = this.options.hook == 'element' ? this.element : (this.options.hook == 'parent' ? this.element.up() : this.options.hook);
			var offset = hookObj.cumulativeOffset();

			if (this._positionedLeft())
				position.left = offset.left - this.tooltip.getWidth() - this.options.offsetX - (this.options.stem ? (this._positionedTop() || this._positionedBottom() ? this.options.borderRadius - this.options.stemPosition.width : this.options.borderWidth * 2 + this.options.stemPosition.width) : 0);
			else if (this._positionedRight())
				position.left = offset.left + hookObj.getWidth() + this.options.offsetX - (this.options.stem ? (this._positionedTop() || this._positionedBottom() ? this.options.borderWidth + this.options.borderRadius : this.options.stemPosition.width * -1) : 0);
			else
				position.left = Math.round(offset.left + (hookObj.getWidth() / 2 - this.tooltip.getWidth() / 2));
				
			if (this._positionedTop())
				position.top = offset.top - this.tooltip.getHeight() - this.options.offsetY - (this.options.borderWidth * 2) - (this.options.stem ? 8 : 0);
			else if (this._positionedBottom())
				position.top = offset.top + hookObj.getHeight() + (this.options.offsetY + (this.options.stem ? 8 : 0));
			else
				position.top = Math.round(offset.top + (hookObj.getHeight() / 2 - this.tooltip.getHeight() / 2)) - this.options.borderRadius;

		} else {

			var offset = { left: Event.pointerX(arguments[0]), top: Event.pointerY(arguments[0]) };

			if (this._positionedLeft())
				position.left = offset.left - this.tooltip.getWidth() - this.options.offsetX - (this.options.stem ? (this._positionedTop() || this._positionedBottom() ? this.options.borderRadius : this.options.borderWidth * 2 + this.options.stemPosition.width) : 0);
			else if (this._positionedRight())
				position.left = offset.left + this.options.offsetX - (this.options.stem ? (this._positionedTop() || this._positionedBottom() ? this.options.borderWidth : this.options.stemPosition.width * -1) : 0);
			else
				position.left = Math.round(offset.left - (this.tooltip.getWidth() / 2) - this.options.borderWidth * 2);
				
			if (this._positionedTop())
				position.top = offset.top - this.tooltip.getHeight() - this.options.offsetY - (this.options.borderWidth * 2) - (this.options.stem ? 8 : 0);
			else if (this._positionedBottom())
				position.top = offset.top + (this.options.offsetY + (this.options.stem ? 8 : 0));
			else
				position.top = Math.round(offset.top - (this.tooltip.getHeight() / 2));
		
		}
	
		return position;
	
	},
	
	_createCloseButton: function() {
	
		var closeButton = new Element('a', {
			href: '#', className: 'uiTooltipCloseButton'
		});
		
		closeButton.observe('click', this.hide.bind(this));
		
		this.tooltip.down('div').insert({
			top: closeButton
		});
		
		this.tooltip.addClassName('uiTooltipHasCloseButton');
		
		closeButton.insert({
			after: new Element('div', {
				className: 'clear'
			})
		});
		
	},
	
	_createEmptyTitle: function() {
		this.tooltip.insert({
			top: new Element('div', {
				className: 'uiTooltipTitle uiTooltipTitleEmpty'
			})
		});
	},
	
	_createStem: function() {
	
		if (!this.options.stem);
	
		this.stem = new Element('div', {
			className: 'uiTooltipStem'
		});
		
		this._positionStem();
		
		this.stem.setStyle({
			position: 'absolute',
			zIndex: 50
		});
		
		this.tooltip.insert({
			bottom: this.stem
		});
		
	},
	
	_positionStem: function() {
	
		if (!this.options.stem) return;
	
		// Center the stem background image for now, then make adjustments to it later
		this.options.stemBgPosition = {
			left: -32,
			top: -4
		};
		
		// Default the stem to the center and make adjustments later
		this.options.stemPosition = {
			left: this.tooltip.getWidth() / 2,
			top: this.tooltip.getHeight() / 2,
			width: 8,
			height: 8
		};

		if (this._positionedLeft()) {
			this.options.stemBgPosition.left += (this.options.stemPosition.width / 2);
			this.options.stemPosition.left = this.tooltip.getWidth() + this.options.borderWidth - (this._positionedTop() || this._positionedBottom() ? this.options.stemPosition.width + this.options.borderRadius : 0);// - this.options.borderRadius + (this.options.stem ? (this._positionedTop() || this._positionedBottom() ? 0 : this.options.borderWidth + this.options.stemPosition.width) : 0);
		} else if (this._positionedRight()) {
			this.options.stemBgPosition.left += (this._positionedTop() || this._positionedBottom() ? this.options.stemPosition.width / 2 * -1 : this.options.stemPosition.width / 2);
			this.options.stemPosition.left = 0 - (this._positionedTop() || this._positionedBottom() ? this.options.borderWidth - this.options.borderRadius : this.options.borderWidth + this.options.stemPosition.width);
		} else {
			this.options.stemBgPosition.left += 4;
			this.options.stemPosition.width = 16;
			this.options.stemPosition.left -= (this.options.stemPosition.width / 4);
		}
			
		if (this._positionedBottom()) {
			this.options.stemBgPosition.top += this.options.stemPosition.height / 2;
			this.options.stemPosition.top = 0 - this.options.borderWidth - this.options.stemPosition.height;
		} else if (this._positionedTop()) {
			this.options.stemBgPosition.top -= 4;
			this.options.stemPosition.top = this.tooltip.getHeight() + this.options.borderWidth;
		} else {
			this.options.stemBgPosition.top += 4;
			this.options.stemBgPosition.left -= this._positionedRight() ? 0 : this.options.stemPosition.width;
			this.options.stemPosition.height = 16;
			this.options.stemPosition.top -= 8;
		}
		
		this.stem.setStyle({
			background: 'url(\'/_images/ui.png\') ' + this.options.stemBgPosition.left + 'px ' + this.options.stemBgPosition.top + 'px no-repeat',
			height: this.options.stemPosition.height + 'px',
			left: this.options.stemPosition.left + 'px',
			top: this.options.stemPosition.top + 'px',
			width: this.options.stemPosition.width + 'px'
		});
	
	},
	
	_getAvailableOptions: function() {
		if (!this.keys.length) {
			for (var key in this.options)
				this.keys.push(key);
		}
		return this.keys;
	},
	
	_positionedTop: function() {
		return this.options.position.indexOf('top') > -1;
	},
	
	_positionedRight: function() {
		return this.options.position.indexOf('right') > -1;
	},
	
	_positionedBottom: function() {
		return this.options.position.indexOf('bottom') > -1;
	},
	
	_positionedLeft: function() {
		return this.options.position.indexOf('left') > -1;
	},
	
	_makePositionedTop: function() {
		if (this._positionedBottom())
			this.options.position = this.options.position.replace('bottom', 'top');
		else
			this.options.position += ' top';
	},
	
	_makePositionedBottom: function() {
		if (this._positionedBottom())
			this.options.position = this.options.position.replace('top', 'bottom');
		else
			this.options.position += ' bottom';
	},
	
	_followMouse: function() {
		return this.options.hook == 'mouse' && !this.options.fixed;
	}

});

Event.observe(document, 'dom:loaded', function() {

	$$('.uiTooltip').each(function(elm) {
		new UITooltip(elm);
	});
	
});