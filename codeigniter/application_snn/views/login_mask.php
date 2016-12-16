<body class="desktop" style="color:#FFFFFF">
<div class="container" onload="$('#username').focus()">
	
        <div class="col-md-12">
        	<?=form_open('/login', "class='form-horizontal'");?>
        	<?=form_hidden('login', 'true');?>
			<fieldset  style="border-color: #FFFFFF;width:350px">

			<!-- Form Name -->
			<legend style="color: #FFFFFF">Login</legend>
			<!-- Text input-->
				<?php if($flashmsg): ?>
					<div class="errormsg" style="width:300px"><?=$flashmsg?></div>
					<br />
				<?php endif; ?>			
			<div class="control-group">
				<div class="controls">
			  		<div class="input-group margin-bottom-sm" style="width: 300px">
						<span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
						<?=form_input(array('id' => 'username', 'name' =>'username', 'placeholder'=>'Username', "class" => "form-control",  "autofocus" => true));?>
					</div>
			  	</div>
			</div>

			<!-- Password input-->
			<div class="control-group">
				<div class="controls">
			  		<div class="input-group" style="width: 300px">
						<span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
						<?=form_password(array('id' => 'password', 'name' =>'password', 'placeholder'=>'Password', "class" => "form-control"));?>
					</div>
			  	</div>
			</div>
				<br />
			<!-- Button -->
			<div class="control-group">
			  <div class="controls">
			  	<?=form_submit(array('id'=>'submit', 'value' => 'sign in', 'name' => 'submit', 'class' => 'btn btn-primary'));?>
			  </div>
			</div>

			</fieldset>
			<?=form_close();?>
		</div>

</div>
</body>
</html>