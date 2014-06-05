<?php

App::uses('AppController', 'Controller');

class QuizzesController extends AppController {
  public $components = array('Paginator');

  public $paginate = array(
    'fields' => array('Quiz.id', 'Quiz.title'),
    'limit' => 25,
    'order' => array(
      'Quiz.created' => 'desc'
    )
  );

	public function index() {
    $this->Paginator->settings = $this->paginate;

    $quizzes = $this->Paginator->paginate('Quiz');
    $this->set('quizzes', $quizzes);
	}

	public function take($quiz_id) {
    
	}

  public function type() {

    $this->render('type');
  }
}