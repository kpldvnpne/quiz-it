<?php
  require 'data-provider.php';

  // TODO: make quizId random when not provided
  $quizId = isset($_GET["quizID"]) ? $_GET["quizId"] : 1;

  $quizData = QuizDataProvider::getAllQuizData($quizId);
?>

<html>
<head>
  <!-- CSS and JS for making MDC (Material Design Components) work -->
  <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
  <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>

  <!-- Material Icons from Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

  <!-- Roboto Font from Google -->
  <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Slab&display=swap" rel="stylesheet">

  <title>Quiz</title>

  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <style>

    :root {
      --mdc-theme-primary: #17EA95;
      /* --mdc-theme-secondary: white; */
      --mdc-theme-surface: blue;
    }

    body {
      border: 4px solid black;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Roboto, sans-serif;
    }

    .partition-item {
      display: block;
      float: left;
      height: 100%;
    }

    .partition-item-4 {
      width: 45%;
    }

    .partition-item-6 {
      width: 55%;
    }

    .partition-item:last-child::after {
      content: "";
      display: table;
      clear: both;
    }

    @media only screen and (max-width: 768px) {
      body {
        border: none;
      }

      [class*="partition-item-"] {
        width: 100%;
      }

      #right-partition-item {
        height:fit-content;
        overflow-y: auto !important;
      }
    }

    #left-partition-item {
      background-image: url('./assets/images/quiz-background-image-1.jpg');
      background-size: cover;
      background-position: 0 -100px;

      color: white;
    }

    #right-partition-item {
      overflow-y: scroll;
    }

    .toolbar {
      height: 15%;
    }

    .toolbar__content {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      height: 100%;
    }

    .toolbar__content__title {
      margin-left: 10%;
      font-size: 30px;
      font-family: 'Roboto Slab', sans-serif;
    }

    .quiz-description {
      display: flex;
      flex-direction: column;
      justify-content: center;
      height: 70%;
    }

    .hashtag.mdc-chip {
      background-color: rgb(255, 255, 255, 0.5);
      /* opacity: 0.5; */
      text-shadow: none;
      text-transform: uppercase;
    }

    .quiz-description, .footer {
      width: 50%;
      padding-left: 25%;
      text-shadow: 0 0 10px black;
    }

    .mdc-linear-progress__buffer {
      opacity: 0.5;
    }

    /* Question section */

    .question {
      margin: 18% auto auto 15%;
      margin-bottom: 5%;
      width: 60%;
      max-width: 500px;
      min-width: 300px;
    }

    .question__number {
      text-transform: uppercase;
      font-weight: bold;
      color: #7BC1AA;
    }

    .question__title {
      font-family: 'Roboto Slab';
      font-size: 30px;
    }

    .question-option {
      margin: 0;
      padding: 0;
    }

    .question-option-item {
      background-color: rgb(245, 249, 249);
      margin-bottom: 5px;
    }

    .question-option-item.mdc-list-item--selected {
      --mdc-theme-primary: white;
      background-color: rgb(0, 150, 206);
    }

    .question__next, .quiz__submit {
      --mdc-theme-primary: #00F78E;
      --mdc-theme-on-primary: black;
      margin-top: 0;
      height: 50px;
      width: 100%;
      font-weight: bold;
    }

    .question__next:disabled, .quiz__submit:disabled {
      --mdc-theme-primary: #B1AFBC;
      --mdc-theme-on-primary: white;
    }

  </style>
