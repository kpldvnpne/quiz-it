<?php

require 'database.php';

class QuizDataEditor {
  
  public function __construct() {
    die('Init function not allowed');
  }

  public static function updateQuestion($questionId, $questionData) {
    return self::updateQuestionTitle($questionId, $questionData['questionTitle']);
  }

  private static function updateQuestionTitle($questionId, $questionTitle) {
    $succeeded = false;
    $pdo = Database::connect();

    $stmt = '
    UPDATE 
      question
    WHERE
      id=?
    SET
      title=?
    ';

    $stmt = $pdo->prepare($stmt);

    if ($stmt->execute([$questionId, $questionTitle])) {
      $succeeded = true;
    }

    Database::disconnect();
    return $succeeded;
  }

}