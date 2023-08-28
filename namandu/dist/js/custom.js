$(function() {
    "use strict";
    $(function() {
        $(".preloader").fadeOut();
    });
    jQuery(document).on('click', '.mega-dropdown', function(e) {
        e.stopPropagation()
    });
    // ============================================================== 
    // This is for the top header part and sidebar part
    // ==============================================================  
    var set = function() {
        var width = (window.innerWidth > 0) ? window.innerWidth : this.screen.width;
        var topOffset = 55;
        if (width < 1170) {
            $("body").addClass("mini-sidebar");
            $('.navbar-brand span').hide();
        } else {
            $("body").removeClass("mini-sidebar");
            $('.navbar-brand span').show();
        }
        var height = ((window.innerHeight > 0) ? window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $(".page-wrapper").css("min-height", (height) + "px");
        }
    };
    $(window).ready(set);
    $(window).on("resize", set);
    // ============================================================== 
    // Theme options
    // ==============================================================     
    $(".sidebartoggler").on('click', function() {
        $("body").toggleClass("mini-sidebar");

    });

    // this is for close icon when navigation open in mobile view
    $(".nav-toggler").on('click', function() {
        $("body").toggleClass("show-sidebar");
        $(".nav-toggler i").toggleClass("ti-menu");
    });
	
	 $(".page-wrapper").on('click', function() {
	  $("body").removeClass("show-sidebar");
    });
	
    $(".nav-lock").on('click', function() {
        $("body").toggleClass("lock-nav");
        $(".nav-lock i").toggleClass("mdi-toggle-switch-off");
        $("body, .page-wrapper").trigger("resize");
    });
    $(".search-box a, .search-box .app-search .srh-btn").on('click', function() {
        $(".app-search").toggle(200);
        $(".app-search input").focus();
    });

    // ============================================================== 
    // Right sidebar options
    // ============================================================== 
    $(".right-side-toggle").click(function() {
        $(".right-sidebar").slideDown(50);
        $(".right-sidebar").toggleClass("shw-rside");
    });
    // ============================================================== 
    // This is for the floating labels
    // ============================================================== 
    $('.floating-labels .form-control').on('focus blur', function(e) {
        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
    }).trigger('blur');

    // ============================================================== 
    //tooltip
    // ============================================================== 
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
    // ============================================================== 
    //Popover
    // ============================================================== 
    $(function() {
        $('[data-toggle="popover"]').popover()
    })

    // ============================================================== 
    // Perfact scrollbar
    // ============================================================== 
    $('.scroll-sidebar, .right-side-panel, .message-center, .right-sidebar').perfectScrollbar();
    // ============================================================== 
    // Resize all elements
    // ============================================================== 
    $("body, .page-wrapper").trigger("resize");
    // ============================================================== 
    // To do list
    // ============================================================== 
    $(".list-task li label").click(function() {
        $(this).toggleClass("task-done");
    });
    // ============================================================== 
    // Collapsable cards
    // ==============================================================
    $('a[data-action="collapse"]').on('click', function(e) {
        e.preventDefault();
        $(this).closest('.card').find('[data-action="collapse"] i').toggleClass('ti-minus ti-plus');
        $(this).closest('.card').children('.card-body').collapse('toggle');
    });
    // Toggle fullscreen
    $('a[data-action="expand"]').on('click', function(e) {
        e.preventDefault();
        $(this).closest('.card').find('[data-action="expand"] i').toggleClass('mdi-arrow-expand mdi-arrow-compress');
        $(this).closest('.card').toggleClass('card-fullscreen');
    });
    // Close Card
    $('a[data-action="close"]').on('click', function() {
        $(this).closest('.card').removeClass().slideUp('fast');
    });
    // ============================================================== 
    // Color variation
    // ==============================================================

    var mySkins = [
        "skin-default",
        "skin-green",
        "skin-red",
        "skin-blue",
        "skin-purple",
        "skin-megna",
        "skin-default-dark",
        "skin-green-dark",
        "skin-red-dark",
        "skin-blue-dark",
        "skin-purple-dark",
        "skin-megna-dark"
    ]
    /**
     * Get a prestored setting
     *
     * @param String name Name of of the setting
     * @returns String The value of the setting | null
     */
    function get(name) {
        if (typeof(Storage) !== 'undefined') {
            //return localStorage.getItem(name)
        } else {
            window.alert('Please use a modern browser to properly view this template!')
        }
    }
    /**
     * Store a new settings in the browser
     *
     * @param String name Name of the setting
     * @param String val Value of the setting
     * @returns void
     */
    function store(name, val) {
        if (typeof(Storage) !== 'undefined') {
            localStorage.setItem(name, val)
        } else {
            window.alert('Please use a modern browser to properly view this template!')
        }
    }

    /**
     * Replaces the old skin with the new skin
     * @param String cls the new skin class
     * @returns Boolean false to prevent link's default action
     */
    // function changeSkin(cls) {
        // $.each(mySkins, function(i) {
            // $('body').removeClass(mySkins[i])
        // })
        // $('body').addClass(cls)
        // store('skin', cls)
        // return false
    // }

    // function setup() {
        // var tmp = get('skin')
        // if (tmp && $.inArray(tmp, mySkins)) changeSkin(tmp)
        // Add the change skin listener
        // $('[data-skin]').on('click', function(e) {
            // if ($(this).hasClass('knob')) return
            // e.preventDefault()
            // changeSkin($(this).data('skin'))
        // })
    // }
    // setup()
    // $("#themecolors").on("click", "a", function() {
        // $("#themecolors li a").removeClass("working"),
            // $(this).addClass("working")
    // })
	

	
});
	window.icons = { paginationSwitchDown: 'fa-toggle-down', paginationSwitchUp: 'fa-toggle-up', refresh: 'fa-sync-alt', toggleOff: 'fa-list-alt', toggleOn: 'fa-toggle-on', toggle: 'fa-list-alt', columns: 'fa-th-list', detailOpen: 'fa-plus', detailClose: 'fa-minus', fullscreen: 'fa-arrows-alt',  export: 'fa-download'};
		
	function alertDismissJS(msj, tipo){
		var salida;
		switch (tipo){
			case 'error':
				salida = swal("Error", msj, "error");  
				/*salida = "<div class='alert alert-danger'> <i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;"+msj+"&nbsp;&nbsp;&nbsp;<button type='button' class='close' data-dismiss='alert' aria-label='Close'>"+
						 "<span aria-hidden='true'>&times;</span></button></div>";*/
			break;
			
			case 'error_span':
				salida = "<span id='alerta' class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+
				"<span class='glyphicon glyphicon-exclamation-sign'>&nbsp;</span>"+msj+"</span>";
			break;
			
			case 'warning':
				salida = "<div id='alerta' class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+
				"<span class='glyphicon glyphicon-exclamation-sign'>&nbsp;</span>"+msj+"</div>";
			break;
			
			case 'ok':
				salida = $.toast({ heading: 'Operación exitosa', text: msj, position: 'top-center', loaderBg:'#008c69', icon: 'success', hideAfter: 3000 });
				/*salida = "<div class='alert alert-success'> <i class='fa fa-check-circle'></i>&nbsp;&nbsp;"+msj+"&nbsp;&nbsp;&nbsp;<button type='button' class='close' data-dismiss='alert' aria-label='Close'>"+
						 "<span aria-hidden='true'>&times;</span></button></div>";*/
			break;
			
			case 'ok_span':
				salida = "<span id='alerta' class='alert alert-success alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+
				"<span class='glyphicon glyphicon-ok'>&nbsp;</span>"+msj+"</span>";
			break;
			
			case 'info':
				salida = "<div id='alerta' class='alert alert-info alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+
				"<span class='glyphicon glyphicon-exclamation-sign'>&nbsp;</span>"+msj+"</div>";
			break;
		}
		return salida; 
	}
	
	function alertToast(msj, tipo){
		var salida;
		if (msj=="reset"){
			salida = $.toast().reset('all');
		}else{
			switch (tipo){
				case 'error':
					salida = $.toast({ heading: 'Error', text: msj, position: 'top-center', loaderBg:'#d8d8d8', icon: 'error', hideAfter: 10000  });
				break;
				case 'ok':
					salida = $.toast({ heading: 'Operación exitosa', text: msj, position: 'top-center', loaderBg:'#008c69', icon: 'success', hideAfter: 3000 });
				break;
			}
		}
		return salida; 
	}

	function fechaMYSQL(fecha){
		var fechaArr = fecha.split("/");
		var salida = fechaArr[2]+"-"+fechaArr[1]+"-"+fechaArr[0];
		return salida;
	}

	function fechaLatina(fecha){
		var fechaArr = fecha.split("-");
		var salida = fechaArr[2]+"/"+fechaArr[1]+"/"+fechaArr[0];
		return salida;
	}

	//Permitir números y puntos (decimales)
	// USO: onkeypress="numeroDecimales(event, this.value)"
	function numeroDecimales(event,data){
	if((event.charCode>= 48 && event.charCode <= 57) || event.charCode== 46 ||event.charCode == 0){
		if(data.indexOf('.') > -1){
 			if(event.charCode== 46)
  				event.preventDefault();
		}
	}else
		event.preventDefault();
	};

	/*USO: onkeypress="return soloNumeros(event)" */
	function soloNumeros(evt){
		var charCode = (evt.which) ? evt.which : evt.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
		return true;
	}

	//Separador de miles al momento de escribir
	//onkeyup="separadorMilesOnKey(event,this)"
	function separadorMilesOnKey(e,input){
		 -1 !== $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) || /65|67|86|88/.test(e.keyCode) && (!0 === e.ctrlKey || !0 === e.metaKey) || 35 <= e.keyCode && 40 >= e.keyCode || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode) && e.preventDefault()
		  var $this = $(input);
		  var num = $this.val().replace(/[^\d]/g,'').split("").reverse().join("");
		  var num2 = RemoveRougeChar(num.replace(/(.{3})/g,"$1.").split("").reverse().join(""), ".");
		  return $this.val(num2);
	}

	//Separacion de miles para guaranies y decimales para dolares
	function separadorMilesDecimales(convertString, separa){
		if(convertString.substring(0,1) == separa){
			return convertString.substring(1, convertString.length)    
			}
		return convertString;
	}

	function separadorMiles(x) {
		if(x){
			return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		}else{
			return 0;
		}
	}

	function quitaSeparadorMiles(valor){
		if(valor){
			return parseInt(valor.toString().replace(/\./g, ""));
		}else{
			return 0;
		}
	}
	
	function quitaSeparadorMilesFloat(valor){
		//sacamos los puntos
		var x = valor.replace(/\./g, "");
		//reemplazamos el punto por coma
		var x = x.replace(/\,/g, ".");
		
		if (Number.isNaN(Number.parseFloat(x))) {
			return 0;
		}else{
			return parseFloat(x);
		}
	}


	//Enter desde el input hace click al button seleccionado
	function enterClick(input, button){
		$("#"+input).keydown(function(e){
			if (e.which === 13) {
				$("#"+button).click();
			}
		});
	}
	//Quita todos los tags HTML
	function htmlToText(x){
		return x.replace(/<[^>]*>/gi, ' - ');
	}


	function getDateTime() {
		var now     = new Date(); 
		var year    = now.getFullYear();
		var month   = now.getMonth()+1; 
		var day     = now.getDate();
		var hour    = now.getHours();
		var minute  = now.getMinutes();
		var second  = now.getSeconds(); 
		if(month.toString().length == 1) {
			var month = '0'+month;
		}
		if(day.toString().length == 1) {
			var day = '0'+day;
		}   
		if(hour.toString().length == 1) {
			var hour = '0'+hour;
		}
		if(minute.toString().length == 1) {
			var minute = '0'+minute;
		}
		if(second.toString().length == 1) {
			var second = '0'+second;
		}   
		//var dateTime = day+'/'+month+'/'+year+' '+hour+':'+minute+':'+second;   
		var dateTime = day+'/'+month+'/'+year+' '+hour+':'+minute+' hs';   
		return dateTime;
	}

	//Separacion de miles para guaranies y decimales para dolares
	function RemoveRougeChar(convertString, separa){
		if(convertString.substring(0,1) == separa){
			return convertString.substring(1, convertString.length)            
			}
		return convertString;
	}

	function readImage(input, output, divFoto) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#'+divFoto).css('display', 'inline');
				$('#'+output)
					.attr('src', e.target.result)
					.height(120);
				if (input.id == "foto1"){
					$('#borrarFot1').css('display', 'inline');
					$('#borrar_foto1').val('');
				}
				if (input.id == "foto2"){
					$('#borrarFot2').css('display', 'inline');
					$('#borrar_foto2').val('');
				}
				
				
				
			};

			reader.readAsDataURL(input.files[0]);
		}
	}

	function noSubmitForm(obj){
		$(obj).on('keyup keypress', function(e) {
		  var code = e.keyCode || e.which;
		  if (code == 13) { 
			e.preventDefault();
			return false;
		  }
		});
	}
	
	function nextFocus(focused, _FORM_, salto) {
		if (typeof salto == "undefined") {
			salto = 1;
		}
		if (typeof _FORM_ == "undefined") {
			_FORM_ = $("form");
		}
		if (typeof focused == "undefined" || focused == null) {
			var focused = $(':focus');
			limitTag = false;
		}
		if (focused.length > 0 && (focused.prop("tagName") == 'INPUT' || focused.prop("tagName") == 'SELECT')) {
			var all_ = _FORM_.find("input[type=date]:not(.select2-focusser):visible,input[type=text]:not(.select2-focusser):visible,select.select2-hidden-accessible,textarea,select,button").not(':disabled, [readonly]');
			var nextIdx = all_.index(focused) + salto;
			next_ = all_.eq(nextIdx);
			if (next_.prop("tagName")=="BUTTON"){
				nextIdx = all_.index(focused) + salto + 1;
				next_ = all_.eq(nextIdx);
			}
			next_.focus();
			return false;
		}
		return true;
	}



	//Funcion que sirve para enviar variables por POST a un form en un popup. Esto evita que se vean las variables en la barra de dirección del navegador
	function OpenWindowWithPost(url, windowoption, name, params)
	{
		var form = document.createElement("form");
		form.setAttribute("method", "post");
		form.setAttribute("action", url);
		form.setAttribute("target", name);
		for (var i in params) {
			if (params.hasOwnProperty(i)) {
				var input = document.createElement('input');
				input.type = 'hidden';
				input.name = i;
				input.value = params[i];
				form.appendChild(input);
			}
		}
		document.body.appendChild(form);
		window.open("", name, windowoption).focus();
		form.submit();
		document.body.removeChild(form);
	}
	
	//Leemos cotización del día
	function cotizacion(){
		dolar_venta=0;
		$.ajax({
			dataType: 'json', async: false, cache: false, url: 'inc/cotizacion-data.php', type: 'POST', timeout: 10000,  data: {q: 'ver_cotizacion'},
			beforeSend: function(){
				NProgress.start();
			},
			success: function (json){
				NProgress.done();
				dolar_venta = parseInt(json.dolar_venta);
			},
			error: function (xhr) {
				NProgress.done();
				alertDismissJS("No se pudo consultar cotización del día. " + xhr.status + " " + xhr.statusText, 'error');
			}
		});
		return dolar_venta;
	}

	/**
	 * Calcula altura entre la altura de la ventana y la diferencia especificada por parametro. 
	 * @param {number} difencia 
	 * @param {number} minimo 
	 * @returns {number} Retorna el calculo si no el minimo especificado o por defecto.
	 */
	function calcAlturaPropiedad(difencia, minimo){
		// var dif = (typeof difencia === 'undefined' ? 100 : difencia);
		// var min = (typeof minimo === 'undefined' ? 500 : minimo);
		var calc = $(window).height() - difencia;
		return	(calc < minimo ? minimo : calc);
	}
