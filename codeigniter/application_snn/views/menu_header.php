
<?php 
  if ($this->uri->segment(1) == 'combatzone'){
    $class = 'combatzone'; 
  } else {
    if ($this->uri->segment(2) == 'messages') {
      $class = 'messages';
    } else if ($this->uri->segment(2) == 'einstellungen') {
      $class = 'einstellungen';
    } else {
      $class = 'desktop';
    }
  }

?>

<style>
.dropdown-menu {
  background-color: #222222;
  color: #9D9D9D;
}
.dropdown-menu > li > a {
 color: #9D9D9D; 
  padding:5px; 
}
.dropdown-menu > li > a:hover {
  color: #ffffff; 
  background-color: #222222;  
}
.dropdown-menu > li > a:focus, a:active {
  color: #ffffff; 
  background-color: #000000;  
}
.dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus {
    background-color: #000000;
    color: #fff;
    outline: 0 none;
    text-decoration: none;
}
.alert-info {
    color: #31708F;
    background-color: #D9EDF7;
    border-color: #BCE8F1;
}
</style>
<script>
$(document).ready(function($) {
    var stickyHeaderTop = $('nav').offset().top;
    $(window).scroll(function(){ /*header-menu-wrap*/
        if( $(window).scrollTop() > stickyHeaderTop ) {
        $('nav').addClass("navbar-fixed-top");
        } else {
        $('nav').removeClass("navbar-fixed-top");
        }
    });
}); 
</script>
<body class="<?=$class;?>">
<script type="text/javascript" src="/secure/snn/assets/js/wz_tooltip.js"></script>	

    <div id="top-header">
        <div class="container text-center">
          <img src="/secure/snn/assets/img/layout/snn.png"/>
        </div>
    </div>

        <!-- Main Navigation/Menu
    ====================================-->   

<nav class="navbar navbar-inverse navbar-custom">
	
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <span class="navbar-brand" href="#">Willkommen zurück <?=ucfirst($this->session->userdata('nickname')); ?></span>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav" role="menu">
            <li><a href="/secure/snn/desktop/overview"><i class="fa fa-home"></i>&nbsp;[Home]</a></li>
		<?php if($this->session->userdata('rank') <= '3'): ?>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-trophy"></i>&nbsp;[Combatzone]</a>
                <ul class="dropdown-menu" role="menu">                  
                  <li><a href="/secure/snn/combatzone/inventory"><i class="fa fa-suitcase"></i>&nbsp;[Charakterbogen]</a></li>
                  <li><a href="/secure/snn/combatzone/marketplace"><i class="fa fa-credit-card"></i>&nbsp;[Marktplatz]</a></li>
                  <li><a href="/secure/snn/combatzone"><i class="fa fa-heartbeat"></i>&nbsp;[Missionen]</a></li>
                  <li><a href="/secure/snn/combatzone/clinic"><i class="fa fa-ambulance"></i>&nbsp;[Schattenklinik]</a></li>
                </ul>
            </li>
            
         <?php endif; ?>
<?php /*     
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="combatzone"><i class="fa fa-trophy"></i>&nbsp;[Combatzone]<b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="/secure/snn/combatzone"><i class="fa fa-heartbeat"></i>&nbsp;[Missionen]</a></li>
          <li><a href="/secure/snn/combatzone/inventory"><i class="fa fa-suitcase"></i>&nbsp;[Inventar]</a></li>          
          <li><a href="/secure/snn/combatzone/marketplace"><i class="fa fa-credit-card"></i>&nbsp;[Marktplatz]</a></li>
          <li><a href="/secure/snn/combatzone/clinic"><i class="fa fa-ambulance"></i>&nbsp;[Schattenklinik]</a></li>          
        </ul>
      </li>
*/ ?>
            <li><a href="/secure/snn/desktop/einstellungen"><i class="fa fa-cogs"></i>&nbsp;[Einstellungen]</a></li>
            <li><a href="/secure/snn/desktop/messages"><i class="fa fa-newspaper-o"></i>&nbsp;[Nachrichten<span id="newMessagesHeader"></span>]</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-bar-chart"></i>&nbsp;[Betatest]</a>
                <ul class="dropdown-menu" role="menu">            
                	<li><a href="/secure/snn/desktop/feedback"><i class="fa fa-street-view"></i>&nbsp;[Feedback]</a></li>
                	<li><a href="/secure/snn/desktop/features"><i class="fa fa-bolt"></i>&nbsp;[new Features/ Fixes]</a></li>
                </ul>
            </li>            
            
            <?php if($this->session->userdata('rank') <= '2'): ?>
              <li><a href="/secure/snn/admin"><i class="fa fa-university"></i>&nbsp;[Admin]</a></li>
            <?php endif; ?>               
        </ul>
    		<ul class="nav navbar-nav navbar-right">
      		<li><a href="/secure/snn/desktop/logout"><i class="fa fa-sign-out"></i>&nbsp;[Logout]</a></li>
    		</ul>                
        </div>
      </div>
  </nav>
  <div class="container-fluid"><!-- Container from menu -->
    <div class="row"><!-- row from menu -->
	<?php if(!empty($systemnews[0])): ?>
		<div class="col-sm-3">&nbsp;</div>
		<div class="alert alert-info col-sm-6">
			<h3>Systemnachricht!</h3>
			<strong><?=$systemnews[0]['title']?></strong><br />
			<?=$systemnews[0]['text']?>
		</div>
		<div class="col-sm-3">&nbsp;</div>
		<div style="clear:both"></div>
	<?php endif;?>


