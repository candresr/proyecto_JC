
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Favicons -->
    <link rel="shortcut icon" href="img/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="57x57" href="<?=base_url()?>assets/img/favicon/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=base_url()?>assets/img/favicon/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=base_url()?>assets/img/favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?=base_url()?>assets/img/favicon/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?=base_url()?>assets/img/favicon/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?=base_url()?>assets/img/favicon/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?=base_url()?>assets/img/favicon/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?=base_url()?>assets/img/favicon/apple-touch-icon-152x152.png">
    <link rel="icon" type="image/png" href="<?=base_url()?>assets/img/favicon/favicon-196x196.png" sizes="196x196">
    <link rel="icon" type="image/png" href="<?=base_url()?>assets/img/favicon/favicon-160x160.png" sizes="160x160">
    <link rel="icon" type="image/png" href="<?=base_url()?>assets/img/favicon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="<?=base_url()?>assets/img/favicon/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="<?=base_url()?>assets/img/favicon/favicon-32x32.png" sizes="32x32">
    <meta name="msapplication-TileColor" content="#ed1e79">
    <meta name="msapplication-TileImage" content="<?=base_url()?>assets/img/favicon/mstile-144x144.png">
    <meta name="msapplication-config" content="<?=base_url()?>assets/img/favicon/browserconfig.xml">

    <title>Avila TV</title>
    
    <!-- Styles -->
    <link href="<?=base_url()?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/fonts.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/slick.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/lightbox.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/atv-styles.css" rel="stylesheet">
	
  </head>

  <body class="<?=$css?>">
<!-- HEADER
================================================== -->
    <!-- Logo -->
    <div class="container" id="top-header">
      <div class="row">
        <div class="col-xs-3">
          <div class="row">
            <img src="<?=base_url()?>assets/img/header.svg" alt="header" id="logo-vector">
          </div>
        </div>
        <div id="social-sm" class="hidden-xs">
            <a href="https://twitter.com/AvilatvSuena" class="bt-tw" target="_blank"></a>
          <a href="https://www.facebook.com/avilatv.suena" class="bt-fb" target="_blank"></a>
          <a href="https://www.youtube.com/channel/UCm4PdsJ1uxEGLYsUVNZswww" class="bt-yt" target="_blank"></a>
        </div>
        <div class="col-xs-9">
          <div id="toggle-menu">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
        </div>
        <div id="social-xs" class="visible-xs">
          <a href="https://twitter.com/AvilatvSuena" class="bt-tw"></a>
          <a href="https://www.facebook.com/avilatv.suena" class="bt-fb"></a>
          <a href="https://www.youtube.com/channel/UCm4PdsJ1uxEGLYsUVNZswww" class="bt-yt"></a>
        </div>
      </div>
    </div>
  
    <!-- Menu -->
    <div class="container" id="menu">
      <div class="row">
        <div class="navbar navbar-collapse collapse" role="navigation">
          <ul class="nav nav-justified">
            <?=$menu?>
            
          </ul>
        </div>
      </div>
    </div>

<!-- CONTENT
================================================== -->
 <?=$content?>
    
<!-- FOOTER
================================================== -->
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-xs-4">
            <img src="<?=base_url()?>assets/img/gobierno_footer.png" class="img-responsive">
          </div>
        </div>
      </div>
    </footer>


<!-- JS
================================================== -->
    <script src="<?=base_url()?>assets/js/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>assets/js/slick.min.js"></script>
    <script src="<?=base_url()?>assets/js/lightbox.min.js"></script>
    <script src="<?=base_url()?>assets/js/main.js"></script>
    <script src="<?=base_url()?>assets/js/repro.js"></script>
  </body>
</html>
