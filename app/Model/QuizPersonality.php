<?php

App::uses('AppModel', 'Model');

class QuizPersonality extends AppModel {
  public $belongsTo = array(
    'Quiz' => array(
      'className' => 'Quiz',
      'foreignKey' => 'quiz_id'
    )
  );
}