<?php

$this->start('scripts');
echo $this->Html->script('jquery-scrollTo');
echo $this->Html->script('quiz-creator');
$this->end();

?>

<div class="page-header">
  <h1>
    Make your quiz! <small>You are making a <?php echo $quizTypeForDisplay; ?> quiz.</small>

    <div class="pull-right">
      <button class="btn btn-primary">Save Quiz</button>
      <button class="btn btn-default"><span class="glyphicon glyphicon-cog"></button>
    </div>
  </h1>
</div>

<?php echo $this->Form->create('Quiz'); ?>
  <?php 

  $quizValue = ($currentQuiz['Quiz']['title'] != 'Untitled') ? $currentQuiz['Quiz']['title'] : null;

  echo $this->Form->input('title', array(
    'class' => 'form-control input-lg',
    'placeholder' => 'Enter a title!',
    'div' => 'form-group',
    'label' => false,
    'value' => $quizValue,
    'name' => 'title'
  )); 

  echo $this->Form->input('description', array(
    'class' => 'form-control',
    'placeholder' => 'Enter a tagline for your quiz!',
    'div' => 'form-group',
    'label' => false,
    'name' => 'description'
  )); 

  ?>
<?php echo $this->Form->end(); ?>
<hr />

<h2>
  <span>Add some possible personalities!</span> 
  <button class="add-personality btn btn-default pull-right"><span class="glyphicon glyphicon-plus"></span> Add Personality</button>
</h2>
<div class="personality-manage-panel">
  <div class="personality-manage-panel-body">

  </div>
</div>

<h2>
  <span>Add some questions!</span> 
  <button class="add-question btn btn-default pull-right"><span class="glyphicon glyphicon-plus"></span> Add Question</button>
</h2>
<div class="question-manage-panel">
  <div class="question-manage-panel-body">

  </div>
</div>

<script type="text/javascript">
  var quizCreator;

  $(document).ready(function() {
    quizCreator = new QuizCreator(<?php echo $currentQuiz['Quiz']['id']; ?>);
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
    <div class="question panel panel-default" id="question-{ITERATION}" data-iteration="{ITERATION}" data-removed="false" data-dbkey="{KEY}">
      <div class="panel-heading">

        <div class="input-group" style="width:100%;">
          <span class="question-drag-handle input-group-addon" data-toggle="tooltip" data-placement="top" title="Click to drag.">
            <span class="glyphicon glyphicon-move"></span>
          </span>
          <input type="text" value="{QUESTION}" class="question-title-field form-control" id="question" placeholder="Ask a question?" />
          <span class="input-group-btn">
            <button class="btn btn-warning" onclick="quizCreator.Questions.remove('{ITERATION}');">Remove</button>
          </span>
        </div>

      </div>

      <div class="panel-body question-body">
        <div class="question-toolbar">
          <button class="btn btn-default btn-sm" onclick="quizCreator.Options.add(null, {ITERATION})">Add Option</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Option -->
  <div class="option-template">
    <div class="option" id="option-{ITERATION}" data-question="{QUESTION-ITERATION}" data-iteration="{ITERATION}" data-dbkey="{KEY}" data-removed="false">
      <div class="input-group" style="width:100%;">
        <span class="option-drag-handle input-group-addon" data-toggle="tooltip" data-placement="top" title="Click to drag.">
          <span class="glyphicon glyphicon-move"></span>
        </span>

        <input type="text" class="option-title-field form-control" placeholder="Option" value="{TITLE}" />

        <span class="input-group-btn">
          <button class="btn btn-default" onclick="quizCreator.Options.remove('{ITERATION}');">
            <span class="glyphicon glyphicon-remove"></span>
          </button>
        </span>
      </div>
      <div class="option-manage-section">
        <div class="panel panel-default traits-panel">
          <div class="panel-heading">
            Traits Configuration

            <button class="btn btn-default btn-sm pull-right" onclick="quizCreator.Traits.add(null, {ITERATION})">
              <span class="glyphicon glyphicon-plus"></span>
              Add
            </button>
          </div>
          <table class="table traits-config-table">
            <thead>
              <th style="width: 70%;">Personality</th>
              <th style="width: 20%;">
                <abbr title="Use negative to remove.">Add/Remove Points</abbr>
              </th>
              <th style="width: 10%;">Tools</th>
            </thead>
            <tbody class="traits-tbody faux-tbody">
              
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Trait -->
  <table class="trait-template">
    <tr id="trait-{ITERATION}" class="trait" data-option="{OPTION-ITERATION}" data-trait="{ITERATION}" data-removed="false">
      <td>
        <select name="personality" id="traits-select-{ITERATION}" class="traits-select form-control">
          <option class="trait-option-blank" value=""></option>
        </select>
      </td>
      <td>
        <input class="form-control traits-points" name="points" type="number" min="-100" max="100" value="{POINTS}" />
      </td>
      <td>
        <a href="javascript:quizCreator.Traits.remove('{ITERATION}');">Remove</a>
      </td>
    </tr>
  </table>
</div>