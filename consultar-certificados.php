<?php 
$title       = "Consultar Solicitud | Registro del Estado Civil";
$description = "Somos una institución pública dependiente del Ministerio de Justicia, que presta servicios a la ciudadanía caracterizándonos por nuestra eﬁciencia, siendo responsables de la registración de nacimientos y defunciones, celebración y registración de matrimonios y toda modificación a los mismos.";
include 'head.php';

$cod = $_GET['cod'];
$pass = $_GET['pass'];

?>
<style>
.box{
    border: 0.5px solid #00529C ;
    padding: 10px;
}
</style>
<body>
    <?php include 'header.php'; ?>
    <section class="page-breadcrumb portada">
        <div class="page-section" style="background: url(<?php echo url() ."img/background/c.jpg"; ?>) ">
            <div class="breadcumb-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="breadcrumb text-center">
                            <div class="section-titleBar white-headline text-center">
                                <h3>Verifique su certificado</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-path blanquito">
            <div class="container">
                <div class="row">
                    <ul>
                        <li class="home-bread"><a href="<?php echo url(); ?>">Inicio</a></li>
                        <li>Verifique su certificado</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="blog-page-section sec-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-9">
                    <div class="col-md-8">
                        <div class="single-sidebar-widget">
                            <div class="category-widget">
                                <form id="formBuscar" method="post" enctype="multipart/form-data" action="">
                                    <input type="text" id="cod_impresion" class="form-control" name="cod_impresion" placeholder="Ingrese el código de impresión" autocomplete="off" value="<?php echo $cod?>">
                                    <br>
                                    <input type="text" id="buscar" class="form-control" name="buscar" placeholder="Contraseña" autocomplete="off" value="<?php echo $pass?>">
                                    <div class="g-recaptcha mt-2 captchap" data-sitekey="6LfbkEYUAAAAAHSiRWWW-hDV-8mzbMXDDyKbn5cJ"></div>
                                    <center><button type="submit" id="btn-buscar" name="btn-buscar" class="thm-btn text-rigth mt-2 ">CONSULTAR</button></center>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div id="contenido">
            </div>
            <br><br>
           
            
        </div>
    </section>
    <?php include 'footer.php' ?>
    <?php include 'script.php' ?>
    <script src="<?php echo url(); ?>js/bootstrap-table.min.js"></script>
    <script src="<?php echo url(); ?>js/locale/bootstrap-table-es-AR.js"></script>
    <script src="<?php echo url(); ?>js/extensions/mobile/bootstrap-table-mobile.min.js"></script>
    <script type="text/javascript">
    var url = '<?php echo url(); ?>';

    // $.ajax({
    //     url: 'consultar-certificados-data',
    //     dataType: 'html',
    //     type: 'POST',
    //     async: true,
    //     contentType: 'application/x-www-form-urlencoded',
    //     data: { q: 'ver', cd:cd_impresion },
    //     success: function(data) {
    //         console.log(data);
    //     },
    //     error: function(jqXHR, textStatus, errorThrown) {
    //         console.log("Error en la solicitud:", textStatus, errorThrown);
    //     }
    // });

    $("#formBuscar").submit(function(e) {
        e.preventDefault();
        var data = $(this).serializeArray();
        $.ajax({
            url: 'consultar-certificados-data',
            dataType: 'json',
            type: 'POST',
            async: true,
            contentType: 'application/x-www-form-urlencoded',
            data: data,
            success: function(data) {
                //console.log(data);
                // var jsonObject = JSON.stringify(data.datos[0].d_certificado);
                // var parsedObject = JSON.parse(jsonObject);
                // console.log(parsedObject)
                $('#btn-buscar').text('CONSULTAR').prop('disabled', false);

                let html = '<p><center><img src="img/check.png" style="width:25px;height:25px;">El certificado es valido</p></center>'+
                '<div class="row">'+
                '<div class="col-md-2"></div>'+
                '<div class="col-md-8" id="certificado">'+
                    '<div class="single-sidebar-widget">'+
                        '<div class="box">'+
                            '<table class="tabla_libros" width="100%">'+
                                '<tbody>'+
                                    '<tr>'+
                                        '<td width="width:16%">Oficina Nro.:</td>'+
                                        '<td id="nro_oficina" style="text-align:left; width:10%">'+ data.data.CODOFICINA+'</td>'+
                                        '<td width="width:10%"> Libro:</td>'+
                                        '<td id="codlibro" style="text-align:left; width:10%">'+ data.data.CODLIBRO + '</td>'+
                                        '<td width="width:10%"> Tomo:</td>'+
                                        '<td id="tomo" style="text-align:left; width:7%">'+ data.data.TOMONUMERO + '</td>'+
                                        '<td width="width:7%"> Folio:</td>'+
                                        '<td id="folio" style="text-align:left; width:7%">'+ data.data.FOLIONUMERO + '</td>'+
                                        '<td width="width:10%"> Acta:</td>'+
                                        '<td id="acta" style="text-align:left; width:10%">'+ data.data.ACTANUMERO + '</td>'+
                                   ' </tr>'+
                                '</tbody>'+
                           '</table>'+
                            '<table class="tabla_of" width="100%">'+
                                '<tbody>'+
                                    '<tr>'+
                                        '<td width="width:25%">Nombre de Oficina:</td>'+
                                        '<td id="nom_oficina" style="text-align:left; width:75%">'+ data.NOMBREOFICINANEW + '</td>'+
                                    '</tr>'+
                                '</tbody>'+
                            '</table>'+
                            '<table class="tabla_nom" width="100%">'+
                                '<tbody>'+
                                    '<tr>';
                        if(data.tipo == 'CN' || data.tipo == 'CD') {
                            html += '<td width="width:25%">Nombres y Apellidos:</td>' + 
                            '<td id="nombres" style="text-align:left; width:75%">' + data.data.NOMBRES + ' ' + data.data.APELLIDOS + '</td>';
                        }else{
                            html += '<td width="width:40%">Nombres y Apellidos del Conyuge:</td>' + 
                            '<td id="nombres" style="text-align:left; width:60%">' + data.data.VNOMBRES + ' ' + data.data.VAPELLIDOS + '</td>'+
                            '</tr>'+'<tr>'+
                            '<td width="width:40%">Nombres y Apellidos de la Conyuge:</td>' + 
                            '<td id="nombres" style="text-align:left; width:60%">' + data.data.MNOMBRES + ' ' + data.data.MAPELLIDOS + '</td>';  
                        }
                        html += '</tr>'+
                                '</tbody>'+
                            '</table>'+
                            '<table class="tabla_fec" width="100%">'+
                                '<tbody>'+
                                    '<tr>';
                                    if(data.tipo == 'CN'){
                                        html += '<td width="width:25%">Fecha de Nacimiento:</td>'+
                                        '<td id="nro_oficina" style="text-align:left; width:75%">'+ data.data.NACIMIENTOFECHA[0] + data.data.NACIMIENTOFECHA[1] + '/'+  data.data.NACIMIENTOFECHA[2] + data.data.NACIMIENTOFECHA[3] +'/'+ data.data.NACIMIENTOFECHA[4] + data.data.NACIMIENTOFECHA[5] + data.data.NACIMIENTOFECHA[6] + data.data.NACIMIENTOFECHA[7] + '</td>'+
                                        '</tr>'+
                                        '<tr>'+
                                        '<td width="width:25%">Fecha de Inscripción:</td>'+
                                        '<td id="nro_oficina" style="text-align:left; width:75%">'+ data.data.INSCRIPCIONFECHA[0] + data.data.INSCRIPCIONFECHA[1] + '/'+  data.data.INSCRIPCIONFECHA[2] + data.data.INSCRIPCIONFECHA[3] +'/'+ data.data.INSCRIPCIONFECHA[4] + data.data.INSCRIPCIONFECHA[5] + data.data.INSCRIPCIONFECHA[6] + data.data.INSCRIPCIONFECHA[7] +'</td>'+
                                        '</tr>';
                                    }else if(data.tipo == 'CD'){
                                        html += '<td width="width:25%">Fecha de Defunción:</td>'+
                                        '<td id="nro_oficina" style="text-align:left; width:75%">'+ data.data.DEFUNCIONFECHA[0] + data.data.DEFUNCIONFECHA[1] + '/'+  data.data.DEFUNCIONFECHA[2] + data.data.DEFUNCIONFECHA[3] +'/'+ data.data.DEFUNCIONFECHA[4] + data.data.DEFUNCIONFECHA[5] + data.data.DEFUNCIONFECHA[6] + data.data.DEFUNCIONFECHA[7] + '</td>'+
                                        '</tr>'+
                                        '<tr>'+
                                        '<td width="width:25%">Fecha de Inscripción:</td>'+
                                        '<td id="nro_oficina" style="text-align:left; width:75%">'+ data.data.INSCRIPCIONFECHA[0] + data.data.INSCRIPCIONFECHA[1] + '/'+  data.data.INSCRIPCIONFECHA[2] + data.data.INSCRIPCIONFECHA[3] +'/'+ data.data.INSCRIPCIONFECHA[4] + data.data.INSCRIPCIONFECHA[5] + data.data.INSCRIPCIONFECHA[6] + data.data.INSCRIPCIONFECHA[7] +'</td>'+
                                        '</tr>';
                                    }else if(data.tipo == 'CM'){
                                        html += '<td width="width:25%">Fecha de Celebracion:</td>'+
                                        '<td id="nro_oficina" style="text-align:left; width:75%">'+ data.data.CELEBRACIONFECHA[0] + data.data.CELEBRACIONFECHA[1] + '/'+  data.data.CELEBRACIONFECHA[2] + data.data.CELEBRACIONFECHA[3] +'/'+ data.data.CELEBRACIONFECHA[4] + data.data.CELEBRACIONFECHA[5] + data.data.CELEBRACIONFECHA[6] + data.data.CELEBRACIONFECHA[7] + '</td>'+
                                        '<tr>';
                                    }
                        html += '</tbody>'+
                            '</table>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '</div>';
                $('#contenido').html(html);
                var status = data.status;
                var mensaje = data.mensaje;
                var datos = data.datos;
                
                if (status == 'success') {
                    $('#aviso').html(alertDismissJS(mensaje, 'success'));
                    setTimeout(function() {
                        $("#aviso").html("");
                        grecaptcha.reset();
                        $("#buscar").val('');
                    }, 2000);
                } else {
                    let html = '<p><center><img src="img/x.jpg" style="width:25px;height:25px;">El certificado no es válido</p></center>';
                    $('#contenido').html(html);
                    $('#aviso').html(alertDismissJS(mensaje, 'error'));
                    grecaptcha.reset();
                    setTimeout(function() {
                        $("#mensaje").html("");
                    }, 3000);
                }
            },
            error: function(jqXHR) {
                $('#btn-buscar').text('CONSULTAR').prop('disabled', false);
                var mensaje = null;
                grecaptcha.reset();
                mensaje = ('Mensaje de error: ' + jqXHR.responseText);
                $('#aviso').html(alertDismissJS(mensaje, 'error')).show();
                setTimeout(function() {
                    $('#aviso').html('').hide();
                }, 5000);
            }
        });
    });
    </script>
</body>

</html>