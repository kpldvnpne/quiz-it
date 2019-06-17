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

  echo "<p>Your score is: {$score} %</p>";

?>