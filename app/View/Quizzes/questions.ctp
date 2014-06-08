<?php

$this->start('scripts');
echo $this->Html->script('jquery-scrollTo');
echo $this->Html->script('quiz-creator');
$this->end();

?>

<div class="page-header">
  <h1>Make your quiz! <small>You are making a <?php echo $quizTypeForDisplay; ?> quiz.</small></h1>
</div>

<?php echo $this->Form->create('Quiz'); ?>
  <?php 

  $quizValue = ($currentQuiz['Quiz']['title'] != 'Untitled') ? $currentQuiz['Quiz']['title'] : null;

  echo $this->Form->input('title', array(
    'class' => 'form-control input-lg',
    'placeholder' => 'Enter a title!',
    'div' => 'form-group',
    'label' => false,
    'value' => $quizValue
  )); 

  echo $this->Form->input('description', array(
    'class' => 'form-control',
    'placeholder' => 'Enter a tagline for your quiz!',
    'div' => 'form-group',
    'label' => false
  )); 

  ?>
<?php echo $this->Form->end(); ?>
<hr />

<h2>
  <span>Add some possible personalities!</span> 
  <button class="add-personality btn btn-default pull-right"><span class="glyphicon glyphicon-plus"></span> Add Personality</button>
</h2>
<div class="personality-manage-panel panel panel-default">
  <div class="panel-body personality-manage-panel-body">

  </div>
</div>

<h2>
  <span>Add some questions!</span> 
  <button class="add-question btn btn-default pull-right"><span class="glyphicon glyphicon-plus"></span> Add Question</button>
</h2>
<div class="question-manage-panel panel panel-default">
  <div class="panel-body question-manage-panel-body">

  </div>
</div>

<script type="text/javascript">
  var quizCreator;

  $(document).ready(function() {
    quizCreator = new QuizCreator();
    quizCreator.init();
  });
</script>

<!-- 
***************************
***** Clone Templates *****
***************************
-->

<div class="clone-templates">

  <!-- Personality Form -->
  <div class="personality-form-template">
    <div class="personality panel panel-default" id="personality-{ITERATION}" data-iteration="{ITERATION}" data-removed="false" data-dbkey="{KEY}">
      <div class="panel-heading">

        <div class="input-group" style="width:100%;">
          <input type="text" id="personality-title-{ITERATION}" name="personality-title[{ITERATION}]" class="question-title-field form-control" id="question" value="{TITLE}" placeholder="Personality Title" />
          <span class="input-group-btn">
            <button class="btn btn-warning" onclick="quizCreator.Personalities.remove({ITERATION});">Remove</button>
          </span>
        </div>

      </div>

      <div class="panel-body">
        <div class="form">
          <div class="form-group">
            <label>Personality Details</label>
            <textarea class="form-control" id="personality-details-{ITERATION}" placeholder="Give a description for this personality.">{DETAILS}</textarea>
          </div>
          <div class="personality-image form-group">
            <label>Image URL</label>
            <input class="form-control" id="personality-image-url-{ITERATION}" name="personality-image-url[{ITERATION}]" type="text" name="url" value="{IMAGE-URL}" placeholder="http://example.com/image.jpg" />
          </div>
        </div>
      </div>

    </div>
  </div>


  <!-- Question Form -->
  <div class="question-form-template">
    <div class="question panel panel-default">
      <div class="panel-heading">

        <div class="input-group" style="width:100%;">
          <input type="text" class="question-title-field form-control" id="question" placeholder="Question Title" />
          <span class="input-group-btn">
            <button class="btn btn-warning">Remove</button>
          </span>
        </div>

      </div>

      <div class="panel-body">

        <div class="input-group" style="width:100%;">
          <input type="text" class="question-title-field form-control" id="question" placeholder="Option 1" />

          <span class="input-group-btn">
            <button class="btn btn-default">
              <span class="glyphicon glyphicon-remove"></span>
            </button>
          </span>
        </div>

        <div class="option-manage-section">
          <div class="panel panel-default traits-panel">
            <div class="panel-heading">
              Traits Configuration
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th class="width-70">Trait</th>
                  <th class="width-30">
                    <abbr title="Use negative to remove.">Add/Remove Points</abbr>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <select class="traits-select form-control"></select>
                  </td>
                  <td>
                    <input class="form-control" type="number" min="-100" max="100" value="0" />
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="2">
                    <button class="btn btn-primary btn-block">Add Trait</button>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>

          <div class="panel panel-default option-settings-panel">
            <div class="panel-heading">
              Settings
            </div>
            <div class="panel-body">
              No available settings.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Option -->
</div>