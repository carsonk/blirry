function QuizCreator() {
  // Properties
  var instance = this;
  this.quizData = {};

  // Methods

  /*
  * Initializes quiz creator object and sets up quiz page.
  * @param quizData [object] If updating, should contain data relating to the
  *                         current quiz. Can be empty.
  */
  this.init = function(quizData) {
    App.showLoading();

    // Completes page setup.
    $.when( 
      this.quizDataSetup(quizData) 
    ).then(function() {
      console.log(this.quizData);
      if(instance.quizData.Personality.length > 0) {
        $.each(instance.quizData.Personality, function(key, currentPers) {
          instance.Personalities.add(currentPers);
        });
      } else {
        instance.Personalities.add();
        instance.Personalities.add();
      }

      if(instance.quizData.Question.length > 0) {
        $.each(instance.quizData.Question, function(key, currentQues) {
          instance.Questions.add(currentQues);
        });
      } else {
        instance.Questions.add();
        instance.Questions.add();
      }

      instance.setupListeners();
      App.closeLoading();
    });
  };

  /*
  * Does essential page setup for quiz creator page.
  * @param quizData [object] If updating, should contain data relating to the
  *                          current quiz. Can be empty.
  */
  this.quizDataSetup = function(quizData) {
    var toReturn;

    if(typeof quizData != "undefined") {
      this.quizData = quizData;
      toReturn = true;
    } else {
      toReturn = this.getQuizData();
    }

    return toReturn;
  };

  this.setupListeners = function() {
    $(".add-personality").click(function() {
      instance.Personalities.add();
    });

    $(".add-question").click(function() {
      instance.Questions.add();
    });
  };

  /*
  * Gets quiz data in JSON form and stores it in this.quizData.
  * @returns jqHXR jQuery object containing information about AJAX request.
  *                Used for determining completion.
  */
  this.getQuizData = function() {
    return $.get(root + "quizzes/get.json", {quizID: 6}, function(data) {
      instance.quizData = data;
    }, "json");
  };


  // Inner Objects
  this.Personalities = new function() {
    this.currentIteration = 0; // Used to identity personality blocks.

    this.add = function(personality) {
      var personalityClone = $(".clone-templates .personality-form-template").clone();
      var updatedCloneHtml = personalityClone.html();

      if(personality) {
        updatedCloneHtml = updatedCloneHtml.replace(/\{ITERATION\}/g, this.currentIteration).replace("{TITLE}", personality.title).replace("{DETAILS}", personality.description).replace("{IMAGE-URL}", personality.image_url).replace(/\{KEY\}/g, personality.id);
      } else {
        updatedCloneHtml = updatedCloneHtml.replace(/\{ITERATION\}/g, this.currentIteration).replace("{TITLE}", "").replace("{DETAILS}", "").replace("{IMAGE-URL}", "").replace(/\{KEY\}/g, "0");
      }
      

      $(".personality-manage-panel-body").append(updatedCloneHtml);

      if(!personality) {
        $.scrollTo($("#personality-" + this.currentIteration), { duration: 300 });
      }

      this.currentIteration++;
    };
    this.remove = function(iterationNo) {
      var element = $("#personality-" + iterationNo);

      $(element).data("removed", "true");
      $(element).hide();
    };
  };

  this.Questions = new function() {
    this.currentIteration = 0; // Used to identify personality blocks.

    this.add = function(question) {
    };
    this.remove = function(iterationNo) {
    };
  };
}