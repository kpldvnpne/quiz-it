<?php
  
  require 'data-provider.php';
  require 'constants.php';

  $quizId = isset($_GET["quizId"]) ? $_GET["quizId"] : 1;
  $quizData = QuizDataProvider::getAllQuizData($quizId);
  $questionIndex = (int) (isset($_GET['questionIndex']) ? $_GET['questionIndex']: 0);

  if (isset($_GET['new']) && $_GET['new'] == true) {
    $questionId = -10;
    $questionData = ['questionTitle' => '', 'options' => [], 'correctOptionIndices' => []];
  } else {
    // TODO: make edit.php not accessible when no id is given
    $questionId = (int) (isset($_GET['questionId']) ? $_GET['questionId']: 0);
    $questionData = $quizData['questions'][$questionIndex];
  }

  // TODO: throw error when no questionData found and show the error page, which displays automatically on based on throw statement

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
      /* --mdc-theme-secondary: white; */
      --mdc-theme-surface: blue;
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
      background-image: url("<?php echo $quizData['quizImageFilename'] ? $quizData['quizImageFilename'] : './assets/images/quiz-background-image-1.jpg'; ?>");
      background-size: cover;

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

    /* Question section */

    .question {
      position: relative;
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
      resize: none;
    }

    .question-option {
      --mdc-theme-primary: blue;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .question-option-item {
      display: flex;
      flex-direction: row;
      align-items: center;
      background-color: rgb(245, 249, 249);
      margin-bottom: 5px;
    }

    .question-option-item .mdc-text-field {
      flex: auto;
    }

    .question-option-item__add {
      width: 100%;
      border-style: dashed;
      background: none;
    }

    .question-actions {
      display: flex;
      justify-content: flex-end;
    }

    .question-actions > * {
      margin-left: 10px;
    }

    .question__save {
      margin-top: 0;
      height: 50px;
      font-weight: bold;
      --mdc-theme-primary: #00F78E;
      --mdc-theme-on-primary: black;
      color: black;
    }

    .question__save:disabled {
    --mdc-theme-primary: #B1AFBC;
    }

    .question__cancel {
      height: 50px;
      --mdc-theme-primary: red;
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
          <div class="mdc-linear-progress__buffer" style="background: #17EA95"></div>
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
        <strong>BY KAPILDEV NEUPANE</strong>
        <p>June 20, 2019</p>
      </footer>
    </aside>

    <main class="partition-item partition-item-6" id="right-partition-item">
      <form action="save-question.php?<?php echo http_build_query(['quizId' => $quizId, 'questionIndex' => $questionIndex, 'questionId' => $questionId]) ?>" method="post">

      <div class="question">
        <label class="question__number">Question <?=$questionIndex + 1?></label>
        <h2>
          <div class="mdc-text-field mdc-text-field--textarea" data-mdc-auto-init="MDCTextField">
            <textarea id="textarea" class="mdc-text-field__input question__title" name="questionTitle" rows="3" cols="40" oninput="makeSaveAccessible()"><?=$questionData['questionTitle']?></textarea>
            <div class="mdc-notched-outline">
              <div class="mdc-notched-outline__leading"></div>
              <div class="mdc-notched-outline__notch">
                <label for="textarea" class="mdc-floating-label">Question title</label>
              </div>
              <div class="mdc-notched-outline__trailing"></div>
            </div>
          </div>
        </h2>
        <ul class="question-option">

          <?php foreach($questionData['options'] as $optionIndex => $option): ?>
            <li class="question-option-item" <?=$optionIndex === 0 ? 'tabindex="0"': '';?> >

              <div class="mdc-form-field" data-mdc-auto-init="MDCFormField">
                <div class="mdc-checkbox" data-mdc-auto-init="MDCCheckbox">
                  <input type="checkbox" class="mdc-checkbox__native-control" 
                    name="options[<?php echo $optionIndex?>][isCorrect]" 
                    <?php echo in_array($optionIndex, $questionData['correctOptionIndices']) ? 'checked': ''; ?>
                    onchange="makeSaveAccessible()" />
                  <div class="mdc-checkbox__background">
                    <svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                      <path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                    </svg>
                    <div class="mdc-checkbox__mixedmark"></div>
                  </div>
                </div>
              </div>

              <div class="mdc-text-field"  data-mdc-auto-init="MDCTextField">
                <input type="text" id="my-text-field" class="mdc-text-field__input" name="options[<?php echo $optionIndex?>][value]" oninput="makeSaveAccessible()" value="<?=$option?>">
                <div class="mdc-line-ripple"></div>
              </div>
              <button type="button" class="mdc-icon-button material-icons" onclick="removeOption(event)">delete</button>
            </li>
          <?php endforeach; ?>

          <li class="question-option-item">
            <button type="button" data-mdc-auto-init="MDCRipple"
              class="mdc-button mdc-button--outlined material-icons question-option-item__add"
              onclick="addOption(event)"
              >add</button>
          </li>

        </ul>
        <div class="question-actions">
          <button class="mdc-button mdc-button--unelevated question__save" data-mdc-auto-init="MDCRipple"
            disabled>Save</button>
          <a href="<?php echo BASE_URL . '/edit.php?' . http_build_query(['quizId' => $quizId])?>" 
            class="mdc-button mdc-button--unelevated question__cancel"
            data-mdc-auto-init="MDCRipple">Cancel</a>
        </div>
      </div>

      </form>
    </main>
  </div> 

  <script>
    // Auto Init
    window.mdc.autoInit();

    function addOption(event) {
      const optionTemplate = `
        <li class="question-option-item">
          <div class="mdc-form-field" data-mdc-auto-init="MDCFormField">
            <div class="mdc-checkbox" data-mdc-auto-init="MDCCheckbox">
              <input type="checkbox" class="mdc-checkbox__native-control" name="options[${countOptions}][isCorrect]" onchange="makeSaveAccessible()" />
              <div class="mdc-checkbox__background">
                <svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                  <path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                </svg>
                <div class="mdc-checkbox__mixedmark"></div>
              </div>
            </div>
          </div>
          
          <div class="mdc-text-field" data-mdc-auto-init="MDCTextField">
            <input type="text" id="my-text-field" class="mdc-text-field__input" name="options[${countOptions}][value]" oninput="makeSaveAccessible()">
            <div class="mdc-line-ripple"></div>
          </div>
          <button class="mdc-icon-button material-icons" onclick="removeOption(event)">delete</button>
        </li>
      `;

      ++countOptions;

      let newOptionElem = createElementFromHtml(optionTemplate);
      event.target.parentNode.parentNode.insertBefore(newOptionElem, event.target.parentNode);

      makeSaveAccessible();
    }

    function removeOption(event) {
      event.target.parentNode.remove();

      makeSaveAccessible();
    }

    function makeSaveAccessible() {
      document.querySelector('.question__save').disabled = false;
    }

    function createElementFromHtml(html) {
      var template = document.createElement('template');
      template.innerHTML = html.trim();
      return template.content.firstChild;
    }

    let countOptions = <?php echo (int) count($questionData['options'])?>;

  </script>

</body>
</html>