<?php

App::uses('AppModel', 'Model');

class QuizOptionTrait extends AppModel {
  public $belongsTo = array(
    'QuizOption' => array(
      'className' => 'QuizOption',
      'foreignKey' => 'quiz_option_id'
    ),
    'QuizPersonality' => array(
      'className' => 'QuizPersonality',
      'foreignKey' => 'quiz_personality_id'
    )
  );
}