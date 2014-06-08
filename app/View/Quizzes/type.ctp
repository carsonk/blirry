<div class="page-header">
  <h1>Type? <small>What kind of quiz would you like to make?</small></h1>
</div>
<div class="container">
  <div class="row">

    <?php

      $quizTypeUrl = $this->Html->url(array(
        "controller" => "quizzes",
        "action" => "type"
      ));

    ?>

    <div class="col-md-4 col-md-offset-2">
      <div data-src="<?php echo $quizTypeUrl; ?>/personality" class="thumbnail thumbnail-link" style="height: 367px;;">
        <img src="http://designcollector.net/files/celebrities-timetravel.jpg" alt="" class="image-resize-300-200" />
        <div class="caption">
          <h3>Personality</h3>
          <p>Which of your friends act like your favorite hero? Are you just like DiCaprio? Create a quiz that judges personalities by assigning traits to different questions.</p>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div data-src="<?php echo $quizTypeUrl; ?>/rightwrong" class="thumbnail thumbnail-link" style="height: 367px;">
        <img src="http://knightnews.com/wp-content/uploads/2013/12/114-962240-1.jpg" alt="" class="image-resize-300-200" />
        <div class="caption">
          <h3>Right/Wrong</h3>
          <p>Quiz format where the answers are right and wrong, and the points don't matter. Assign answers to questions that are correct -- or not if you don't feel like it.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $(".thumbnail-link").click(function () {
      window.location = $(this).data("src");
    });
  });
</script>

<br />

<div class="row">

  <div class="alert alert-warning col-md-12">
    <strong>Not here?</strong> 
    <span>Can't find what you're looking for? <a href="#" class="alert-link">Click here to suggest a new type</a>!
  </div>
</div>