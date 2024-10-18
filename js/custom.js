function alertDismissJS(msj, tipo) {
    var salida;
    switch (tipo) {
        case 'error':
            salida = `<div class="alert alert-danger" role="alert">${msj}</div>`;
            break;

        case 'error_span':
            salida = "<span id='alerta' class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                "<span class='glyphicon glyphicon-exclamation-sign'>&nbsp;</span>" + msj + "</span>";
            break;

        case 'warning':
            salida = "<div id='alerta' class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                "<span class='glyphicon glyphicon-exclamation-sign'>&nbsp;</span>" + msj + "</div>";
            break;

        case 'success':
            salida = `<div class="alert alert-success" role="alert">${msj}</div>`;
            break;

        case 'success_span':
            salida = "<span id='alerta' class='alert alert-success alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                "<span class='glyphicon glyphicon-ok'>&nbsp;</span>" + msj + "</span>";
            break;

        case 'info':
            salida = "<div id='alerta' class='alert alert-info alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                "<span class='glyphicon glyphicon-exclamation-sign'>&nbsp;</span>" + msj + "</div>";
            break;
    }
    return salida;
}

function soloNumeros(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function separadorMiles(x) {
    if (x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    } else {
        return 0;
    }
}