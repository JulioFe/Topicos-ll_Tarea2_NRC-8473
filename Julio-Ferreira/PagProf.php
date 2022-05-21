<!DOCTYPE html>
<html>                                                                                                    
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/estilo.css">
	<title>PAGINA WEB </title>
</head>
<body>
<header>
    <div id="header-inner"> <!-- ENCABEZADO -->
      <a href="#" id="logo"> <!-- # ENLACE A MARCADORES  -->
        <img src="img/pics/logo_seleccion.png" alt="">
      </a>

      <nav> <!-- # SECCION DE NAVEGACION  -->
        <a href="#" id="menu-icon"> <!-- # ENLACE AL INICIO  -->
          <i class="fa fa-bars"></i>
        </a>
        <ul>
          <li>
            <a href="PagProf.php" class="current">
              <!-- # ENLACE A LA PAGINA PRINCIPAL  -->
              Inicio
            </a>
          </li>
        </ul>
      </nav>
    </div>
</header>
  <?php
    //Se inicia sesion en php y se inicializa el array de vendedores en caso de que no exista
    session_start();
    if (!isset($_SESSION["vendedor"])){
      $_SESSION["vendedor"] = array();
    }
    //En caso de que se inserte vendedor 
    if (isset($_POST["insertar"])){
      //Se almacenan las variables enviadas por POST
      $rut= $_POST["Rut"];
      $nom= $_POST['Nom'];
      $cant_fornite= $_POST['Forn'];
      $cant_call= $_POST['Call'];
      $cant_mine= $_POST['Mine'];

      //Validacion de todos los campos con sus respectivas restricciones 
      if(empty($nom)||empty($cant_fornite)||empty($cant_call) || empty($cant_mine) || empty($rut)){
        echo '<script>alert("Rellena los valores")</script>';
      }
      else if(!is_numeric($rut) || $rut < 100000000) {
        echo '<script>alert("Ingrese rut de 9 digitos numericos")</script>';;
      }
      else if(!ctype_alpha($nom)) {
        echo '<script>alert("Nombre debe contener solo caracteres alfabeticos")</script>';;
      }
      else if(!is_numeric($cant_call) || !is_numeric($cant_fornite) || !is_numeric($cant_mine)){
        echo '<script>alert("Cantidades deben ser numericas")</script>';;
      }
      //En caso de que todos los campos esten bien se realizan los calculos para los totales y comisiones para luego guardarlos en un array 
      else{
          $cant_total = $cant_call + $cant_fornite + $cant_mine;
          $comision_fornite = ($cant_fornite * 58200) * 0.09;
          $comision_mine = ($cant_mine * 8800) * 0.04;
          $comision_call = ($cant_call * 34500) * 0.06;
          $comision_total = $comision_call + $comision_mine + $comision_fornite;
          $vendedor = array(
              "Rut" => $rut,
              "Nom" => $nom,
              "Forn" => $cant_fornite,
              "Call" => $cant_call,
              "Mine" => $cant_mine,
              "Total" => $cant_total,
              "ComiF" => $comision_fornite,
              "ComiC" => $comision_call,
              "ComiM" => $comision_mine,
              "TotalComi" => $comision_total,
          );
          //Si ya existe se muestra mensaje de modificado
          if (isset($_SESSION['vendedor'][$rut])){
            echo "<span>Se ha modificado la Persona con el rut:".$rut."</span>";
          }
          //Si no existe se muestra mensaje de creado
          else{
              echo "<span>Se ha registrado al vendedor</span>";
          }
          //El array definido anteriormente es guardado en la sesion
          $_SESSION['vendedor'][$rut]=$vendedor;
      }
    }
    //En caso de que se elimine vendedor
    else if (isset($_POST['vaciar'])){
      //Valida que si se han seleccionado personas para eliminar, en caso de que no muesstra un mensaje de que no hay personas seleccionadas
      if (!isset($_POST['ruts'])){
          echo "<span>No hay personas seleccionadas</span>";
      }
      else{
          //En caso de que si, toma los ruts de las personas seleccionadas y los elimina del array de sesion
          $ruts=$_POST['ruts'];
          foreach ($_SESSION['vendedor'] as $key => $value){
              if (in_array($key,$ruts)){
                  unset($_SESSION['vendedor'][$key]);
              }
          }
          echo "<span>Vendedor(s) Borrado(s)</span>";
      }
  }
  ?>
    <!-- Formulario para el registro de vendedor con diferentes botenes que desencadenan diferentes acciones-->
    <form method="POST" class="form_design">
      <div class="inputs">
        <span>Rut sin punto ni guion</span>
        <br><input type="text" id="Rut" name="Rut">
        <br><span>Nombre</span>
        <br><input type="text" id="Nom" name="Nom">
        <br><span>Cantidad Fornite</span>
        <br><input type="text" id="Forn" name="Forn">
        <br><span>Cantidad Call of Duty</span>
        <br><input type="text" id="Call" name="Call">
        <br><span>Cantidad Minecraft</span>
        <br><input type="text" id="Mine" name="Mine">
        <br>
        <div class="buttons">
          <button type="submit" name="insertar" class="button-6" role="button"> Insertar </button>
          <button type="submit" name="mostrar" class="button-6" role="button"> Mostrar </button>
          <button type="submit" name="vaciar" class="button-6" role="button"> Vaciar </button>
          <button type="submit" name="mostrar_top" class="button-6" role="button"> Vendedor Top </button>
        </div>
      </div>
    <?php
      //En caso de que se seleccione mostrar vendedores o mostrar al vendedor top
      if (isset($_POST['mostrar']) || isset($_POST['mostrar_top'])){
                //Valida que haya existencias de vendedores
                if ((count($_SESSION['vendedor'])===0)){
                    echo "<p> No hay Personas <p>";
                }
                else{
                    //Se en encabezado de la tabla
                    echo "<div class='div_table'>";
                    echo "<table>";
                    echo "<tr>";
                    echo "<th></th>";
                    echo "<th> Nombre Vendedor</th>";
                    echo "<th> Cantidad Ventas FOR</th>";
                    echo "<th> Cantidad Ventas COD</th>";
                    echo "<th> Cantidad Ventas MINE</th>";
                    echo "<th> Total Ventas</th>";
                    echo "<th> Comision Fornite</th>";
                    echo "<th> Comision Call of Duty</th>";
                    echo "<th> Comision Minecraft</th>";
                    echo "<th> Comision Total</th>";
                    echo "<th> Imagen juego(s) top</th>";
                    echo "</tr>";
                    //Aqui valida se seleccionamos mostrar el vendedor top
                    if (isset($_POST["mostrar_top"])){
                      //Se recupera el vendedor con mas ventas
                      $top_aux = 0;
                      $top_vendedor;
                      foreach ($_SESSION['vendedor'] as $key => $value){
                        if($value["Total"] > $top_aux){
                          $top_aux = $value["Total"];
                          $top_vendedor = $key;
                        }
                      }
                      //Se completa la tabla con los datos del vendedor top
                      ?>
                      <tr>
                        <td> <input type="checkbox" name="ruts[]" value= "<?php echo $key; ?> "> </td>
                        <td> <?php echo $_SESSION['vendedor'][$top_vendedor]["Nom"]; ?></td>
                        <td> <?php echo $_SESSION['vendedor'][$top_vendedor]["Forn"]; ?></td>
                        <td> <?php echo $_SESSION['vendedor'][$top_vendedor]["Call"]; ?></td>
                        <td> <?php echo $_SESSION['vendedor'][$top_vendedor]["Mine"]; ?></td>
                        <td> <?php echo $_SESSION['vendedor'][$top_vendedor]["Total"]; ?></td>
                        <td> <?php echo $_SESSION['vendedor'][$top_vendedor]["ComiF"]; ?></td>
                        <td> <?php echo $_SESSION['vendedor'][$top_vendedor]["ComiC"]; ?></td>
                        <td> <?php echo $_SESSION['vendedor'][$top_vendedor]["ComiM"]; ?></td>
                        <td> <?php echo $_SESSION['vendedor'][$top_vendedor]["TotalComi"]; ?></td>
                        <!-- Aqui se realiza un if para mostrar la imagen o imagenes(en caso de que se repita la mayor cantidad de ventas) del juego mas vendido-->
                        <td> <?php
                                  if((int)$_SESSION['vendedor'][$top_vendedor]["Forn"] >= (int)$_SESSION['vendedor'][$top_vendedor]["Call"] && (int)$_SESSION['vendedor'][$top_vendedor]["Forn"] >=  (int)$_SESSION['vendedor'][$top_vendedor]["Mine"]){
                                    ?>
                                    <img src='./img/games/fornite.jpg' alt='' class='img_table'>
                                    <?php
                                  }
                                  if((int)$_SESSION['vendedor'][$top_vendedor]["Call"] >= (int)$_SESSION['vendedor'][$top_vendedor]["Forn"] && (int)$_SESSION['vendedor'][$top_vendedor]["Call"] >= (int)$_SESSION['vendedor'][$top_vendedor]["Mine"]){
                                    ?>
                                    <img src='./img/games/cod.jpg' alt='' class='img_table'>
                                    <?php
                                  }
                                  if((int)$_SESSION['vendedor'][$top_vendedor]["Mine"] >= (int)$_SESSION['vendedor'][$top_vendedor]["Call"] && (int)$_SESSION['vendedor'][$top_vendedor]["Mine"] >= (int)$_SESSION['vendedor'][$top_vendedor]["Forn"]){
                                    ?>
                                    <img src='./img/games/mine.jpg' alt='' class='img_table'>
                                    <?php
                                  }
                        ?></td>
                      </tr>
                    <?php
                    }
                    //En caso de no seleccionar top, es decir, en caso de seleccionar mostrar todos los vendedores
                    else{
                        //Se recorre todos los vendedores de la sesion y se completa la tabla con todos los registros
                        foreach ($_SESSION['vendedor'] as $key => $value){
                        ?>
                            <tr>
                                <td> <input type="checkbox" name="ruts[]" value= "<?php echo $key; ?> "> </td>
                                <td> <?php echo $value['Nom']; ?></td>
                                <td> <?php echo $value['Forn']; ?></td>
                                <td> <?php echo $value['Call']; ?></td>
                                <td> <?php echo $value['Mine']; ?></td>
                                <td> <?php echo $value['Total']; ?></td>
                                <td> <?php echo $value['ComiF']; ?></td>
                                <td> <?php echo $value['ComiC']; ?></td>
                                <td> <?php echo $value['ComiM']; ?></td>
                                <td> <?php echo $value['TotalComi']; ?></td>
                                <!-- Aqui se realiza un if para mostrar la imagen o imagenes(en caso de que se repita la mayor cantidad de ventas) del juego mas vendido-->
                                <td> <?php
                                  if($value["Forn"] >= $value["Call"] && $value["Forn"] >=  $value["Mine"]){
                                    ?>
                                    <img src='./img/games/fornite.jpg' alt='' class='img_table'>
                                    <?php
                                  }
                                  if($value["Call"] >= $value["Forn"] && $value["Call"] >= $value["Mine"]){
                                    ?>
                                    <img src='./img/games/cod.jpg' alt='' class='img_table'>
                                    <?php
                                  }
                                  if($value["Mine"] >= $value["Forn"] && $value["Mine"] >= $value["Call"]){
                                    ?>
                                    <img src='./img/games/mine.jpg' alt='' class='img_table'>
                                    <?php
                                  }
                        ?></td>
                            </tr>
                            <?php
                        }
                      }
                    echo "</table>";
                    echo "</div>";
                  }
       }
            ?>
  </form>
  <div id="wrapper">


