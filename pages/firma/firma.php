<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Firma consenso</title>
  <meta name="description" content="Apposizione Firma - Vestiario">

  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
	
  <script src="../../plugins/jquery/jquery.min.js"></script>
  <?php 
	//include("lib_js_ext.php"); 
 ?> 
  <link rel="stylesheet" href="css/signature-pad.css">
 

  <!--[if IE]>
    <link rel="stylesheet" type="text/css" href="css/ie9.css">
  <![endif]-->

<!-- Ref. https://github.com/szimek/signature_pad !-->

</head>
<body onselectstart="return false">


  <div id="signature-pad" class="signature-pad">
    <div class="signature-pad--body">
      <canvas></canvas>
    </div>
    <div class="signature-pad--footer">
      <div class="description">Opzioni</div>

      <div class="signature-pad--actions">
        <div>
          <button type="button" class="button clear" data-action="clear">Cancella</button>
          <button type="button" class="button" data-action="change-color">Cambia colore</button>
          <button type="button" class="button" data-action="undo">Annulla</button>

        </div>
        <div>
          <button type="button" class="button save" data-action="save-png">Deposita firma</button>
		  <!--
			<button type="button" class="button save" data-action="save-jpg">Salva come JPG</button>
			<button type="button" class="button save" data-action="save-svg">Salva come SVG</button>
		  !-->
        </div>
      </div>
    </div>
  </div>
  <input type='hidden' id='chi' value="<?php echo $_GET['chi']; ?>">	
  <script src="js/signature_pad.umd.js?ver=1.3"></script>
  <script src="js/app.js"></script>
</body>
</html>
