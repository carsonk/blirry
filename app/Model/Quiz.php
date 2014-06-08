<?php

App::uses('AppModel', 'Model');

class Quiz extends AppModel {
  public $belongsTo = array(
    'Creator' => array(
      'className' => 'User',
      'foreignKey' => 'user_id'
    )
  );

  public $hasMany = array(
    'Personality' => array(
      'className' => 'QuizPersonality',
      'foreignKey' => 'quiz_id'
    ),
    'Question' => array(
      'className' => 'QuizQuestion',
      'foreignKey' => 'quiz_id'
    )
  );

  public $possibleTypes = array('personality', 'rightwrong');
  public $htmlAllowed = array();

  public static function getDisplayType($flatType) {
    switch($flatType) {
      case "personality":
        return "Personality";
      case "rightwrong":
        return "Right/Wrong";
      default:
        return "";
    }
  }
}