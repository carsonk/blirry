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

    instance.Questions.startSortableListener();

    instance.Options.startConfigListeners();
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
    this.personalityClone = $(".clone-templates .personality-form-template").clone();

    this.add = function(personality) {
      var updatedCloneHtml = this.personalityClone.html();

      if(personality) {
        updatedCloneHtml = updatedCloneHtml.replace(/\{ITERATION\}/g, this.currentIteration).replace("{TITLE}", personality.title).replace("{DETAILS}", personality.description).replace("{IMAGE-URL}", personality.image_url).replace(/\{KEY\}/g, personality.id);
      } else {
        updatedCloneHtml = updatedCloneHtml.replace(/\{ITERATION\}/g, this.currentIteration).replace("{TITLE}", "").replace("{DETAILS}", "").replace("{IMAGE-URL}", "").replace(/\{KEY\}/g, "0");
      }
      

      $(".personality-manage-panel-body").append(updatedCloneHtml);

      if(!personality) {
        $.scrollTo($("#personality-" + this.currentIteration), { duration: 500, over: -.5 });
      }

      this.currentIteration++;
      return (this.currentIteration - 1);
    };

    this.remove = function(iterationNo) {
      var element = $("#personality-" + iterationNo);

      $(element).data("removed", "true");
      $(element).hide();
    };
  };

  this.Questions = new function() {
    this.currentIteration = 0; // Used to identify personality blocks.
    this.questionClone = $(".clone-templates .question-form-template").clone();

    this.add = function(question) {
      var updatedCloneHtml = this.questionClone.html();

      if(question) {
        updatedCloneHtml = updatedCloneHtml.replace(/\{ITERATION\}/g, this.currentIteration).replace("{QUESTION}", question.question).replace("{IMAGE-URL}", question.image_url).replace(/\{WEIGHT\}/g, question.weight).replace(/\{KEY\}/g, question.id);
      } else {
        updatedCloneHtml = updatedCloneHtml.replace(/\{ITERATION\}/g, this.currentIteration).replace("{QUESTION}", "").replace(/\{WEIGHT\}/g, 0).replace("{IMAGE-URL}", "").replace(/\{KEY\}/g, "0");
      }

      $(".question-manage-panel-body").append(updatedCloneHtml);

      // If adding blank, scroll to blank one.
      if(question) {
        $.each(question.Option, function(key, option) {
          instance.Options.add(option, instance.Questions.currentIteration);
        });
      } else {
        $.scrollTo($("#question-" + this.currentIteration), { duration: 300 });

        // Add to blank options.
        instance.Options.add(null, this.currentIteration);
        instance.Options.add(null, this.currentIteration);
      }

      $(".drag-handle").tooltip({});

      this.currentIteration++;
      return (this.currentIteration - 1);
    };

    this.remove = function(iterationNo) {
      var element = $("#question-" + iterationNo);

      $(element).hide();
      $(element).data("removed", "true");
    };

    this.startSortableListener = function() {
      $(".question-manage-panel-body").sortable({
        containment: "parent",
        handle: ".drag-handle"
      });
    };

    this.serializeSort = function() {
      return $( ".question-manage-panel-body" ).sortable( "serialize", { key: "sort" } );
    }
  };
  // </questions>

  this.Options = new function() {
    this.currentIteration = 0;
    this.optionClone = $(".clone-templates .option-template").clone();

    this.add = function(option, questionIteration) {
      var updatedCloneHtml = this.optionClone.html();

      if(option) {
        updatedCloneHtml = updatedCloneHtml.replace(/\{ITERATION\}/g, this.currentIteration).replace(/\{QUESTION-ITERATION\}/g, questionIteration).replace("{TITLE}", option.title).replace("{IMAGE-URL}", option.image_url).replace(/\{KEY\}/g, option.id).replace("{ORDER}", option.order);
      } else {
        updatedCloneHtml = updatedCloneHtml.replace(/\{ITERATION\}/g, this.currentIteration).replace(/\{QUESTION-ITERATION\}/g, questionIteration).replace("{TITLE}", "").replace("{IMAGE-URL}", "").replace(/\{KEY\}/g, "").replace("{ORDER}", "");
      }

      $("#question-" + questionIteration + " .question-body").append(updatedCloneHtml);

      if(!option) {
        instance.Traits.add(null, this.currentIteration);
      }

      this.currentIteration++;
      return (this.currentIteration - 1);
    };

    this.addWithQuestionKey = function(option, questionKey) {

    };

    this.remove = function(iterationNo) {
      var toRemove = $("#option-" + iterationNo);

      $(toRemove).hide();
      $(toRemove).data("removed", true);
    };

    this.startSortableListener = function() {

    };

    this.startConfigListeners = function() {
      $(".question-manage-panel-body").on('focus', ".option-title-field", function() {
        var affectedSection = $(this).parent().parent().children(".option-manage-section:hidden");

        if($(affectedSection).is(":hidden")) { 
          $(".option-manage-section:visible").slideUp();
          $(affectedSection).slideDown();
        }
      });
    };

    this.serializeSort = function() {

    };
  };

  this.Traits = new function() {
    this.currentIteration = 0;
    this.traitClone = $(".clone-templates .trait-template").html();
    this.traitOptionTemplate = '<option {SELECTED} class="trait-option trait-option-{PERSONALITY-ITERATION}" data-personalitykey="{PERSONALITY-KEY}">{PERSONALITY-TITLE}</option>';

    this.add = function(trait, optionIteration) {
      console.log('running');

      var updatedCloneHtml = this.traitClone;
      var questionIteration = $("#option-" + optionIteration).data("question");
      var pointsValue = (typeof(trait) !== "undefined" && trait != null) ? trait.points : 0;

      console.log(this.traitClone);

      updatedCloneHtml = updatedCloneHtml.replace(/\{ITERATION\}/g, this.currentIteration).replace(/\{OPTION-ITERATION\}/g, optionIteration).replace('{POINTS}', pointsValue);

      $("#option-" + optionIteration + " .traits-tbody").append(updatedCloneHtml);

      this.currentIteration++;
      return (this.currentIteration - 1);
    };

    this.remove = function(iterationNo) {

    };

    this.addPersonalityToSelects = function(personality, personalityIteration, selected) {
      var newOption = this.traitOptionTemplate.replace('{PERSONALITY-ITERATION}', personalityIteration).replace('{PERSONALITY-KEY}', personality.id).replace('{PERSONALITY-TITLE}', personality.title);

      if(selected) {
        newOption = newOption.replace('{SELECTED}', 'selected="selected"');
      } else {
        newOption = newOption.replace('{SELECTED}', '');
      }

      $(".traits-select").append(newOption);
    };

    this.updatePersonalitiesInSelects = function(personalityIteration, newTitle) {
      $(".trait-option-" + personalityIteration).html(newTitle);
    };
  }
}