<br />

<div class="panel panel-default users form" style="width: 50%; margin: 0 auto;">
  <div class="panel-heading">Login</div>
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
          'placeholder' => 'Username',
          'div' => 'form-group'
        ));
        echo $this->Form->input('password', array(
          'class' => 'form-control',
          'placeholder' => 'Password',
          'div' => 'form-group'
        ));
      ?>
      <button type="submit" class="btn btn-default">Login</button>
    <?php echo $this->Form->end(); ?>
  </div>
</div>