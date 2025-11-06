        document.addEventListener('contextmenu', function(event) {
			event.preventDefault();
		});

		$(window).on('keydown', function(event) {
			if (event.keyCode == 123) {
			    event.preventDefault();
				return false;// alert('Entered F12');
			} else if (event.ctrlKey && event.shiftKey && event.keyCode == 67) {
			    event.preventDefault();
				return false; //Prevent from ctrl+shift+c
			} else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
			    event.preventDefault();
				return false; //Prevent from ctrl+shift+i
			} else if (event.ctrlKey && event.keyCode == 73) {
			    event.preventDefault();
				return false; //Prevent from ctrl+shift+i
			}
			
			// Block Ctrl+Shift+J (opens the console in some browsers)
            if (event.ctrlKey && event.shiftKey && event.keyCode === 74) {
                event.preventDefault();
                return false;
            }
            
            // Block Ctrl+U (view source)
            if (event.ctrlKey && event.keyCode === 85) {
                event.preventDefault();
                return false;
            }
            
            // Block Ctrl+Shift+C (used for inspecting elements)
            if (event.ctrlKey && event.shiftKey && event.keyCode === 67) {
                event.preventDefault();
                return false;
            }
            
            // Block Ctrl+S (sometimes used to save the page)
            if (event.ctrlKey && event.keyCode === 83) {
                event.preventDefault();
                return false;
            }
            
            // Block F12 key on macOS (cmd+option+I)
            if (event.metaKey && event.altKey && event.keyCode === 73) {
                event.preventDefault();
                return false;
            }
		});

		$(document).ready(function() {
			$('.hcpreadonly').prop('readonly', true);
			
			$('.gradecheckbox').on('change', function(event){
			    var shouldChange = false;
            if (!shouldChange) {
                event.preventDefault();
                event.stopImmediatePropagation();
                this.checked = !this.checked;
            }
			});

			$('input[name="hcp4"]').on('change', function() {
				if ($(this).is(':checked')) {
					$('input[name="hcp4"]').not(this).prop('checked', false);
				}
			});

			$('input[name="hcp97_1"]').on('change', function() {
				if ($(this).is(':checked')) {
					$('input[name="hcp97_1"]').not(this).prop('checked', false);
				}
			});

			$('input[name="hcp97_2"]').on('change', function() {
				if ($(this).is(':checked')) {
					$('input[name="hcp97_2"]').not(this).prop('checked', false);
				}
			});

			$('input[name="hcp98_1"]').on('change', function() {
				if ($(this).is(':checked')) {
					$('input[name="hcp98_1"]').not(this).prop('checked', false);
				}
			});

			$('input[name="hcp98_2"]').on('change', function() {
				if ($(this).is(':checked')) {
					$('input[name="hcp98_2"]').not(this).prop('checked', false);
				}
			});

			$('input[name="hcp99_1"]').on('change', function() {
				if ($(this).is(':checked')) {
					$('input[name="hcp99_1"]').not(this).prop('checked', false);
				}
			});

			$('input[name="hcp99_2"]').on('change', function() {
				if ($(this).is(':checked')) {
					$('input[name="hcp99_2"]').not(this).prop('checked', false);
				}
			});

			$('input[name="hcp100_1"]').on('change', function() {
				if ($(this).is(':checked')) {
					$('input[name="hcp100_1"]').not(this).prop('checked', false);
				}
			});

			$('input[name="hcp100_2"]').on('change', function() {
				if ($(this).is(':checked')) {
					$('input[name="hcp100_2"]').not(this).prop('checked', false);
				}
			});

		});

		function savedata() {
			var formdata = new FormData($('form#studenthcpdata')[0]);

			$.ajax({
				url: 'savestudentdata.php',
				type: 'POST',
				processData: false,
				contentType: false,
				data: formdata,
				dataType: 'JSON',
				success: function(res) {

					if (res.status == 'success') {
						$('#already_filled').val(res.alreadyfilled);
						Swal.fire({
							title: "Success...",
							text: res.info,
							icon: "success"
						});
						$('.sticky-button-print').css('display','block');
					} else {
						Swal.fire({
							icon: "error",
							title: "Oops...",
							text: res.info,
							//   footer: '<a href="#">Why do I have this issue?</a>'
						});
					}

				}
			});
		}
		
		function handlePrint() {
            const printButton = document.getElementById('printbtn');
            printButton.style.display = 'none';

            window.print();

            setTimeout(() => {
                printButton.style.display = 'block';
            }, 1000);
        }