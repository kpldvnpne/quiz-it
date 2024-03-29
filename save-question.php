<?php
  require 'data-editor.php';
  require 'utils.php';

  $quizId = isset($_GET["quizId"]) ? $_GET["quizId"] : 1;
  $questionId = (int) (isset($_GET['questionId']) ? $_GET['questionId']: 0);

  $questionData = $_POST;

  if (QuizDataEditor::updateQuestion($quizId, $questionId, $questionData)) {
    redirect('edit.php?' . http_build_query(['quizId' => $_GET['quizId']]), 301);
  } else {
    redirect('edit-question.php?' . http_build_query($_GET), 301);
  }
?> 