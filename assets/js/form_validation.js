/********************************* Validaciones de codeigniter  ******************************/

Ext.apply(Ext.form.VTypes, {
	// Validacion Integer
	integerMask: /^[\-+]?[0-9]+$/,
	integer: function(v) {
		return Ext.form.VTypes.integerMask.test(v);
	},
	integerText:'Debe ingresar s\u00F3lo n\u00FAmeros',
	
	// Validacion Numeric
	numericMask: /^[\-+]?[0-9]*\.?[0-9]+$/,
	numeric: function (v) { 
		return Ext.form.VTypes.numericMask.test(v);
	},
	numericText:'Debe ingresar s\u00F3lo n\u00FAmeros',
	
	// Validacion email
	valid_emailMask: Ext.form.VTypes.emailMask,
	valid_email: function(v) {
		return Ext.form.VTypes.email(v);
	},
	valid_emailText: 'Este campo debe ser una direcci\u00F3n de correo electr\u00F3nico con el formato "usuario@dominio.com"',
	
	//Validacion numeros naturales
	is_naturalMask: /^[0-9]+$/,
	is_natural: function (v) {
		return Ext.form.VTypes.is_naturalMask.test(v);
	},
	is_naturalText: 'Debe ingresar s\u00F3lo n\u00FAmeros',
	
	//Validacion Alpha con espacios
	alphaMask: /^[a-z\u00D1\u00F1\u00C0\u00E0\u00C1\u00E1\u00E9\u00C9\u00CD\u00ED\u00D3\u00F3\u00DA\u00FA\u00C4\u00E4\u00CB\u00EB\u00CF\u00EF\u00D6\u00F6\u00DC\u00FC\u0178\u00FF\s]+$/i,
	alpha: function (v) {
		return Ext.form.VTypes.alphaMask.test(v);
	},
	alphaText: 'Este campo s\u00F3lo debe contener letras y espacios',
	
	// Validacion Alpha con numeros y espacios
	alpha_numericMask:  /^[a-z0-9,;-_:\u00D1\u00F1\u00C0\u00E0\u00C1\u00E1\u00E9\u00C9\u00CD\u00ED\u00D3\u00F3\u00DA\u00FA\u00C4\u00E4\u00CB\u00EB\u00CF\u00EF\u00D6\u00F6\u00DC\u00FC\u0178\u00FF\.\s]+$/i,
	alpha_numeric: function (v) {
		return Ext.form.VTypes.alpha_numericMask.test(v);
	},
	alpha_numericText: 'Este campo s\u00F3lo debe contener letras, n\u00FAmeros y espacios',
	
	// Validacion de URL
	valid_urlMask: /[\w+&@#\/%\?=\~_|!:,.;]+$/i,
	valid_url: function (v) {
		return (/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%\?=\~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i).test(v);
		//return Ext.form.VTypes.valid_urlMask.test(v);
	},
	valid_urlText: 'Este campo debe ser una URL con el formato "http:/'+'/www.dominio.com"',
	
	// Validacion De fechas
	valid_dateMask:/([0-3][0-9])\/([0-9]{1,2})\/([1-2][0-9]{3})/,
	valid_date: function (v){
		if(Ext.form.VTypes.valid_dateMask.test(v))
			return false;
		else{
			var strSeparator = strValue.substring(2,3);
			var arrayDate = strValue.split(strSeparator); 
			//create a lookup for months not equal to Feb.
			var arrayLookup = {
				'01' : 31,
				'03' : 31, 
				'04' : 30,
				'05' : 31,
				'06' : 30,
				'07' : 31,
				'08' : 31,
				'09' : 30,
				'10' : 31,
				'11' : 30,
				'12' : 31
			}
			var intDay = parseInt(arrayDate[0],10); 

			//check if month value and day value agree
			if(arrayLookup[arrayDate[1]] != null) {
				if(intDay <= arrayLookup[arrayDate[1]] && intDay != 0)
					return true; //found in lookup table, good date
			}
    
			//check for February (bugfix 20050322)
			//bugfix  for parseInt kevin
			//bugfix  biss year  O.Jp Voutat
			var intMonth = parseInt(arrayDate[1],10);
			if (intMonth == 2) { 
				var intYear = parseInt(arrayDate[2]);
				if (intDay > 0 && intDay < 29) {
					return true;
				}
				else if (intDay == 29) {
					if ((intYear % 4 == 0) && (intYear % 100 != 0) || (intYear % 400 == 0)) {
						// year div by 4 and ((not div by 100) or div by 400) ->ok
						return true;
					}   
				}
			}
		}  
		return false; //any other values, bad date
	},
	valid_dateText: 'Debe ingresar una fecha valida con el formato dd/mm/yyyy',
	
	// Validacion alpha_dash de CodeIgniter
	alpha_dashMask: /^([.a-z0-9_-])+$/i,
	alpha_dash: function (v) {
		return Ext.form.VTypes.alpha_dashMask.test(v);
	},
	alpha_dashText: 'Este campo s\u00F3lo debe contener caracteres alfanuméricos, guiones bajos o guiones'

	
});