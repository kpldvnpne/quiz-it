<?php

  require 'data-editor.php';

  $quizId = isset($_GET["quizId"]) ? $_GET["quizId"] : 1;
  $questionId = (int) (isset($_GET['questionId']) ? $_GET['questionId']: 0);

  $questionData = $_POST;

  print_r($questionData);

  if (QuizDataEditor::updateQuestion($questionId, $questionData)) {
    echo "Successfully edited title";
  } else {
    echo "Could not edit title";
  }

  // TODO: redirect to edit.php or edit-question.php according to error or not
?> 