<?php 
  require 'data-provider.php';

  // TODO: make quizId random when not provided
  $quizId = isset($_GET["quizID"]) ? $_GET["quizId"] : 1;

  $quizData = QuizDataProvider::getAllQuizData($quizId);

  $answerData = $_POST['question'];

  $totalQuestions = count($quizData['questions']);
  $correctlyAnsweredQuestions = 0;

  foreach($quizData['questions'] as $questionIndex => $question) {
    if (array($answerData[$questionIndex]) == $question['correctOptionIndices']) {
      ++$correctlyAnsweredQuestions;
    }
  }

  $score = $correctlyAnsweredQuestions / $totalQuestions * 100;

  

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

  <!-- CSS and JS for Fontawesome -->
  <link href="./fontawesome/css/all.css" rel="stylesheet">
  <script src="./fontawesome/js/all.js"></script>

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

    .mdc-linear-progress__buffer {
      opacity: 0.5;
    }

    /* Result section */
    .result {
      margin-top: 20%;
      margin-left: 20%;
      margin-bottom: 15%;
      text-align: center;

      color: rgb(85,88,95);
    }

    .result-medal {
      color: rgb(59, 167, 213);
      font-size: 30px;
    }

    .result-score {
      font-size: 70px;
      font-weight: 900;
    }

    .result-remark {
      font-size: 40px;
      font-weight: 400px;
    }

    .result-replay {
      --mdc-theme-on-primary: black;
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
        <strong>BY KAPILDEV NEUPANE</strong>
        <p>June 20, 2019</p>
      </footer>
    </aside>

    <main class="partition-item partition-item-6" id="right-partition-item">
      <div class="result">
        <p><i class="fas fa-medal result-medal"></i></p>

        <span class="result-score"><?php echo $score;?>%</span>
        <br />
        
        <?php 
          if ($score > 70) {
            $remark = "Congratulations!";
          } elseif ($score > 50) {
            $remark = "Great!";
          } elseif ($score > 40) {
            $remark = "Good!";
          } else {
            $remark = "Better luck next time";
          }
        ?>

        <span class="result-remark"><?php echo $remark; ?></span>
        <p><?php echo "You got {$correctlyAnsweredQuestions} out of {$totalQuestions} questions";?></p>

        <a href="index.php" class="mdc-button mdc-button--unelevated result-replay" data-mdc-auto-init="MDCRipple">Replay</a>
      </div>
      
    </main>
  </div> 

  <script>
    // Auto Init
    window.mdc.autoInit();

  </script>

</body>
</html>