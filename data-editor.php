<?php

require_once 'database.php';

class QuizDataEditor {
  
  public function __construct() {
    die('Init function not allowed');
  }

  public static function updateQuiz($quizId, $quizData) {
    $succeeded = false;
    $pdo = Database::connect();

    $pdo->beginTransaction();
    if (self::updateQuizDescription($quizId, $quizData)
        && self::updateQuizTags($quizId, $quizData['quizTags'])) {
      $pdo->commit();
      $succeeded = true;
    } else {
      $pdo->rollBack();
      $succeeded = false;
    }

    Database::disconnect();
    return $succeeded;
  }

  private static function updateQuizDescription($quizId, $quizData) {
    $succeeded = false;
    $pdo = Database::connect();

    $params = ['id' => $quizId, 'title' => $quizData['quizTitle'], 'details' => $quizData['quizDetails']];
    if ($quizData['quizImageFilename']) {
      if (!self::deleteQuizImage($quizId)) {
        return false;
      }
      $stmt = '
      UPDATE
        quiz
      SET
        title = :title,
        details = :details,
        image_filename = :quizImageFilename
      WHERE
        id = :id;'
      ;
      $params['quizImageFilename'] = $quizData['quizImageFilename'];
    } else {
      $stmt = '
      UPDATE
        quiz
      SET
        title = :title,
        details = :details
      WHERE
        id = :id;'
      ;
    }

    $stmt = $pdo->prepare($stmt);
    if ($stmt->execute($params)) {
      $succeeded = true;
    }

    // Database::disconenct();
    return $succeeded;
  }

  private static function updateQuizTags($quizId, $quizTags) {
    if (!self::deleteAllQuizTags($quizId)) {
      return false;
    }

    $succeeded = true;
    $pdo = Database::connect();

    foreach($quizTags as $tag) {
      $stmt1 = 'SELECT id FROM hashtag WHERE tag = :tag;';
      $stmt1 = $pdo->prepare($stmt1);
      if ($stmt1->execute(['tag' => $tag]) && $stmt1->rowCount() > 0) {
        $hashtagId = $stmt1->fetch()['id'];
      } else {
        $stmt1 = 'INSERT INTO hashtag(tag) VALUES (:tag);';
        $stmt1 = $pdo->prepare($stmt1);

        if ($stmt1->execute(['tag' => $tag])) {
          $hashtagId = $pdo->lastInsertId();
        } else {
          $succeeded = false;
          break;
        }
      }

      $stmt2 = 'INSERT INTO quiz_hashtag VALUES (:quizId, :hashtagId);';
      $stmt2 = $pdo->prepare($stmt2);
      if (!$stmt2->execute(['quizId' => $quizId, 'hashtagId' => $hashtagId])) {
        $succeeded = false;
        break;
      }
    }

    // Database::disconnect();
    return $succeeded;
  }

  private static function deleteQuizImage($quizId) {
    $pdo = Database::connect();

    $stmt = $pdo->prepare('SELECT image_filename as quizImageFilename FROM quiz WHERE id = :quizId;');
    if ($stmt->execute(['quizId' => $quizId])) {
      $filename = $stmt->fetch(PDO::FETCH_ASSOC)['quizImageFilename'];
      if (file_exists($filename) && !unlink($filename)) {
        return false;
      }
    } else {
      return false;
    }
    return true;
  }

  private static function deleteAllQuizTags($quizId) {
    $succeeded = false;
    $pdo = Database::connect();

    $stmt = 'DELETE FROM quiz_hashtag WHERE quiz_id = ?;';
    $stmt = $pdo->prepare($stmt);
    if ($stmt->execute([$quizId])) {
      $succeeded = true;
    }

    // Database::disconnect();
    return $succeeded;
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

    $succeeded = true;
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

      if (!$stmt->execute(['value'=> $option['value'], 'correct' => self::getChoice($option), 'questionId' => $questionId])) {
        $succeeded = false;
        break;
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