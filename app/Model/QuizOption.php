<?php

App::uses('AppModel', 'Model');

class QuizOption extends AppModel {
  public $actsAs = array('Containable');

  public $belongsTo = array(
    'Question' => array(
      'className' => 'QuizQuestion',
      'foreignKey' => 'quiz_question_id'
    )
  );

  public $hasMany = array(
    'Trait' => array(
      'className' => 'QuizOptionTrait',
      'foreignKey' => 'quiz_option_id'
    )
  );
}