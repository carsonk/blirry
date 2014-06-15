<?php

App::uses('AppModel', 'Model');

class QuizOption extends AppModel {
  public $belongsTo = array(
    'QuizQuestion' => array(
      'className' => 'QuizQuestion',
      'foreignKey' => 'quiz_question_id'
    )
  );
}