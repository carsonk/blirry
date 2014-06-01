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
	?>
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    	<div class="container">
    		<div class="navbar-header">
      			<a class="navbar-brand" href="#">blirry</a>
    		</div>
        	<div class="collapse navbar-collapse">
          		<ul class="nav navbar-nav navbar-right">
	            	<li class="active"><a href="#">Home</a></li>
	            	<li><a href="#about">About</a></li>
	          	</ul>
        	</div><!--/.nav-collapse -->
      	</div>
    </nav>

    <div class="container">
    	<?php echo $this->fetch('content'); ?>
    </div><!-- /.container -->
</body>
</html>
