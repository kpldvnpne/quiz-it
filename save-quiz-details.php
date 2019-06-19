<?php 

require('data-editor.php');
require('utils.php');

function preprocess(&$quizData) {
  $quizData['quizTags'] = array_map(function ($tag) {
    if ($tag && $tag[0] == '#') {
      return substr($tag, 1);
    } else {
      return $tag;
    }
  }, explode(',', $quizData['quizTags']));
}

$quizId = $_GET['quizId'];
$quizData = $_POST;
preprocess($quizData);

if (QuizDataEditor::updateQuiz($quizId, $quizData)) {
  redirect('edit.php?' . http_build_query(['quizId' => $_GET['quizId']]), 301);
} else {
  // TODO: make error.php more informational
  redirect('error.php');
}