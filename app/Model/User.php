<?php

App::uses('AppModel', 'model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
  public $validate = array(
    'username' => array(
      'required' => array(
        'rule' => array('notEmpty'),
        'message' => 'A username is required.'
      ),
      'unique' => array(
        'rule' => 'isUnique',
        'required' => 'create',
        'last' => 'true',
        'message' => 'Username must be unique.'
      )
    ),

    'password' => array(
      'required' => array(
        'rule' => array('notEmpty'),
        'message' => 'A password is required.'
      )
    ),
    
    'email' => array(
      'required' => array(
        'rule' => array('notEmpty'),
        'message' => 'An email is required.'
      ),
      'validEmail' => array(
        'rule' => array('email'),
        'message' => 'Invalid email format.'
      ),
      'unique' => array(
        'rule' => 'isUnique',
        'required' => 'create',
        'last' => 'true',
        'message' => 'Email must be unique.'
      )
    )
  );

  public function beforeSave($options = array()) {
    // Encrypts password.
    if(isset($this->data[$this->alias]['password'])) {
      $passwordHasher = new SimplePasswordHasher();
      $this->data[$this->alias]['password'] = $passwordHasher->hash(
        $this->data[$this->alias]['password']
      );
    }
    return true;
  }
}