<?php 
    require 'data-provider.php';

    // TODO: make quizId random when not provided
    $quizId = isset($_GET["quizID"]) ? $_GET["quizId"] : 1;

    $quizData = QuizDataProvider::getAllQuizData($quizId);
?>