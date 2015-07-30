<head>  
  <title><?=NOM_SITIO?></title>
  <link rel="shortcut icon" href="<?=base_url()?>favicon.ico" />
  <script type="text/javascript" src="<?=base_url()?>assets/js/libraries/ExtJs/3.4/adapter/ext/ext-base.js"></script>
  <script type="text/javascript" src="<?=base_url()?>assets/js/libraries/ExtJs/3.4/ext-all.js"></script>
  <script type="text/javascript" src="<?=base_url()?>assets/js/libraries/ExtJs/3.4/plugins/statusbar/StatusBar.js"></script>
  <script type="text/javascript" src="<?=base_url()?>assets/js/form_validation.js"></script>
  
  
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/js/libraries/ExtJs/3.4/resources/css/ext-all-notheme.css"/>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/js/libraries/ExtJs/3.4/resources/css/yourtheme.css"/>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/style.css"/>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/js/libraries/ExtJs/3.4/plugins/statusbar/css/statusbar.css"/>

<script type="text/javascript">
    var BASE_URL 	= '<?=base_url()?>';
    var NOM_SITIO = '<?=NOM_SITIO?>';
    var LXP       = '<?=LXP?>';
    var BASE_PATH 	= '<?=base_url()?>';
    var BASE_ICONS 	= '<?=base_url()?>' + 'assets/img/icons/';
    Ext.onReady(function() {
        Ext.QuickTips.init();
        Ext.BLANK_IMAGE_URL = BASE_PATH + 'assets/js/libraries/ExtJs/3.4/resources/images/default/s.gif';
        Ext.form.Field.prototype.msgTarget = 'side';
    });
</script>
  
  
</head>
<body class="bglogin">
<? 
   $this->load->view('form_login.js.php');
?>
</body>
</html>
