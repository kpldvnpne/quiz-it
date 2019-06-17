<?php

  require 'data-editor.php';

  $quizId = isset($_GET["quizId"]) ? $_GET["quizId"] : 1;
  $questionId = (int) (isset($_GET['questionId']) ? $_GET['questionId']: 0);

  $questionData = $_POST;

  if (QuizDataEditor::updateQuestion($questionId, $questionData)) {
    echo "Successfully edited question";
  } else {
    echo "Could not edit question";
  }

  // TODO: redirect to edit.php or edit-question.php according to error or not
?> 