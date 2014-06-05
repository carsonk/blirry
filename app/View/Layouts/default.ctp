<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'blirry');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<link href="http://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet" type="text/css">

	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('bootstrap');
		echo $this->Html->css('bootstrap-theme');
		echo $this->Html->css('global');

		echo $this->Html->script('jquery');
		echo $this->Html->script('bootstrap');

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