</head>
<body>

  <div class="partition">
    <aside class="partition-item partition-item-4" id="left-partition-item">      
      <div class="toolbar">
        <header class="toolbar__content">
          <span class="toolbar__content__title">Quiz !t</span>
          <div class="social-media-buttons">
            <a href="#" class="mdc-icon-button material-icons">favorite</a>
            <a href="#" class="mdc-icon-button material-icons">bookmark</a>
            <a href="#" class="mdc-icon-button material-icons">save</a>
          </div>
        </header>

        <div role="progressbar" class="mdc-linear-progress">
          <div class="mdc-linear-progress__buffer"></div>
          <div class="mdc-linear-progress__bar mdc-linear-progress__primary-bar">
            <span class="mdc-linear-progress__bar-inner"></span>
          </div>
          <div class="mdc-linear-progress__bar mdc-linear-progress__secondary-bar">
            <span class="mdc-linear-progress__bar-inner"></span>
          </div>
        </div>
      </div>

      <div class="quiz-description">
        <div class="mdc-chip-set hashtag-set">

          <?php foreach ($quizData['tags'] as $tag): ?>
            <div class="mdc-chip hashtag">
              <div class="mdc-chip__text"> 
                #<?=$tag?> 
              </div>
            </div>
          <?php endforeach; ?>

        </div>
        <h1><?=$quizData['quizTitle']?></h1>
        <p><?=$quizData['quizDetails']?></h3>
      </div>

      <footer class="footer">
        <strong>BY ISAAC NEWTON</strong>
        <p>OCT 23, 2018</p>
      </footer>
    </aside>

    <main class="partition-item partition-item-6" id="right-partition-item">
      <form action="result.php" method="POST">

      <?php foreach($quizData['questions'] as $questionNo => $question): ?>
        <div class="question">
          <label class="question__number">Question <?=$questionNo + 1?></label>
          <h2 class="question__title"><?=$question['questionTitle']?></h2>
          <div class="mdc-list question-option" data-mdc-auto-init="MDCList">

            <?php $nameFormOption = "question[$questionNo]" ?>

            <?php foreach($question['options'] as $optionIndex => $option):?>
              <?php $optionId = 'question'.$questionNo.'option'.$optionIndex ?>

              <label for="<?=$optionId?>" class="mdc-list-item question-option-item" <?= $optionIndex === 0 ? 'tabindex="0"': ''; ?> >
                <span class="mdc-list-item__text"><?=$option?></span>
              </label>
              <input id="<?=$optionId?>" style="display: none" type="radio" name="<?=$nameFormOption?>" value="<?=$optionIndex?>"/>

            <?php endforeach; ?>

          </div>

          <?php if ($questionNo + 1 === count($quizData['questions'])): ?>
            <button class="mdc-button mdc-button--unelevated quiz__submit" data-mdc-auto-init="MDCRipple" disabled>Submit</button>
          <?php else: ?>
            <button type="button" onclick="return false;" class="mdc-button mdc-button--unelevated question__next" data-mdc-auto-init="MDCRipple" disabled>Next</button>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

      </form>
    </main>
  </div> 

  <script>
    // Auto Init
    window.mdc.autoInit();

    const MDCLinearProgress = mdc.linearProgress.MDCLinearProgress;
    const MDCRipple = mdc.ripple.MDCRipple;

    const progressBar = new MDCLinearProgress(document.querySelector('.mdc-linear-progress'));

    initializeBehaviour();

    function initializeBehaviour() {
      // make all question options single select
      // also make them ripple 
      applyBehaviourToAllElements('.question-option', function (element) {
        element.MDCList.singleSelection = true;
        element.MDCList.listElements.map((listItemEl) => new MDCRipple(listItemEl));
      });

      // apply button color changes on question options select
      applyBehaviourToAllElements('.question', function (element) {
        element.addEventListener('MDCList:action', function (event) {
          const nextElementSibling = event.target.nextElementSibling;
          if (nextElementSibling.classList.contains('question__next'))
            nextElementSibling.disabled = false;
        })
      })

      // make all button scroll to next question on finish
      applyBehaviourToAllElements('.question__next', function (element) {
        element.addEventListener('click', function (event) {
            const nextElementSibling = event.target.parentNode.nextElementSibling;
            nextElementSibling && nextElementSibling.scrollIntoView({ behavior: 'smooth', block: 'center' });
        })
      })

      applyBehaviourToAllElements('#right-partition-item', function (element) {
        element.addEventListener('MDCList:action', function (event) {
          const progress = getFractionOfAnsweredQuestions();
          progressBar.progress = progress;
          if (progress === 1) {
            document.querySelector('.quiz__submit').disabled = false;
          }
        })
      })
    }

    function applyBehaviourToAllElements(selector, behaviour) {
      const elements = document.querySelectorAll(selector);
      for (let i = 0; i < elements.length; ++i) {
        behaviour(elements[i]);
      }
    }

    function getFractionOfAnsweredQuestions() {
      return getAnsweredQuestionsCount() / getTotalQuestionsCount();
    }

    function getAnsweredQuestionsCount() {
      let answeredQuestionsCount = 0;

      // count no. of .question which has .mdc-list-item--selected as a decendent
      for (let element of document.querySelectorAll('.question')) {
        if (element.querySelectorAll('.mdc-list-item--selected').length > 0) {
          ++answeredQuestionsCount;
        }
      }

      return answeredQuestionsCount;
    }

    function getTotalQuestionsCount() {
      return document.querySelectorAll('.question').length;
    }
  </script>

</body>
</html>