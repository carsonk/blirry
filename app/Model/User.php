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
        'last' => 'true',
        'message' => 'Username must be unique.'
      ),
      'length' => array(
        'rule' => array('between', 3, 20),
        'message' => 'Username must be between 3 and 20 characters long.'
      )/*,
      'characters' => array(
        'rule' => 'alphaNumeric',
        'message' => 'Username must be alphanumberic.'
      )*/
    ),

    'password' => array(
      'required' => array(
        'rule' => array('notEmpty'),
        'message' => 'A password is required.'
      ),
      'length' => array(
        'rule' => array('minLength', 3)
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
        'last' => 'true',
        'message' => 'Email must be unique.'
      )
    )

  );

  public $hasMany = array(
    'Quiz' => array(
      'className' => 'Quiz',
      'foreignKey' => 'user_id'
    )
  );

  public $displayField = 'username';

  private $publicFields = array('id' => 1,'username' => 1);

  public function beforeSave($options = array()) {
    parent::beforeSave();

    // Encrypts password.
    if(isset($this->data[$this->alias]['password'])) {
      $passwordHasher = new SimplePasswordHasher();
      $this->data[$this->alias]['password'] = $passwordHasher->hash(
        $this->data[$this->alias]['password']
      );
    }
    return true;
  }

  public function beforeFind($query) {
    return $query;
  }

  public function afterFind($results, $primary = false) {
    return $results;
  }
}