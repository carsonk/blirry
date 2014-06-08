<?php

App::uses('AppModel', 'Model');

class QuizQuestion extends AppModel {
  public $belongsTo = array(
    'Quiz' => array(
      'className' => 'Quiz',
      'foreignKey' => 'quiz_id'
    )
  );
}