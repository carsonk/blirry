<br />

<div class="panel panel-default users form" style="width: 50%; margin: 0 auto;">
	<div class="panel-heading">Sign up</div>
	<div class="panel-body">
    <?php 
      echo $this->Session->flash('flash', array(
        'params' => array('class' => 'alert alert-danger')
      ));
    ?>

		<?php echo $this->Form->create('User'); ?>
	    <?php 
		    echo $this->Form->input('username', array(
		    	'class' => 'form-control',
		    	'placeholder' => 'Pick a unique username',
		    	'div' => 'form-group'
				));
				echo $this->Form->input('email', array(
		    	'class' => 'form-control',
		    	'placeholder' => 'Enter email',
		    	'div' => 'form-group'
				));
		    echo $this->Form->input('password', array(
		    	'class' => 'form-control',
		    	'placeholder' => 'Enter a good password',
		    	'div' => 'form-group'
				));
				echo $this->Form->input('confirm-password', array(
		    	'class' => 'form-control',
		    	'placeholder' => 'Enter your password again, just to make sure you got it',
		    	'div' => 'form-group',
		    	'label' => 'Confirm Password',
          'type' => 'password'
				));
	    ?>
			<button type="submit" class="btn btn-default">Register!</button>
		<?php echo $this->Form->end(); ?>
	</div>
</div>