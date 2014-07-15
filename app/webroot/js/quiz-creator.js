/*
* QuizCreator
* Gets shit related to quiz creation done.
*/
function QuizCreator(quizKey) {
  // Properties

  var instance = this;

  this.toBeRemoved = {
    question: [],
    options: [],
    traits: []
  }; // (obj) Object containing arrays of item iterations that need removal.
  this.edited = false; // (bool) Whether form has been edited.
  this.setup = false; // (bool)
  this.quizData = {}; // (obj) Object containing quiz data.
  this.quizKey = quizKey; // (int) Quiz key.

  // Methods

  /*
  * Initializes quiz creator object and sets up quiz page.
  * @param quizData [object] If updating, should contain data relating to the
  *                         current quiz. Can be empty.
  */
  this.init = function(quizData) {
    if(!this.setup) {
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
        instance.setup = true;
      });
    }
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

  /*
  * Sets up base listeners.
  */
  this.setupListeners = function() {
    $(".add-personality").click(function() {
      instance.Personalities.add();
    });

    $(".add-question").click(function() {
      instance.Questions.add();
    });

    instance.Personalities.startTitleListener();
    instance.Questions.startSortableListener();
    instance.Options.startConfigListeners();
  };

  /*
  * Gets quiz data in JSON form and stores it in this.quizData.
  * @returns jqHXR jQuery object containing information about AJAX request.
  *                Used for determining completion.
  */
  this.getQuizData = function() {
    return $.get(root + "quizzes/get.json", {quizID: this.quizKey}, function(data) {
      instance.quizData = data;
    }, "json");
  };

  /*
  * Gets serialized metadata about quiz (e.g. title, tagline)
  * @returns array Array containing form data.
  */
  this.getSerializedMeta = function() {
    var serialized = $("#QuizQuestionsForm").serializeObject();
    return serialized;
  };

  this.save = function() {
    var quizData = instance.getSerializedMeta();
    var personalityData = instance.Personalities.getSerialized();
    var questionData = instance.Questions.getSerialized();

    var postData = {
      "Quiz": quizData,
      "Personality": personalityData,
      "Question": questionData
    };

    console.log(postData);

    /*$.ajax({
      type: "POST",
      url: "",
      data: JSON.stringify(postData),
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      success: function(data) { console.log(data); },
      failure: function(errMsg) {
          console.log(errMsg);
      }
    });*/
  }

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

      if(personality) {
        instance.Traits.addPersonalityToSelects(personality, this.currentIteration);
      } else {
        instance.Traits.addPersonalityToSelects(null, this.currentIteration);

        if(instance.setup) { 
          $.scrollTo($("#personality-" + this.currentIteration), { duration: 500, over: -.5 }); 
        }
      }

      this.currentIteration++;
      return (this.currentIteration - 1);
    };

    this.remove = function(iterationNo) {
      var element = $("#personality-" + iterationNo);

      $(element).data("removed", "true");
      $(element).hide();
      $(".trait-option-" + iterationNo).remove();
    };

    this.startTitleListener = function() {
      $(".personality-manage-panel-body").on("blur", ".question-title-field", function() {
        var personalityIteration = $(this).parents(".personality").data("iteration");
        var newTitle = $(this).val();

        instance.Traits.updatePersonalitiesInSelects(personalityIteration, newTitle);
      });
    };

    this.getSerialized = function() {
      var personalities = [];

      $(".personality-manage-panel-body .personality").each(function() {
        personalities.push( $(this).serializeObject() );
      });

      return personalities;
    }
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
        if(instance.setup) {
          $.scrollTo($("#question-" + this.currentIteration), { duration: 300 });
        }

        // Add to blank options.
        instance.Options.add(null, this.currentIteration);
        instance.Options.add(null, this.currentIteration);
      }

      $(".question-drag-handle").tooltip({});

      instance.Options.startSortableListener(this.currentIteration);

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
        handle: ".question-drag-handle"
      });
    };

    this.serializeSort = function() {
      return $( ".question-manage-panel-body" ).sortable( 
        "toArray", 
        { attribute: "data-iteration" }
      );
    };

    this.getSerialized = function() {
      var questions = [];
      var iterationOrder = this.serializeSort();

      $.each(iterationOrder, function(key, iterationNo) {
        if($(this).data("removed") != "true") {
          var question = {};
          var selector = "#question-" + iterationNo;

          question.questionIteration = $(selector).data("iteration");
          question.questionKey = $(selector).data("dbkey");
          question.question = $(selector + " .question-title-field").val();
          question.image_url = "";
          question.order = key;

          question.options = instance.Options.getSerializedByQuestion(iterationNo);

          questions.push(question);
        }
      });

      return questions;
    };
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

      if(option) {
        $.each(option.Trait, function(key, trait) {
          instance.Traits.add(trait, instance.Options.currentIteration);
        });
      } else {
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

    this.startSortableListener = function(questionIteration) {
      var questionBody = $("#question-" + questionIteration + " .question-body");

      $(questionBody).sortable({
        containment: "parent",
        handle: ".option-drag-handle",
        items: "> .option"
      });
    };

    this.serializeSort = function(questionIteration) {
      var questionBody = $("#question-" + questionIteration + " .question-body");
      return $( questionBody ).sortable( 
        "toArray", 
        { attribute: "data-iteration" }
      );
    };

    this.startConfigListeners = function() {
      $(".question-manage-panel-body").on('focus', ".option-title-field", function() {
        var affectedSection = $(this).parent().parent().children(".option-manage-section:hidden");

        if($(affectedSection).is(":hidden")) { 
          $(".option-manage-section:visible").slideUp();
          $(affectedSection).slideDown();
        }
      });

      // hide option configuration when clicking outside of a question
      $(document).mouseup(function (e) {
        var container = $(".question");

        if (!container.is(e.target) 
          && container.has(e.target).length === 0) {
          $(".option-manage-section:visible").slideUp();
        }
      });
    };

    this.getSerializedByQuestion = function(questionIteration) {
      var options = [];
      var iterationOrder = this.serializeSort(questionIteration);

      $.each(iterationOrder, function(key, iterationNo) {
        var selector = $("#option-" + iterationNo);

        if(!$(selector).data("removed")) {
          var option = {};

          option.optionIteration = $(selector).data("iteration");
          option.optionKey = $(selector).data("dbkey");
          option.title = $(selector).children(".input-group").children(".option-title-field").val();
          option.image_url = "";
          option.order = key;

          option.traits = instance.Traits.getSerializedByOption(option.optionIteration);

          options.push(option);
        }
      });

      return options;
    };
  };

  this.Traits = new function() {
    this.currentIteration = 0;

    this.add = function(trait, optionIteration) {
      // Since trait template is changed after page load, has to be pulled in on every add.
      var updatedCloneHtml = $(".clone-templates .trait-template").html().replace('<tbody>', '').replace('</tbody>','');
      var questionIteration = $("#option-" + optionIteration).data("question");
      var pointsValue = (typeof(trait) !== "undefined" && trait != null) ? trait.points : 0;

      updatedCloneHtml = updatedCloneHtml.replace(/\{ITERATION\}/g, this.currentIteration).replace(/\{OPTION-ITERATION\}/g, optionIteration).replace('{POINTS}', pointsValue);

      $("#option-" + optionIteration + " .traits-tbody").append(updatedCloneHtml);

      if(trait) {
        $("#traits-select-" + this.currentIteration + " option[data-personalitykey='" + trait.quiz_personality_id + "']").attr("selected", true);
      }

      this.currentIteration++;
      return (this.currentIteration - 1);
    };

    this.remove = function(iterationNo) {
      var selector = $("#trait-" + iterationNo);

      $(selector).hide();
      $(selector).data("removed", true);
    };

    this.addPersonalityToSelects = function(personality, personalityIteration) {
      var newOption = '<option class="trait-option trait-option-{PERSONALITY-ITERATION}" data-personalitykey="{PERSONALITY-KEY}" value="{PERSONALITY-ITERATION}">{PERSONALITY-TITLE}</option>';

      if(personality) {
        newOption = newOption.replace(/\{PERSONALITY-ITERATION\}/g, personalityIteration).replace('{PERSONALITY-KEY}', personality.id).replace('{PERSONALITY-TITLE}', personality.title);
      } else {
        newOption = newOption.replace(/\{PERSONALITY-ITERATION\}/g, personalityIteration).replace('{PERSONALITY-KEY}', '').replace('{PERSONALITY-TITLE}', 'Untitled');
      }

      $(".traits-select").append(newOption);
    };

    this.updatePersonalitiesInSelects = function(personalityIteration, newTitle) {
      $(".trait-option-" + personalityIteration).html(newTitle);
    };

    this.getSerializedByOption = function(optionIteration) {
      var traits = [];

      $("#option-" + optionIteration + " .trait").each(function() {
        if($(this).data("removed") != true) {
          var currentTrait = {
            traitIteration: null,
            personalityIteration: null,
            personalityKey: null,
            points: null
          };

          var selectSelector = $(this).children("td").children(".traits-select");
          var pointsSelector = $(this).children("td").children(".traits-points");

          currentTrait.traitIteration = $(this).data("trait");
          currentTrait.personalityIteration = parseInt($(selectSelector).find(":selected").val());
          currentTrait.personalityKey = $(selectSelector).find(":selected").data("personalitykey");
          currentTrait.points = $(pointsSelector).val();

          if(!isNaN(currentTrait.personalityIteration)) {
            traits.push( currentTrait );
          }
        }
      });

      return traits;
    }
  }
}