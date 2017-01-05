
</div> <!-- end row from menu -->
</div> <!-- end container from menu -->
<br />
<div style="clear:both"></div>
<br />
	<footer class="newstitle">
		ShadownewsNet 2.0 &copy; 2015 by CyberDude
	</footer>


    <script type="text/javascript" src="/secure/snn/assets/js/jquery.tooltipsy.js"></script>
	<script type="text/javascript" src="/secure/snn/assets/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript" src="/secure/snn/assets/js/functions.js"></script>
	<script type="text/javascript" src="/secure/snn/assets/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="/secure/snn/assets/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/secure/snn/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/secure/snn/assets/js/messages.js"></script> 


	<link href="/secure/snn/assets/js/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" media="screen">
	<?php if(($this->uri->segment(2) == 'marketplace') || ($this->uri->segment(2) == 'clinic')): ?>
        <link href="/secure/snn/assets/css/shop.css" rel="stylesheet">        
        <script type="text/javascript" src="/secure/snn/assets/js/shop.js"></script>        
    <?php endif;?>
    
    <?php if($this->uri->segment(1) == 'combatzone'): ?>
    	<script type="text/javascript" src="/secure/snn/assets/js/combat.js"></script>    
    <?php endif;?>
    <?php if($this->uri->segment(1) == 'admin'): ?>
    	<script type="text/javascript" src="/secure/snn/assets/js/trix.js"></script>    
    <?php endif;?>

    <script type="text/javascript">
    $(document).ready(function () {
        var url = window.location;
        var path = url.pathname;
        if (path.match(/combatzone/g)) {
            $('#combatzone').parent().addClass('active');
        } else{
            $('ul.nav a[href="'+ path +'"]').parent().addClass('active');
        }
        

        $('ul.nav a').filter(function() {
             return this.href == url;
        }).parent().addClass('active');
    });
</script> 
</body>
</html>