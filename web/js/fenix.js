(function() {
			
	// private vars
	var d = document;
	var url_prefix = '';
	var url; // aux: keeps the last (sic!) accessed URL
	// list of <select> objects
	var sls = {
		selects: ["country", "region", "town", "hotel", "contract", "offer", "button"], // manually; for convenience and simplicity
		country: {
			num: 0,
			val: "1"
		},
		region: {			
			num: 1,
			val: 0,
			response: {}, // repository to keep already retrieved data
			/**
			 * Set POST data Object for the corresponding Ajax call.
			 * 
			 * @returns {fenix_L1.sls.town.setData.fenixAnonym$0}
			 */
			setData: function() {
				return {
					id: this.val
				};
			},
			/**
			 * Trigger to execute on <select> change.
			 * 
			 * @param {Object} obj
			 * @returns {undefined}
			 */
			onChange: function(obj) {
				this.val = obj.value;
				initSelect("contract");
				initSelect("offer");
				enableDisable(this);
				// here we have exception: town must be always enabled
				getById(sls.selects[2]).removeAttribute('disabled');

				// 'memcached': use previously saved data
				if (typeof(this.response[this.val]) === "undefined") {
					return ajaxCall(this);
				} else {
					this.createOptions(this.response[this.val]);
				}
			},
			/**
			 * <select> specific options list.
			 * NB: relative to the next (sic!) <select> in the list.
			 * 
			 * @param {Object} data
			 * @returns {undefined}
			 */
			createOptions: function(data) {
				this.response[this.val] = data; // save data for future use
				
				var next = sls.getNext(this);				
				initSelect(next);

				for (var i in data) {
					$("#"+next).append("<option value='"+ data[i] +"'>"+ i +"</option>");
				}
			}
		},
		town: {
			num: 2,
			list: "<option value='0'>Select Town…</option>",
			val: '',
			response: {}, // repository to keep already retrieved data
			/**
			 * Set POST data Object for the corresponding Ajax call.
			 * 
			 * @returns {fenix_L1.sls.town.setData.fenixAnonym$0}
			 */
			setData: function() {
				return {
					id: this.val
				};
			},
			/**
			 * Trigger to execute on <select> change.
			 * 
			 * @param {Object} obj
			 * @returns {undefined}
			 */
			onChange: function(obj) {
				this.val = obj.value;
				initSelect("contract");
				initSelect("offer");
				enableDisable(this);

				if (this.val === '0') {
					return;
				}
				
				// 'memcached': use previously saved data
				if (typeof(this.response[this.val]) === "undefined") {
					return ajaxCall(this);
				} else {
					this.createOptions(this.response[this.val]);
				}
			},
			/**
			 * <select> specific options list.
			 * NB: relative to the next (sic!) <select> in the list.
			 * 
			 * @param {Object} data
			 * @returns {undefined}
			 */
			createOptions: function(data) {
				this.response[this.val] = data; // save data for future use
				
				var next = sls.getNext(this);				
				initSelect(next);

				for (var i in data) {
					$("#"+next).append("<option value='"+ data[i] +"'>"+ i +"</option>");
				}
			}
		}, 
		hotel: {
			num: 3,
			list: "<option value='0'>Pick up a Hotel…</option>",
			val: '',
			name: '',
			response: {},
			setData: function() {
				return {
					id: this.val
				};
			},
			onChange: function(obj) {
				this.val = obj.value;
				initSelect("offer");
				enableDisable(this);

				if (this.val === '0') {
					return;
				}
				
				if (typeof(this.response[this.val]) === "undefined") {
					return ajaxCall(this);
				} else {
					this.createOptions(this.response[this.val]);
				}
			},
			createOptions: function(data) {		
				this.response[this.val] = data;
				
				var next = sls.getNext(this);				
				initSelect(next);

				for (var i in data) {
					$("#"+next).append("<option value='"+i+"'>Season: "+data[i][0]+". Valid: "+data[i][1]+"—"+data[i][2]+"</option>");
				}
			}
		}, 
		contract: {
			num: 4,
			list: "<option value='0'>Pick up a Contract…</option>",
			val: '',
			response: {},
			setData: function() {
				return {
					ids: [
						sls.country.val, 
						sls.town.val, 
						sls.hotel.val, 
						this.val
				]};
			},
			onChange: function(obj) {
				this.val = obj.value;
				enableDisable(this);
				
				if (this.val === '0' || this.val === '-1') {
					return;
				}
				
				if (typeof(this.response[this.val]) === "undefined") {
					return ajaxCall(this);
				} else {
					this.createOptions(this.response[this.val]);
				}
			},
			createOptions: function(data) {		
				this.response[this.val] = data;
				
				var next = sls.getNext(this);				
				initSelect(next);
				
				// option to output base prices (w/o discounts)
				$("#"+next).append("<option value='-1'>Contract Rates</option>");

				for (var i in data) {
					$("#"+next).append("<option value='"+i+"'>"+data[i][0]+". Valid: "+data[i][1]+"—"+data[i][2]+", book: "+data[i][3]+"—"+data[i][4]+" "+data[i][5]+"</option>");
				}
			}
		}, 
		offer: {
			num: 5,
			list: "<option value='0'>Select an Offer…</option>",
			val: '',
			onChange: function(obj) {
				this.val = obj.value;
				enableDisable(this);				
				return true;
			}
		}, 
		button: {
			num: 6,
			val: '',
			setData: function() {
				return { // full list of ids now
					ids: [
						sls.country.val, 
						sls.town.val, 
						sls.hotel.val,
						sls.contract.val,
						sls.offer.val
					],
					hotelname: sls.hotel.name
				};
			},
			onCreate: function() {
				ajaxCall(this); // /fenix/resultcreate page
			},
			/**
			 * The name is foo, i.e., used here for consistency but DOESN'T create any options!
			 * It displays final RESULT here (simply by insertion into <div>).
			 * 
			 * @param {Object} data
			 * @returns {jQuery}
			 */
			createOptions: function(data) {
				$("#content").html(data);
			}
		},
		getVal: function(n) {
			return this[n].val;
		},
		/**
		 * Get a <select> object and return the next one id in the list.
		 * 
		 * @param {Object} sel
		 * @returns {String}
		 */
		getNext: function(sel) {
			if (typeof(sel) === "object" && typeof(sel.num) !== "undefined") {
				var index_next = sel.num + 1;

				return this.selects[index_next];
			}
			
			return false;
		}
	};
	
	// stopsales object (has about the same structure and idea as sls)
	var ss = {
		form: null,
		hotel: {
			num: 1,
			val: 0,
			onChange: function(obj) {
				this.val = obj.value;
			}
		},
		from: {
			num: 2,
			val: '',
			onChange: function(obj) {
				this.val = obj.value;
			}
		}, 
		to: {
			num: 3,
			val: '',
			onChange: function(obj) {
				this.val = obj.value;
			},
			setVal: function(val) {
				this.val = val;
			}
		}, 
		button: {
			num: 4,
			val: '',
			setData: function() {
				return { // full list of ids now
					ids: [
						ss.hotel.val, 
						ss.from.val, 
						ss.to.val,
					]
				};
			},
			onCreate: function() {
				ajaxCall(this); // /fenix/stopsalesresult page
			},
			/**
			 * The name is foo, i.e., used here for consistency but DOESN'T create any options!
			 * It displays final RESULT here (simply by insertion into <div>).
			 * 
			 * @param {Object} data
			 * @returns {jQuery}
			 */
			createOptions: function(data) {
				$("#content").html(data);
			}
		},
		setForm: function(form) {
			this.form = form;
		}
	};
	
	
	function getById(id) {
		return d.getElementById(id);
	}
	
	/**
	 * Enable <select> next to current and disable all others to the end of the list.
	 * 
	 * @param {Object} slsObj sls sub-Object
	 * @returns {undefined}
	 */
	function enableDisable(slsObj) {		
		// int; index of the next or current <select>
		var index_next = slsObj.val === "0" ? slsObj.num : slsObj.num + 1; 
		
		getById(sls.selects[index_next]).removeAttribute('disabled'); // make next enabled
		
		// disable all to the right of the next
		for (var i = index_next + 1; i < sls.selects.length; i++) {
			getById(sls.selects[i]).setAttribute('disabled', 'disabled');
		}
	}
	
	/**
	 * Prepare an Ajax call.
	 * 
	 * @param {Object} sel <select> object from sls.
	 * @returns {jqXHR}
	 */
	var request = function(sel) {
		return $.ajax({
			method: "POST",
			url: url_prefix + url,
			dataType: "json",
			data: sel.setData()
		});
	};
	
	/**
	 * Ajax call to retrieve corresponding to sel object data.
	 * 
	 * @param {Object} sel
	 * @returns {undefined}
	 */
	function ajaxCall(sel) {	
		var req = request(sel);
		
		$("#loader").addClass("loading");
		
		req.done(function(response) {
			if (response.status === 'success') {
				if (response.substatus === 'expired' || response.substatus === 'wrongdata') {
					$("#excelbtn").hide();
				}				
				sel.createOptions(response.data);
			} else if (response.status === 'error') {
				alert('Error.');
			}
			
			$("#loader").removeClass("loading");
		});

		req.fail(function(jqXHR, textStatus) {
			alert("Request failed: " + textStatus);
			$("#loader").removeClass("loading");
		});
	}

	/**
	 * Common func for several <select>s (defined by 'id').
	 * Clears <option>s list and replaces it with the default one.
	 * 
	 * @param {string} id
	 * @returns {undefined}
	 */
	function initSelect(id) {
		$("#"+id+" option").remove(); // remove existing <option>s
		$("#"+id).append( sls[id].list );
	}
	
	/**
	 * Stopsales: check dates etc.
	 * 
	 * @param {object} form
	 * @returns {Boolean}
	 */
	function checkSsForm(form) {
		if (form.from.value === '' || form.to.value === '') {			
			return false;
		}
		return true;
	}
	
	
/* -- PUBLIC SECTION --------------------------*/

	var o = function() {
		return {
			f: f1,
			tmp: "value of tmp"
		};
	};
	
	// public property
	o.a = "Func";
	
	/**
	 * Universal func for a <select> change.
	 * Trigger designed to get corresponding list on corresponding <select> change.
	 * 
	 * @param {Object} obj
	 * @param {string} path
	 * @returns {Object}
	 */
	o.onChange = function(obj, path) {
		var id = obj.id;
		url = path;		
		$("#content").html(''); // clear content on click
		$("#excelbtn").hide();
		
		return sls[id].onChange(obj);
	};
	
	/**
	 * Universal func for a Stop Sales change.
	 * Trigger designed to get corresponding list on corresponding <select> change.
	 * 
	 * @param {Object} obj
	 * @returns {Object}
	 */
	o.onChangeSs = function(obj) {
		var id = obj.id;
		$("#content").html(''); // clear content on click
		$("#excelbtn").hide();
		
		return ss[id].onChange(obj);
	};
	
	/**
	 * Create the final result; access path using Ajax.
	 * 
	 * @param {string} path
	 * @returns {undefined}
	 */
	o.onCreate = function(path) {
		url = path;
		sls.hotel.name = $("#hotel option:selected").text();
		$("#excelbtn").show();
		
		sls.button.onCreate();
	};
	
	o.getSls = function() {
		return sls;
	};

	o.getSs = function() {
		return ss;
	};

	o.getVal = function(name) {
		return sls[name].val;
	};
	
	/**
	 * For local use: prepend URL.
	 * 
	 * @param {String} val
	 * @returns {undefined}
	 */
	o.setUrlPrefix = function(val) {
		url_prefix = val;
	};
	
	/**
	 * All Datepickers load
	 * @param {String} cl Input class
	 * @returns {void}
	 */
	o.loadDatepicker = function(cl) {
		var now = new Date();
		var year = now.getFullYear() + 3;

		$("." + cl).datepicker({
			showOn: "both",
			buttonImage: url_prefix +"/images/ui/calendar.gif",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			firstDay: 1,
			dateFormat: "dd.mm.yy",
			changeMonth: true,
			changeYear: true,
			yearRange: "2016:"+ year
		});

		$("." + cl).inputmask("99.99.2099"); // use date/time extension of the plugin to allow correct numbers only
	};
		
	/**
	 * Init datepicker: set the second value equal to the first if not empty.
	 * 
	 * @param {String} from Id of 'from' date input
	 * @param {String} to Id of 'to' date input
	 * @returns {jQuery}
	 */
	o.initDp = function(from, to) {
		$("#"+from).change(function() {
			if($("#"+to).val() === '') {
				$("#"+to).val($("#"+from).val());
				ss.to.setVal($("#"+to).val());
			}
		});	
	};
	
	/**
	 * Execute on button hit. 
	 * Not 'submit', in fact, but anything...
	 * 
	 * @param {Object} form
	 * @param {String} URL to execute
	 * @returns {undefined}
	 */
	o.onFormSubmit = function(form, path) {
		if (! checkSsForm(form)) {
			alert("Ошибка. Форма не заполнена.");
			return false;
		}
		
		url = path;
		ss.setForm(form);
		$("#excelbtn").show();		
		ss.button.onCreate();
	};
	
	this.fenix = o;

})();
