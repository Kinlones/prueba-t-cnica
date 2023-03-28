<?php
include_once("/conf/config.php");
$RegionesYcomunas=false;
$resultado_regiones = mysql_query('SELECT * FROM region_comuna ORDER by ID ASC LIMIT 1');
if (!$resultado_regiones)
{
    die('Consulta no válida: ' . mysql_error());
}
else
{
  while ($fila = mysql_fetch_assoc($resultado_regiones))
  {
    $RegionesYcomunas=utf8_encode($fila['regiones']);
  }
}
if ($RegionesYcomunas!=false)
{
  //$RegionesYcomunas=json_decode($RegionesYcomunas);
}
//$RegionesYcomunas=json_encode($RegionesYcomunas, JSON_UNESCAPED_UNICODE);
//var_dump($RegionesYcomunas);
$cadidatos=false;
$resultado_cadidatos = mysql_query('SELECT * FROM candidatos ORDER by ID ASC');
if (!$resultado_cadidatos)
{
    die('Consulta no válida: ' . mysql_error());
}
else
{
  while ($fila = mysql_fetch_assoc($resultado_cadidatos))
  {
    $cadidatos[]=array('id' => $fila['id'], "tipo" => $fila['tipo']);
  }
}
//var_dump($cadidatos);
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Prueba de Dignostico</title>
  </head>
  <body>
    <script type="text/javascript">
      var nombres=false;
      var alias=false;
      var ruts=false;
      var correos=false;
      var regioconcomuna=false;
      var candidatura=false;
      var RegionesYcomunas = <?php echo $RegionesYcomunas;?>
      //RegionesYcomunas = JSON.parse(RegionesYcomunas);
      console.log(RegionesYcomunas.regiones[0].comunas);

      jQuery(document).ready(function ()
      {
        var iRegion = 0;
        var htmlRegion = '<option value="sin-region">Seleccione región</option><option value="sin-region">--</option>';
        var htmlComunas = '<option value="sin-region">Seleccione comuna</option><option value="sin-region">--</option>';

        jQuery.each(RegionesYcomunas.regiones, function () {
          htmlRegion = htmlRegion + '<option value="' + RegionesYcomunas.regiones[iRegion].region + '">' + RegionesYcomunas.regiones[iRegion].region + '</option>';
          iRegion++;
        });

        jQuery('#regiones').html(htmlRegion);
        jQuery('#comunas').html(htmlComunas);

        jQuery('#regiones').change(function () {
          var iRegiones = 0;
          var valorRegion = jQuery(this).val();
          var htmlComuna = '<option value="sin-comuna">Seleccione comuna</option><option value="sin-comuna">--</option>';
          jQuery.each(RegionesYcomunas.regiones, function () {
            if (RegionesYcomunas.regiones[iRegiones].region == valorRegion) {
              var iComunas = 0;
              jQuery.each(RegionesYcomunas.regiones[iRegiones].comunas, function () {
                htmlComuna = htmlComuna + '<option value="' + RegionesYcomunas.regiones[iRegiones].comunas[iComunas] + '">' + RegionesYcomunas.regiones[iRegiones].comunas[iComunas] + '</option>';
                iComunas++;
              });
            }
            iRegiones++;
          });
          jQuery('#comunas').html(htmlComuna);
        });
        jQuery('#comunas').change(function () {
          if (jQuery(this).val() == 'sin-region') {
            alert('selecciones Región');
          } else if (jQuery(this).val() == 'sin-comuna') {
            alert('selecciones Comuna');
          }
        });
        jQuery('#regiones').change(function () {
          if (jQuery(this).val() == 'sin-region') {
            alert('selecciones Región');
          }
        });
      });

      var Fn = {
        validaRut : function (rutCompleto) {
          if (!/^[0-9]+[-]{1}[0-9kK]{1}$/.test( rutCompleto ))
            return false;
          var tmp   = rutCompleto.split('-');
          var digv  = tmp[1]; 
          var rut   = tmp[0];
          if ( digv == 'K' ) digv = 'k' ;
          return (Fn.dv(rut) == digv );
        },
        dv : function(T){
          var M=0,S=1;
          for(;T;T=Math.floor(T/10))
            S=(S+T%10*(9-M++%6))%11;
          return S?S-1:'k';
        }
      }
    var corr={
      validaCorreo: function(correo_completo)
      {
        emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
        if(emailRegex.test(correo_completo))
        {
          correos=true;
          return true;
        }
        else
        {
          correos=false;
          return false;
        }
      }
    }
    $(document).ready(function()
    {
      $( '#rut' ).keyup(function()
      {
        len = this.value.length
        if(len < 7)
        {
          $( "#rutError" ).html( "Ingrese un RUT válido (sin puntos, con guión)");
        }
        else if(Fn.validaRut(this.value)==true)
        {
          $( "#rutError" ).html("");
          ruts=true;
        }
        else
        {
          $( "#rutError" ).html( "Ingrese un RUT válido (sin puntos, con guión)");
        }
      })
    });
    $(document).ready(function()
    {
      $( '#email' ).keyup(function()
      {
        len = this.value.length
        if(corr.validaCorreo(this.value)==true)
        {
          $( "#correoError" ).html("");
        }
        else
        {
          $( "#correoError" ).html( "Ingrese un Correo válido");
        }
      })
    });
    $(document).ready(function()
    {
      $("#alias").keyup(function()
      {
        len=this.value.length;
        //console.log(len);
        var valida_alias=/^[a-z0-9_-]{5,16}$/;
        //console.log(isNaN(this.value));
        if(len<5 || !isNaN(this.value))
        {
          if(len<5)
          {
            $( "#aliaserror" ).html("Se debe Ingresar 5 carateres o mas");
          }
          if (isNaN(this.value))
          {
            $( "#aliaserror" ).html("Se debe Ingresar carateres y numeros");
          }
          else
          {
            $( "#aliaserror" ).html("");
            alias=true;
          }
        }
        else
        {
          $( "#aliaserror" ).html("");
          alias=true;
        }
      })
    });
    $(document).ready(function()
    {
      $("#nombre").blur(function()
      {
        //len=this.value.length;
        //console.log($("#nombre").val());
        if($("#nombre").val()=="")
        {
          $( "#nombreerror" ).html("Debe ingresar su nombre");
        }
        else
        {
          $( "#nombreerror" ).html("");
        }
      })
    });
    </script>
    <div role="main" class="content">
      <div class="container-fluid">
        <form action="index.php" method="POST" class="form-horizontal" enctype="multipart/form-data" id="prueba">
          <div class="row justify-content-center mt-3">
        <div class="col-md-10">
          
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Formulario de votacion</h3>
            </div>
           
            <!-- /.card-header -->
            <div class="card-body" >
              <div class="form-group row">
                <label for="inputName" class="col-sm-2 col-form-label">Nombre y Apellido:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Escriba su Nombre y Apellido">
                  <small id="nombreerror" class="form-text text-muted"></small>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputRut" class="col-sm-2 col-form-label">Alias:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="alias" id="alias" placeholder="Escriba su Alias" maxlength="10">
                  <small id="aliaserror" class="form-text text-muted"></small>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputRut" class="col-sm-2 col-form-label">RUT:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="rut" id="rut" placeholder="Escriba su RUT" maxlength="10">
                  <small id="rutError" class="form-text text-muted"></small>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputEmail" class="col-sm-2 col-form-label">Correo:</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Escriba su correo">
                  <small id="correoError" class="form-text text-muted"></small>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputTel" class="col-sm-2 col-form-label">Region:</label>
                <div class="col-sm-10">
                  <select class="form-select col-sm-10" id="regiones" name="regiones">
                  </select>
                </div>
              </div>   
              <div class="form-group row">
                <label for="inputTel" class="col-sm-2 col-form-label">Comuna:</label>
                <div class="col-sm-10">
                  <select class="form-select col-sm-10" id="comunas" name="comunas">
                  </select>
                </div>
              </div> 
              <div class="form-group row">
                <label for="inputTel" class="col-sm-2 col-form-label">Candidato:</label>
                <div class="col-sm-10">
                  <select class="form-select col-sm-10" aria-label="Default select example" name="candidato">
                    <option selected>Selecione Candidato</option>
                    <?php
                    for ($i=0;$i<count($cadidatos);$i++)
                    {
                      echo '<option value="'.$cadidatos[$i]["id"].'">'.$cadidatos[$i]["tipo"].'</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>   
            </div>
            <div class="col-md-12">
              <div class="form-group">                        
                <label>¿Como se entero de nosotros?</label>
                <div class="row">
                  <div class="col-md-2 col-sm-0">
                  </div>
                  <div class="col-md-10" id="relaciones">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="web" value="1">
                      <label class="form-check-label">Web</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="tv" value="1">
                      <label class="form-check-label">Tv</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="rd" value="1" >
                      <label class="form-check-label">Redes sociales</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="amigo" value="1">
                      <label class="form-check-label">Amigo</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col-md-2">  
                  <button type="summit" class="btn btn-primary float-right" id="cargar" name="cargar" value="true" disabled>
                    <!--<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" id="spiner_cargar" disabled ></span>-->
                    Enviar
                  </button>
                  <!--<a class="btn btn-primary float-right" id="nocargar" data-toggle="modal" data-target="#mensaje" >Enviar</a>-->
                </div>
              </div>
            </div>
          </div>
        </div>
        </form>
      </div>
    </div>
    <?php
    //include_once("/conf/config.php");
    //var_dump($_POST);
    if ($_POST)
    {
      //["nombre"]=> string(6) "asdasd" ["alias"]=> string(7) "asdasd1" ["rut"]=> string(10) "27112928-9" ["email"]=> string(23) "kinlonesdimas@gmail.com" ["candidato"]=> string(1) "1" ["web"]=> string(3) "Web" ["tv"]=> string(2) "Tv" ["rd"]=> string(4) "Otro" ["amigo"]=> string(4) "Otro"
      $query="INSERT INTO candidatos_ingresados (nombre, alias,rut,correo,candidato_tipo,web,tv,rd,amigo,region,comuna) VALUES ('".$_POST['nombre']."', '".$_POST['alias']."', '".$_POST['rut']."','".$_POST['email']."','".$_POST['candidato']."','".$_POST['web']."','".$_POST['tv']."','".$_POST['rd']."','".$_POST['amigo']."','".$_POST['regiones']."','".$_POST['comunas']."')";
      //var_dump($query);
      $query = mysql_query($query);
      if (!$query)
      {
          die('Consulta no válida: ' . mysql_error());
      }
      else
      {
      ?>
        <script type="text/javascript">
          alert('Se ha ingresaco correctamente al candidato');
        </script>
      <?php
      //$_GET['estado']="Carga de candidato correcta";
      }
    }
    //die();
    ?>
    <script type="text/javascript">
      
      $("#prueba").change(function ()
      {
        if($("#nombre").val()!="" && alias && ruts && correos && ($("#comunas").val()!="Seleccione comuna" || $("#comunas").val()!="--") && ($("#regiones").val()!="Seleccione región" || $("#regiones").val()!="--") && ($("#candidato").val()!="" && $("#candidato").val()!="Selecione Candidato"))
        {
          $('#cargar').removeAttr('disabled');
        }
        else
        {
          $('#cargar').attr('disabled','disabled');
        }
      });
    </script>
    
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>