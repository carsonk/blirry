<?php

App::uses('AppController', 'Controller');

class QuizzesController extends AppController {
  public $uses = array('Quiz','QuizPersonality', 'QuizQuestion');
  public $components = array('Paginator', 'RequestHandler');

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

  public function get() {
    if($this->request->is('get')) {
      $currentQuiz = $this->Quiz->find('first', array(
        'conditions' => array('Quiz.id' => $this->request->query['quizID']),
        'fields' => array('Quiz.*', 'Creator.id', 'Creator.username')
      ));

      if(!$currentQuiz) {
        throw new NotFoundException('That quiz doesn\'t exist or has been deleted.');
      }

      $this->set('quiz', $currentQuiz);
      $this->set('_serialize', 'quiz');
    } else {
      throw new BadRequestException('Invalid request.');
    }
  }

  /*
  * Create functions routed from quizzes/create/:action/*.
  */

  public function type($quizType = null) {
    if(isset($quizType) && in_array($quizType, $this->Quiz->possibleTypes)) {

      $this->Quiz->create();
      $saveData = array("Quiz" => array(
        'type' => strtolower($quizType),
        'title' => __('Untitled'),
        'user_id' => $this->Auth->user('id')
      ));
      $this->Quiz->save($saveData);

      $newQuizID = $this->Quiz->getInsertID();
      return $this->redirect(array(
        'controller' => 'quizzes',
        'action' => 'questions',
        $newQuizID
      ));

    } else if(isset($quizType)) {
      throw new BadRequestException('Type was not valid.');
    }

    $this->render('type');
  }

  public function questions($quiz_id) {
    $activeQuiz = $this->Quiz->find('first', array(
      'conditions' => array('Quiz.id' => $quiz_id)
    ));

    if(!$activeQuiz) {
      throw new NotFoundException('That quiz doesn\'t exist or has been deleted.');
    }

    $quizTypeForDisplay = strtolower($this->Quiz->getDisplayType($activeQuiz['Quiz']['type']));

    $this->set('currentQuiz', $activeQuiz);
    $this->set('quizTypeForDisplay', $quizTypeForDisplay);
    $this->render('questions');
  }
}