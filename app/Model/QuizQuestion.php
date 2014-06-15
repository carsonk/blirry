<?php

App::uses('AppModel', 'Model');

class QuizQuestion extends AppModel {
  public $actsAs = array('Containable');

  public $belongsTo = array(
    'Quiz' => array(
      'className' => 'Quiz',
      'foreignKey' => 'quiz_id'
    )
  );

  public $hasMany = array(
    'Option' => array(
      'className' => 'QuizOption',
      'foreignKey' => 'quiz_question_id'
    )
  );
}