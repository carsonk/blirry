<?php
$globalTitle = 'blirry';
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $globalTitle; ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<link href="http://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet" type="text/css">

  <script type="text/javascript">
    var root = "<?php echo $this->Html->url('/',true); ?>";
  </script>

	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('bootstrap');
		echo $this->Html->css('bootstrap-theme');
		echo $this->Html->css('global');

		echo $this->Html->script('jquery');
    echo $this->Html->script('jquery-ui');
		echo $this->Html->script('bootstrap');
    echo $this->Html->script('app');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('scripts');
	?>
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    	<div class="container">
    		<div class="navbar-header">
      			<a class="navbar-brand" href="#">blirry</a>
    		</div>
        	<div class="collapse navbar-collapse">
          		<ul class="nav navbar-nav">
	            	<li><a href="#">Quizzes</a></li>
	            	<li><a href="#">Surveys</a></li>
	            	<li><a href="#">Create</a></li>
	          	</ul>

        			<?php
        				$loginUrl = $this->Html->url(array(
        					"controller" => "users",
        					"action" => "login"
      					));
        			?>
        			<?php if(!$authUser): ?>
	        			<a href="<?php echo $loginUrl; ?>" class="btn btn-primary navbar-btn navbar-right">
	        					<span class="glyphicon glyphicon-user"></span> Login
	      				</a>
	      			<?php else: ?>
	      				<ul class="nav navbar-nav navbar-right">
	      					<li class="dropdown">
		      					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
	      							<?php echo $authUser["username"]; ?>
	      							<b class="caret"></b>
	    							</a>
		      					<ul class="dropdown-menu">
		      						<li><a href="#">Profile</a></li>
		      						<li><a href="#">Preferences</a></li>
	      						</ul>
      						</li>
    						</div>
      				<?php endif; ?>
        	</div><!--/.nav-collapse -->
      	</div>
    </nav>
    
    <div class="container content-container">
    	<?php echo $this->fetch('content'); ?>
    </div>
</body>
</html>
