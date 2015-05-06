$(function() {
	var availableTags = [
		"DAÑOS Y PERJUICIOS",
		"VIOLACION DE DERECHOS CIVILES",
		"ARRESTO ILEGAL",
		"IMPUGNACION DE CONFISCACION",
		SENTENCIA DECLARATORIA",
		"COBRO DE DINERO",
		"REPRESALIAS (LEY 426 DE 7 DE NOVIEMBRE DE 2000)",
		"TRASLADO INJUSTIFICADO",
		"HOSTIGAMIENTO LABORAL",
		"ACCION REIVINDICATORIA",
		"BRUTALIDAD POLICIACA",
		"EJECUCION DE",
		"RESOLUCION",
		"RECURSO EXTRAORDINARIO",
		"MANDAMUS",
		"NULIDAD DE CONTRATOS",
		"INJUNCTION PRELIMINAR",
		"INJUNCTION PERMANENTE",
		"AMERICANS WITH DISBILITIES ACT",
		"LEY DE IGUALDAD DE OPORTUNIDADES DE EMPLEO PARA PERSONAS CON IMPEDIMENTOS",
		"DENEGATORIA DE SOLICITUD PARA EJERCER COMO DETECTIVE PRIVADO 'GUARDIA DE SEGURIDAD CLASE C'",
		"DISCRIMEN POR IMPEDIMENTO",
		"CIVIL RIGHTS ACTION UNDER 42 USCA § 1983, 1985, 1986",
		"CONSIGNACION DE FONDOS"
	];
	function split( val ) {
		return val.split( /,\s*/ );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}
	$( "#tags" )
	// don't navigate away from the field on tab when selecting an item
	.bind( "keydown", function( event ) {
		if ( event.keyCode === $.ui.keyCode.TAB &&
		$( this ).autocomplete( "instance" ).menu.active ) {
			event.preventDefault();
		}
	})
	.autocomplete({
		minLength: 0,
		source: function( request, response ) {
			// delegate back to autocomplete, but extract the last term
			response( $.ui.autocomplete.filter(availableTags, extractLast( request.term ) ) );
		},
		focus: function() {
			// prevent value inserted on focus
			return false;
		},
		select: function( event, ui ) {
			var terms = split( this.value );
			// remove the current input
			terms.pop();
			// add the selected item
			terms.push( ui.item.value );
			// add placeholder to get the comma-and-space at the end
			terms.push( "" );
			this.value = terms.join( ", " );
			return false;
		}
	});
});

function showUser(str) {
	//  if (str == "") {
	//      document.getElementById("txtHint").innerHTML = "";
	//      return;
	//  } else { 
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","getuser.php?q="+str,true);
	xmlhttp.send();
	//  }
}
