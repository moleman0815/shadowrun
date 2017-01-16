<style>
.adminpanel {
    background-color: #0A83BF;
    border: 1px solid black;
    border-radius: 3px;
    color: #000000;
    cursor: pointer;
    display: inline-block;
    font-size: 16px;
    line-height: 30px;
    margin: 4px;
    padding: 8px;
  	box-shadow: 3px 3px 5px #000000;
}
</style>
<script>
	$( document ).ready(function() {
		$( "#tabs" ).tabs();
		var last_index = $.cookie("lasttab");
	    $("#tabs").tabs( "option", "active", last_index );
	});

	$(function() {
		$("li").click(function() {
		    var current_index = $("#tabs").tabs("option","active");
		    $.cookie("lasttab", current_index);
		});
	});	

	
	
</script>


	<div class="newstitle">Admininterface</div>
	<br />
	<div id="tabs">
		<ul>
			<li><a href="#gegner">Gegner</a></li>
		  	<li><a href="#missionen">Missionen</a></li>
		  	<li><a href="#news">Nachrichten</a></li>			
		  	<li><a href="#ads">Werbung</a></li>
		  	<li><a href="#items">Gegenstände</a></li>		  			  	
		</ul>
		<div id="gegner">
			<fieldset>
				<legend class="newstitle">Gegner</legend>
				<a href="/secure/snn/admin/generateGanger"><div class="btn adminpanel"><i class="fa fa-user-plus"></i>&nbsp;Gegner erstellen</div></a>
				<a href="/secure/snn/admin/gangerVerwalten"><div class="btn adminpanel"><i class="fa fa-user"></i>&nbsp;Gegner verwalten</div></a>		
				<a href="/secure/snn/admin/generateUpload/ganger"><div class="btn adminpanel"><i class="fa fa-picture-o"></i>&nbsp;Portrait hochladen</div></a>
				<a href="/secure/snn/admin/uploadVerwalten/ganger"><div class="btn adminpanel"><i class="fa fa-picture-o"></i>&nbsp;Portraits verwalten</div></a>
			</fieldset>
		</div>
		<div id="missionen">
			<fieldset>
				<legend class="newstitle">Missionen</legend>			
				<a href="/secure/snn/admin/generateMission"><div class="btn adminpanel"><i class="fa fa-folder"></i>&nbsp;Generate Mission</div></a>
				<a href="/secure/snn/admin/editMission"><div class="btn adminpanel"><i class="fa fa-folder-open"></i>&nbsp;Edit Mission</div></a>
				<br />
				<a href="/secure/snn/admin/generateUpload/missionsbanner"><div class="btn adminpanel"><i class="fa fa-flag"></i>&nbsp;Banner hochladen</div></a>					
				<a href="/secure/snn/admin/uploadVerwalten/missionsbanner"><div class="btn adminpanel"><i class="fa fa-flag-checkered"></i>&nbsp;Banner verwalten</div></a>
				<br />				
				<a href="/secure/snn/admin/generateUpload/johnson"><div class="btn adminpanel"><i class="fa fa-flag"></i>&nbsp;Johnson hochladen</div></a>					
				<a href="/secure/snn/admin/uploadVerwalten/johnson"><div class="btn adminpanel"><i class="fa fa-flag-checkered"></i>&nbsp;Johnsons verwalten</div></a>
				<br />				
				<a href="/secure/snn/admin/generateUpload/storyimage"><div class="btn adminpanel"><i class="fa fa-flag"></i>&nbsp;Storybild hochladen</div></a>					
				<a href="/secure/snn/admin/uploadVerwalten/storyimage"><div class="btn adminpanel"><i class="fa fa-flag-checkered"></i>&nbsp;Storybilder verwalten</div></a>	
				<br />				
				<a href="/secure/snn/admin/generateStoryitem"><div class="btn adminpanel"><i class="fa fa-flag"></i>&nbsp;Storyitem erstellen</div></a>					
				<a href="/secure/snn/admin/editStoryitem"><div class="btn adminpanel"><i class="fa fa-flag-checkered"></i>&nbsp;Storyitems verwalten</div></a>							
			</fieldset>
		</div>
		<div id="news">
			<fieldset>
				<legend class="newstitle">Nachrichten</legend>	
				<a href="/secure/snn/admin/newNews"><div class="btn adminpanel"><i class="fa fa-keyboard-o"></i>&nbsp;News schreiben</div></a>
				<a href="/secure/snn/admin/newsVerwalten"><div class="btn adminpanel"><i class="fa fa-laptop"></i>&nbsp;News verwalten</div></a>				
				<a href="/secure/snn/admin/insertCategory"><div class="btn adminpanel"><i class="fa fa-server"></i>&nbsp;Neue Kategorie</div></a>	
				<a href="/secure/snn/admin/categoryVerwalten"><div class="btn adminpanel"><i class="fa fa-tasks"></i>&nbsp;Kategorie verwalten</div></a>				
			</fieldset>
		</div>
		<div id="ads">
			<fieldset>
				<legend class="newstitle">Werbung</legend>	
				<a href="/secure/snn/admin/newAds"><div class="btn adminpanel"><i class="fa fa-bitbucket"></i>&nbsp;Werbung erstellen</div></a>	
				<a href="/secure/snn/admin/adsVerwalten"><div class="btn adminpanel"><i class="fa fa-bitbucket-square"></i>&nbsp;Werbung verwalten</div></a>				
			</fieldset>
		</div>
		<div id="items">
			<fieldset>
				<legend class="newstitle">Gegenstände</legend>	
				<a href="/secure/snn/admin/insertItem"><div class="btn adminpanel"><i class="fa fa-motorcycle"></i>&nbsp;Neuer Gegenstand</div></a>	
				<a href="/secure/snn/admin/itemsVerwalten"><div class="btn adminpanel"><i class="fa fa-plane"></i>&nbsp;Gegenstände verwalten</div></a>
				<a href="/secure/snn/admin/itemsImport"><div class="btn adminpanel"><i class="fa fa-plane"></i>&nbsp;Gegenstände importieren</div></a>				
			</fieldset>
		</div>				
	</div>	
	<br />	
	<br /> &nbsp;
	<?php if($this->session->userdata('rank') == '1'): ?>
		<hr />
		<br />
		<a href="/secure/snn/admin/newUser"><button class="btn btn-warning btn-lb">Neuer User</button></a>	
		<a href="/secure/snn/admin/userVerwalten"><button class="btn btn-warning btn-lb">User verwalten</button></a>	
		<a href="/secure/snn/admin/lastOnline"><button class="btn btn-warning btn-lb">Last online</button></a>			
		<br />	&nbsp;	
	<?php endif; ?>