<!-- Contenedor de la informacion de los juegos donde cada juego es separado por su respectiva seccion -->
<div class="main-container">
      <section class="one-third" id="skills">
        <div class="icon-wrap">
          <img src="./img/games/cod.jpg" alt="">
        </div>
        <h3> Call of Duty </h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident praesentium voluptas, hic quia veritatis eaque ipsam. Pariatur itaque repellat dolor tempore aut recusandae, consectetur ad.</p>
      </section>
      <section class="one-third" id="skills">
        <div class="icon-wrap">
          <img src="./img/games/fornite.jpg" alt="">
        </div>
        <h3> Fornite </h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident praesentium voluptas, hic quia veritatis eaque ipsam. Pariatur itaque repellat dolor tempore aut recusandae, consectetur ad.</p>
      </section>
      <section class="one-third" id="skills">
        <div class="icon-wrap">
          <img src="./img/games/mine.jpg" alt="">
        </div>
        <h3>Minecraft</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident praesentium voluptas, hic quia veritatis eaque ipsam. Pariatur itaque repellat dolor tempore aut recusandae, consectetur ad.</p>
      </section>
    </section>
  </div>
  <!-- Footer de la pagina donde se muestran iconos de redes sociales para simular mejor el enunciado, estos links no llevan a ningun lado ya que son solo esteticos -->
  <footer>
    <div class="banner-wrapper">
        <div class="icon-text-text">
          <ul class="social">
            <li>
              <a href="#">
                <i class="fa fa-facebook"></i>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-twitter"></i>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-youtube"></i>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-instagram"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
</body>
</html>

