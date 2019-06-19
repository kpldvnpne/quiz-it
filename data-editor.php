<?php

require_once 'database.php';

class QuizDataEditor {
  
  public function __construct() {
    die('Init function not allowed');
  }

  public static function updateQuestion($quizId, $questionId, $questionData) {
    $succeeded = false;
    $pdo = Database::connect();

    $pdo->beginTransaction();
    if (self::updateQuestionTitle($quizId, $questionId, $questionData['questionTitle'])
        && self::updateQuestionOptions($questionId, $questionData['options'])) {
      $pdo->commit();
      $succeeded = true;
    } else {
      $pdo->rollBack();
      $succeeded = false;
    }

    Database::disconnect();
    return $succeeded;
  }

  public static function deleteQuestion($questionId) {
    $succeeded = false;
    $pdo = Database::connect();

    $pdo->beginTransaction();
    if (self::deleteAllQuestionOptions($questionId)
        && self::deleteQuestionEntry($questionId)) {
      $pdo->commit();
      $succeeded = true;
    } else {
      $pdo->rollBack();
      $succeeded= false;
    }

    Database::disconnect();
    return $succeeded;
  }

  private static function updateQuestionTitle($quizId, &$questionId, $questionTitle) {
    $succeeded = false;
    $pdo = Database::connect();

    $stmt = '
    UPDATE 
      question
    SET
      title=?
    WHERE
      id=?
    ';

    $stmt = $pdo->prepare($stmt);

    if ($stmt->execute([$questionTitle, $questionId]) && $stmt->rowCount() === 1) {
      $succeeded = true;
    } else {
      $stmt = '
      INSERT INTO
        question(title, quiz_id)
      VALUES
        (?, ?)
      ;
      ';

      $stmt = $pdo->prepare($stmt);
      if ($stmt->execute([$questionTitle, $quizId])) {
        $succeeded = true;
        $questionId = $pdo->lastInsertId();
      } else {
        $succeeded = false;
      }
    }

    // Database::disconnect();
    return $succeeded;
  }

  private static function updateQuestionOptions($questionId, $options) {
    if (!self::deleteAllQuestionOptions($questionId)) {
      return false;
    }

    $succeeded = false;
    $pdo = Database::connect();

    foreach ($options as $option) {
      $stmt = '
      INSERT INTO
        choice(value, correct, question_id)
      VALUES
        (:value, :correct, :questionId)
      ;
      ';

      $stmt = $pdo->prepare($stmt);

      if ($stmt->execute(['value'=> $option['value'], 'correct' => self::getChoice($option), 'questionId' => $questionId])) {
        $succeeded = true;
      }
    }

    // Database::disconnect();
    return $succeeded;
  }

  private static function deleteQuestionEntry($questionId) {
    $succeeded = false;
    $pdo = Database::connect();

    $stmt = '
    DELETE FROM
      question
    WHERE
      id=?
    ';

    $stmt = $pdo->prepare($stmt);

    if ($stmt->execute([$questionId])) {
      $succeeded = true;
    }

    // Database::disconnect();
    return $succeeded;
  }

  private static function deleteAllQuestionOptions($questionId) {
    $succeeded = false;
    $pdo = Database::connect();

    $stmt = '
    DELETE FROM
      choice
    WHERE
      question_id=?
    ';

    $stmt = $pdo->prepare($stmt);

    if ($stmt->execute([$questionId])) {
      $succeeded = true;
    }

    // Database::disconnect();
    return $succeeded;
  }

  private static function getChoice($option) {
    if (isset($option['isCorrect']))
      return true;
    else
      return false;
  }

}