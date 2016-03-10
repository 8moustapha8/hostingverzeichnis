$(document).ready(function(){
	
	//Monatskosten-Slider initialisieren
	$(".cost.range").noUiSlider({
		start: [1, 50],
		connect: true,
		margin: 5,
		range: {
			'min': 1,
			'max': 50
		},
		serialization: {
			lower: [
				$.Link({
					target: $(".cost.value_left"),
					method: "text"
				})
			],
			upper: [
				$.Link({
					target: $(".cost.value_right"),
					method: "text"
				})
			],
			format: {
				prefix: 'CHF ',
				decimals: 0,
				postfix: '.-'
			}
		}
	});
	
	//Speicherplatz-Slider initialisieren
	$(".space.range").noUiSlider({
		start: [0, 100],
		connect: true,
		margin: 10,
		range: {
			'min': 0,
			'max': 100
		},
		serialization: {
			lower: [
				$.Link({
					target: $(".space.value_left"),
					method: "text"
				})
			],
			upper: [
				$.Link({
					target: $(".space.value_right"),
					method: "text"
				})
			],
			format: {
				decimals: 0,
				postfix: ' GB'
			}
		}
	});
		
	//Funktion kann die Parameter der URL auslesen. Ist kein Wert vorhanden, wir "null" zurückgegeben.
	function getURLParameter(name) {
	  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
	}
	
	//Werte für die Monatskosten mithilfe der Funktion "getURLParameter()" aus URL auslesen und auf den Slider ".cost.range" anwenden
	$(".cost.range").val([getURLParameter('cost_from'), getURLParameter('cost_to')]);
	//Werte für den Speicherplatz mithilfe der Funktion "getURLParameter()" aus URL auslesen und auf den Slider ".space.range" anwenden
	$(".space.range").val([getURLParameter('space_from'), getURLParameter('space_to')]);
	//Wert für "#ssl" aus URL auslesen und auf Checkbox übertragen (0 = false; 1 = true)
	if(getURLParameter('ssl') == 1) {
		$('#ssl').prop('checked', true);
	} else {
		$('#ssl').prop('checked', false);
	}
	//Wert für "lang" (Serverstandort) aus URL auslesen und auf Checkbox übertragen
	if(getURLParameter('lang')) {
		var lang = getURLParameter('lang').split("-");
		for (var i = 0; i < lang.length - 1; i++) {
			$('#' + lang[i]).prop('checked', true);
		}
	}
	
	//Klick auf den Button "Suchen" (Filter)
	$("#filter_shared").click(function(){
		//Prae- und Suffixe der Sliderwerte entfernen
		var cost = $('.cost.range').val();
			cost[0] = cost[0].match(/[0-9]+/g);
			cost[1] = cost[1].match(/[0-9]+/g);
		var space = $('.space.range').val();
			space[0] = space[0].match(/[0-9]+/g);
			space[1] = space[1].match(/[0-9]+/g);
		
		//Variable für "#ssl" definieren und füllen (0 = false; 1 = true)
		if ($('#ssl').is(":checked")) {
			var ssl = 1;
		} else {
			var ssl = 0;
		}
	
		//Variable für die verschiedenen Serverstandorte definieren und füllen (0 = false; 1 = true)
		if ($('#de').is(":checked")) {
			var de = "de-";
		} else {
			var de = "";	
		}
		if ($('#au').is(":checked")) {
			var au = "au-";
		} else {
			var au = "";	
		}
		if ($('#ch').is(":checked")) {
			var ch = "ch-";
		} else {
			var ch = "";	
		}
		if ($('#li').is(":checked")) {
			var li = "li-";
		} else {
			var li = "";	
		}

		//Variabeln der Sliderwerte der Uebersicht halber neu setzen
		var cost_from = cost[0], cost_to = cost[1], space_from = space[0], space_to = space[1];
		
		//URL mit allen Parameterwerten aufrufen.
		setGetParameter('cost_from', cost_from);
		setGetParameter('cost_to', cost_to);
		setGetParameter('space_from', space_from);
		setGetParameter('space_to', space_to);
		setGetParameter('ssl', ssl);
		setGetParameter('lang', de + au + ch + li);
		setGetParameter('page', 1);
	});
});