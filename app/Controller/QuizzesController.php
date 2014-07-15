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

  public $quizContainSettings = array(
    'Creator' => array(
      'fields' => array('Creator.id', 'Creator.username')
    ),
    'Personality',
    'Question' => array(
      'Option' => array(
        'fields' => array('id', 'title'),
        'Trait'
      )
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
        'contain' => $this->quizContainSettings
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

  public function update() {
    if( !( $this->request->is('post') ) ) {
      throw new BadRequestException('No data was submitted.');
    }

    $data = $this->request->input('json_decode');

    $this->processQuiz($data);

    $this->autoRender = false;
  }

  /*
  * Takes unserialized JSON quiz, breaks it up, and submits it to the server.
  * @param quiz Unserialized JSON quiz.
  */
  private function processQuiz($quiz) {
    $quizID = $quiz->Quiz->key;

    if(!is_int($quizID)) {
      throw new BadRequestException('No ID was given.');
    }

    $oldQuiz = $this->Quiz->find('first', array(
      'conditions' => array('Quiz.id' => $quizID),
      'contain' => $this->quizContainSettings
    ));

    if(empty($oldQuiz)) {
      throw new NotFoundException('Could not find that quiz.');
    }

    // Ownership test.
    if($this->Auth->user('id') != $oldQuiz["Quiz"]["user_id"]) {
      throw new ForbiddenException('That quiz does not belong to you.');
    }

    /* Trick way to convert nested objects to arrays. Note that you're working with a
      single-level object that you want to convert to an array, you can type-cast
      the object to an array with $array = (array) $object. Also note that this is a
      testament to both how great and how really shitty PHP is. If anyone at Oracle 
      ever suggested that shit with Java, they would almost certainly be fired on 
      the spot. */
    $newQuiz = json_decode(json_encode($quiz), TRUE);

    print_r($newQuiz);
  }
}