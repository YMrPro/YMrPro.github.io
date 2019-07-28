<?php error_reporting(0);
?>
<head>
 <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <meta charset="utf-8" />
    <title>[#ExplicitPremiunGuve]#Mitou</title>
    <link rel="icon" href="path/images/favicon.ico" type="image/x-icon" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="path/js/custom.js"></script>
    <script src="path/js/priv8explicitchk.js"></script>
    <link href="path/css/bootstrap.css" rel='stylesheet' type='text/css'>
    <link href="path/css/animate.css" rel='stylesheet' type='text/css'>
    <link href='path/css/explicitchk.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="path/css/font-awesome.min.css">
</head>

    <div class="animated bounceInDown" id="formulario">
        <table class="display" id="example">
            <center>
                <span style="font-size:20px;"><img src="path/images/logo.png"></img></span>
                <textarea name="ccs" id="ccs" placeholder="4984069124851970|08|2020|980" class="form-control" style="max-width: 800px; min-width: 800px; min-height: 200px; max-height: 200px; text-align: center;" placeholder=""></textarea>
                <br>
                <center>
                    <span>
                  Informações: 
                  <i id="demo">
                     <div class="label label-warning label-dismissible">Não iniciado.</div>
                  </i>
                   Carregadas: 
                  <div id="carregada" class="label label-warning label-dismissible">0</div>
                  </i>  Testadas: 
                  <div id="testado" class="label label-warning label-dismissible">0</div>
                   Aprovadas: 
                  <div id="CLIVE" class="label label-warning label-dismissible">0</div>
                   Reprovadas: 
                  <div id="CDIE" class="label label-warning label-dismissible">0</div>
               </span>
                </center>
    </div>
    <br>
    <div class="form-inline">
        <button type="submit" name="testar" value="Iniciar " onclick="start()" class="fcbtn btn btn-warning btn-outline btn-1e btn-squared">Iniciar</button>
        <input value="|" type="text" maxlength="1" style="height: 35px; width: 60px; text-align: center;" class="form-control" name="separador" id="separador" placeholder="|">
        <button type="submit" name="limpar" value="Limpar" onclick="limpar()" class="fcbtn btn btn-info btn-warning btn-1e btn-squared animated ">Limpar</button>
         </table>
    </div>
    </div>
    <br>
    <center>
        <div class="col-md-6 animated bounceInLeft">
            <div class="panel panel-filled">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <a class="panel"><font color="#1ABE88"><i class="fa fa-check" aria-hidden="true"></i></font></a>
						
                    </div>
                    Aprovadas
                </div>
                <div class="panel-body">
                    <p>As suas infos <code>testadas</code> e <code>aprovadas</code> irão aparecer aqui!</p>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th><font size="2">#</font>
                                    </th>
                                    <th><font size="2">Status</font>
                                    </th>
                                    <th><font size="2">Número</font>
                                    </th>
                                    <th><font size="2">Mês</font>
                                    </th>
                                    <th><font size="2">Ano</font>
                                    </th>
                                    <th><font size="2">Cvv</font>
                                    </th>
                                    <th><font size="2">Valor</font>
                                    </th>
                                    <th><font size="2">Informações</font>
                                    </th>
                                </tr>
                            </thead>
                            <tbody name="aprovadas" id="aprovadas">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 animated bounceInRight">
            <div class="panel panel-filled">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <a class="panel-toggle"><font color="#DA514A"><i class="fa fa-close"></i></font></a>
                    </div>
                    Reprovadas
                </div>
                <div class="panel-body">
                    <p>As suas infos <code>testadas</code> e <code>reprovadas</code> irão aparecer aqui!</p>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th><font size="2">#</font>
                                    </th>
                                    <th><font size="2">Status</font>
                                    </th>
                                    <th><font size="2">Número</font>
                                    </th>
                                    <th><font size="2">Mês</font>
                                    </th>
                                    <th><font size="2">Ano</font>
                                    </th>
                                    <th><font size="2">Cvv</font>
                                    </th>
                                    <th><font size="2">Valor</font>
                                    </th>
                                    <th><font size="2">Informações</font>
                                    </th>
                                </tr>
                            </thead>
                            <tbody name="reprovadas" id="reprovadas">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

  <a>Guve</a> <a class="glyphicon glyphicon-sunglasses" color="#FFF"></a><br>
  <a>Shawn</a> <a class="glyphicon glyphicon-knight"></a><br>
  <footer>Edição Premium ExplicitChecker </footer><footer class="glyphicon glyphicon-globe"></footer>
        </html>