<?php
  require 'data-editor.php';

  function redirect($url, $statusCode = 303) {
    header('Location: ' . $url, true, $statusCode);
  }

  $quizId = isset($_GET["quizId"]) ? $_GET["quizId"] : 1;
  $questionId = (int) (isset($_GET['questionId']) ? $_GET['questionId']: 0);

  $questionData = $_POST;

  if (QuizDataEditor::updateQuestion($questionId, $questionData)) {
    redirect('edit.php?' . http_build_query(['quizId' => $_GET['quizId']]), 301);
  } else {
    redirect('edit-question.php?' . http_build_query($_GET), 301);
  }
?> 