<?php
  require 'data-provider.php';
  require 'constants.php';

  // TODO: make edit.php not accessible when no id is given
  $quizId = isset($_GET["quidId"]) ? $_GET["quizId"] : 1;

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

  <title>Edit -- Quiz</title>

  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <style>

    :root {
      --mdc-theme-primary: #17EA95;
      --mdc-theme-on-primary: black;
    }

    .app-fab--absolute {
      position: fixed;
      bottom: 1.5rem;
      right: 2.5rem;
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
      .app-fab--absolute {
        bottom: 1rem;
        right: 1rem;
      }

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

      position: relative;
    }

    #right-partition-item {
      overflow-y: scroll;
    }

    .change-image-label {
      position: absolute;
      top: -100px;
      left: 50%;
      transform: translateX(-50%);
      /* display: none; */
      transition: top 0.2s ease-in;
    }

    #left-partition-item:hover .change-image-label {
      top: 10px;
      /* display: initial; */
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

    .hashtag-set {
      position: relative;
    }

    .hashtag.mdc-chip {
      background-color: rgb(255, 255, 255, 0.5);
      text-shadow: none;
      text-transform: uppercase;
    }

    .hashtag--add {
      margin: 0 !important;
      z-index: 400;
      position: absolute;
      right: 0;
      top: 50%;
      transform: translateY(-50%);
    }

    .hashtag--add__input {
      width: 0;
      color: transparent;
      text-transform: initial;

      transition: width .1s ease-in;
    }

    .hashtag--add:hover {
      background: white;
    }

    .hashtag--add:hover .hashtag--add__input, .hashtag--add__input:focus {
      position: relative;
      width: auto;
      color: initial;
    }

    .quiz-description, .footer {
      width: 50%;
      padding-left: 25%;
      text-shadow: 0 0 10px black;
    }

    .mdc-linear-progress__buffer {
      opacity: 0.5;
    }

    .quiz-description-form__actions {
      --mdc-theme-secondary: red;
      position: absolute; 
      bottom: 5px; 
      left: 50%; 
      transform: translateX(-50%)
    }

    .quiz-description-form__actions.hidden {
      display: none;
    }

    /* Question section */

    .question {
      position: relative;
      margin: 18% auto auto 15%;
      margin-bottom: 5%;
      width: 60%;
      max-width: 500px;
      min-width: 300px;
    }

    .question-modify {
      display: none;
      position: absolute;
      top: 0;
      right: 0;
    }

    .question-modify__button {
      height: 38px;
      width: 38px;
      border-radius: 50%;
    }

    .question:hover .question-modify {
      display: block;
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

    .question-option-item--pre-selected {
      border: 2px solid purple;
    }

    .question-option-item.mdc-list-item--selected {
      --mdc-theme-primary: white;
      background-color: rgb(0, 150, 206);
    }

    .question__next {
      margin-top: 0;
      --mdc-theme-primary: #B1AFBC;
      height: 50px;
      width: 100%;
      font-weight: bold;
    }

    .question__next--activated {
      --mdc-theme-primary: #00F78E;
      --mdc-theme-on-primary: black;
      color: black;
    }

  </style>
</head>
<body>

  <button class="mdc-fab app-fab--absolute" data-mdc-auto-init="MDCRipple">
    <span class="mdc-fab__icon material-icons">add</span>
  </button>

  <div class="partition">
    <aside class="partition-item partition-item-4" id="left-partition-item">      
      <button class="mdc-button mdc-button--raised change-image-label" data-mdc-auto-init="MDCRipple"><label for="quiz-image">Change image</label></button>
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

      <div class="quiz-description" oninput="handleQuizDescriptionChange()">
        <div class="mdc-chip-set mdc-chip-set--input hashtag-set" data-mdc-auto-init="MDCChipSet">
          <div class="mdc-chip hashtag hashtag--add" onmouseout="handleHashtagInputMouseOut(event)" oninput="event.stopPropagation();" onchange="event.stopPropagation();">
            <i class="material-icons mdc-chip__icon mdc-chip__icon--leading" role="button">add</i>
            <div class="mdc-chip__text hashtag--add__input" contenteditable="true" onkeydown="handleHashtagInputKeyDown(event)">New Hashtag</div>
          </div>

          <?php foreach ($quizData['tags'] as $tagIndex => $tag): ?>
            <div class="mdc-chip hashtag" <?= $tagIndex === 0 ? 'tabindex="0"': ''; ?> >
              <div class="mdc-chip__text">
                # <?= $tag ?>
              </div>
              <i class="material-icons mdc-chip__icon mdc-chip__icon--trailing" role="button">cancel</i>
            </div>
          <?php endforeach; ?>
          
        </div>

        <h1 class="quiz-description__title" contenteditable="true"> <?=$quizData['quizTitle']?> </h1>
        <p class="quiz-description__details" contenteditable="true"> <?=$quizData['quizDetails']?> </h3>
      </div>

      <footer class="footer">
        <strong>BY ISAAC NEWTON</strong>
        <p>OCT 23, 2018</p>
      </footer>

      <form action="save-quiz-details.php" method="POST">
        <input type="text" id="quiz-title" style='display: none;' name="quiz-title" />
        <input type="text" id="quiz-details" style='display: none;' name="quiz-details" />
        <input type="file" id="quiz-image" style='display: none' name="quiz-image" accept="image/*" onchange="handleImageChange(event)" />
        <input type="text" id="quiz-tags" style='display: none' name="quiz-tags">
        <div class="quiz-description-form__actions hidden">
          <button type="submit" id="quiz-description-form__submit" class="mdc-button mdc-button--unelevated"
            data-mdc-auto-init="MDCRipple">Submit</button>
          <button type="reset" id="quiz-description-form__cancel" class="mdc-button mdc-button--unelevated mdc-theme--secondary-bg"
            data-mdc-auto-init="MDCRipple" onclick="refresh()">Cancel</button>
        </div>
      </form>
    </aside>

    <main class="partition-item partition-item-6" id="right-partition-item">

      <?php foreach($quizData['questions'] as $questionIndex => $question): ?>
        <div class="question">
          <div class="question-modify">
            <a href="<?php echo BASE_URL . '/edit-question.php?' . http_build_query(['quizId' => $quizId, 'questionIndex' => $questionIndex, 'questionId' => $question['questionId']]); ?>">
              <button class="mdc-button material-icons question-modify__button" data-mdc-auto-init="MDCRipple">edit</button>
            </a>
            <a href="<?php echo BASE_URL . '/delete-question.php?' . http_build_query(['questionIndex' => $questionIndex, 'questionId' => $question['questionId']]); ?>">
              <button class="mdc-button material-icons question-modify__button" data-mdc-auto-init="MDCRipple">delete</button>
            </a>
          </div>
          <label class="question__number">Question <?=$questionIndex + 1?></label>
          <h2 class="question__title"><?=$question['questionTitle']?></h2>
          <ul class="mdc-list question-option" data-mdc-auto-init="MDCList">

            <?php foreach($question['options'] as $optionIndex => $option): ?>
              <?php 
                $className = "mdc-list-item question-option-item";
                if (in_array($optionIndex, $question['correctOptionIndices'])) {
                  $className .=" question-option-item--pre-selected";
                }
              ?>

              <li class="<?=$className?>" <?=$optionIndex === 0 ? 'tabindex="0"': ''?>>
                <span class="mdc-list-item__text"><?=$option?></span>
              </li>
            <?php endforeach; ?>

          </ul>
          <button  class="mdc-button mdc-button--unelevated question__next" data-mdc-auto-init="MDCRipple" disabled>Next</button>
        </div>
      <?php endforeach; ?>

    </main>
  </div> 

  <script>
    const EMPTY_HASHTAG_DEFAULT_VALUE = 'New Hashtag';

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
          event.target.nextElementSibling.classList.add('question__next--activated');
          event.target.nextElementSibling.disabled = false;
        })
      })

      // make all button scroll to next question on finish
      applyBehaviourToAllElements('.question__next', function (element) {
        element.addEventListener('click', function (event) {
          if (event.target.classList.contains('question__next--activated')) {
            const nextElementSibling = event.target.parentNode.nextElementSibling;
            nextElementSibling && nextElementSibling.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }
        })
      })

      // change progressBar based on answered questions
      applyBehaviourToAllElements('#right-partition-item', function (element) {
        element.addEventListener('MDCList:action', function (event) {
          progressBar.progress = getFractionOfAnsweredQuestions();
        })
      })

      // detect quiz-details change based on hashtag changes
      applyBehaviourToAllElements('.hashtag-set', function (element) {
        element.addEventListener('MDCChip:removal', function (event) {
          handleQuizDescriptionChange();
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

    function handleQuizDescriptionChange(event) {
      const quizDescriptionTitle = document.querySelector('.quiz-description__title');
      const quizDescriptionDetails = document.querySelector('.quiz-description__details');
      const quizDescriptionTags = document.querySelector('.hashtag-set');

      const quizTitleInput = document.querySelector('#quiz-title');
      const quizDetailsInput = document.querySelector('#quiz-details');
      const quizTagsInput = document.querySelector('#quiz-tags');

      quizTitleInput.value = quizDescriptionTitle.innerText;
      quizDetailsInput.value = quizDescriptionDetails.innerText;
      quizTagsInput.value = quizDescriptionTags.MDCChipSet.chips.slice(1).map(chip => {
        return chip.root_.querySelector('.mdc-chip__text').innerText;
      }).join(',');

      // enable submit button
      document.querySelector('.quiz-description-form__actions').classList.remove('hidden');
    }

    function handleImageChange(event) {
      if (event && event.target && event.target.files && event.target.files[0].type.split('/')[0] != 'image') {
        // TODO: replace with dialog box
        alert('Not a valid image file');
        return false;
      }
      let url = URL.createObjectURL(event.target.files[0]);
      const leftPartition = document.querySelector('#left-partition-item');
      leftPartition.style.setProperty('background-image', 'url(' + url + ')');

      handleQuizDescriptionChange();
    }

    function handleHashtagInputMouseOut(event) {
      const hashtagInputEl = document.querySelector('.hashtag--add__input');
      if (hashtagInputEl.innerText.trim() == "")
        hashtagInputEl.innerText = EMPTY_HASHTAG_DEFAULT_VALUE;
    }

    function handleHashtagInputKeyDown(event) {
      if (event.key === 'Enter' || event.keyCode === 13) {
        event.preventDefault();   // prevent Enter from inserting <br />

        let hashtagText = event.target.innerText.toUpperCase().split(' ').join('_');
        hashtagText = hashtagText[0] === '#' ? hashtagText : '#' + hashtagText;
        event.target.innerText = EMPTY_HASHTAG_DEFAULT_VALUE;

        const chipTemplate = `
          <div class="mdc-chip hashtag">
            <div class="mdc-chip__text"></div>
            <i class="material-icons mdc-chip__icon mdc-chip__icon--trailing" role="button">cancel</i>
          </div>
        `;
        const chipEl = createElementFromHtml(chipTemplate);
        chipEl.querySelector('.mdc-chip__text').innerText = hashtagText;

        const chipSetEl = document.querySelector('.hashtag-set');
        const chipSet = chipSetEl.MDCChipSet;

        chipSetEl.appendChild(chipEl);
        chipSet.addChip(chipEl);

        handleQuizDescriptionChange();
      } else {
        // prevent propagation so, overall form is not changed
        event.stopPropagation();
      }
    }

    function createElementFromHtml(html) {
      var template = document.createElement('template');
      template.innerHTML = html.trim();
      return template.content.firstChild;
    }

    function refresh() {
      window.location.reload(false);
    }
  </script>

</body>
</html>