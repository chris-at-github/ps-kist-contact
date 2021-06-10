(function () {
	'use strict';

	xna.on('documentLoaded', function() {
		document.addEventListener('contactSearchLoaded', function(event) {

			// Dokumenten-Klasse
			document.body.classList.add('is-contact-open');
			document.body.classList.remove('is-contact-loading');

			let container = event.detail.resultContainer;

			// Fertiges HTML in den vorbereiteten Container laden
			container.innerHTML = event.detail.responseBody;

			// Eltern-DIV auf die Groesse aufspannen
			container.parentElement.style.maxHeight = container.offsetHeight + 'px';
		});

		document.querySelectorAll('.ce-contact-search').forEach(function(node, index) {
			let form = node.querySelector('form');
			let resultContainer = node.querySelector('.contact-search--result-container');
			let zipRegex = '';

			form.addEventListener('submit', function(event) {
				let data = new FormData(form);
				let uri = form.getAttribute('action');

				// Dokumenten-Klasse
				document.body.classList.add('is-contact-loading');

				fetch(uri, {
					body: data,
					method: 'post',
				}).then(function(response) {
						return response.text();

					}).then(function(body) {
						xna.fireEvent('contactSearchLoaded', {
							responseBody: body,
							resultContainer: resultContainer
						});
					});

				event.preventDefault();
			});

			// Laenderauswahl
			node.querySelector('.contact-search--countries').addEventListener('change', function(event) {
				let value = parseInt(this.value);

				let target = {
					zip: node.querySelector('.contact-search--zip'),
					button: node.querySelector('button[type="submit"]')
				};

				// kein Land ausgewaehlt -> Versand des Formulars verhindern
				if(value === 0) {

					// Button sperren
					target.button.disabled = true;

				} else if(typeof(xna.data.contact[value]) !== 'undefined') {

					// Button freigeben
					target.button.disabled = false;

					// es wurde ein Eintrag ausgewaehlt zu dem es eine Konfiguration gibt -> also keine PLZ-Eingabe noetig
					// 1. Zip Feld freischalten
					// 2. Ober-Variable zipRegex beschreiben
					if(xna.data.contact[value].zipRegex !== '') {
						target.zip.disabled = false;
						zipRegex = xna.data.contact[value].zipRegex;

					// 1. Zip Feld sperren
					// 2. Zip Feld leeren
					} else {
						target.zip.value = '';
						target.zip.disabled = true;
					}
				}
			});

			// Zip Eingabe
			node.querySelector('.contact-search--zip').addEventListener('keypress', function(event) {
				if(zipRegex !== '') {
					let regex = new RegExp(zipRegex, 'g');
					let value = this.value + String.fromCharCode(event.charCode || event.keyCode);

					if(value.match(regex) === null) {
						event.preventDefault();
					}
				}
			});
		});
	});
})();