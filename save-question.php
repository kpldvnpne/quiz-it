<?php

  require 'data-editor.php';

  $quizId = isset($_GET["quizId"]) ? $_GET["quizId"] : 1;
  $questionId = (int) (isset($_GET['questionId']) ? $_GET['questionId']: 0);

  echo ($questionId);

  $questionData = $_POST;

  if (QuizDataEditor::updateQuestion($questionId, $questionData)) {
    echo "Successfully edited title";
  } else {
    echo "Could not edit title";
  }
?> 