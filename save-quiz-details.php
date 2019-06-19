<?php 

require('data-editor.php');

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
  echo 'Quiz successfully edited';
} else {
  echo 'Quiz edit failed';
}