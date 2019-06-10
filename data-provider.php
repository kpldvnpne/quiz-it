<?php

require 'database.php';

class QuizDataProvider {

	public function __construct() {
		die('Init function not allowed');
	}

	public static function getAllQuizData($quizId) {
		$quizData = self::getQuizDescription($quizId);

		$quizData['questions'] = self::getQuizQuestions($quizId);
		$quizData['tags'] = self::getQuizTags($quizId);
		return $quizData;
	}

	public static function getQuizDescription($quizId) {
		$pdo = Database::connect();
		$stmt = $pdo->prepare('SELECT title, details FROM quiz WHERE id = ?');
		$stmt->execute([$quizId]);
		$quizDescription = $stmt->fetch(PDO::FETCH_ASSOC);

		Database::disconnect();
		return $quizDescription;
	}

	public static function getQuizTags($quizId) {
		$pdo = Database::connect();
		$stmt = '
		SELECT h.tag as tag FROM
			quiz_hashtag as q_h
		INNER JOIN
			hashtag as h
		ON
			q_h.hashtag_id = h.id
		WHERE
			q_h.quiz_id = ?;
		';

		$stmt = $pdo->prepare($stmt);
		$stmt->execute([$quizId]);

		$tags = $stmt->fetchAll(PDO::FETCH_COLUMN);

		Database::disconnect();
		return $tags;
	}
	
	public static function getQuizQuestions($quizId) {
		$pdo = Database::connect();
		$stmt = '
		SELECT 
			q.id as questionId,
			q.title as questionTitle,
			q.type as questionType,
			c.value as option,
			c.correct as isCorrect
		FROM 
			question as q
		LEFT JOIN
			choice as c
		ON
			q.id = c.question_id
		WHERE
			q.quiz_id = ?;
		';

		$stmt = $pdo->prepare($stmt);
		$stmt->execute([$quizId]);

		$questions = [];
		$lastQuestionIndex = -1;
		$q_i = -1; 	// questions index
		$o_i = -1;	// options index

		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $question) {
			if ($question['questionId'] !== $lastQuestionIndex) {
				$lastQuestionIndex = $question['questionId'];
				$o_i = -1;
				$questions[++$q_i] = [
					'questionTitle' => $question['questionTitle'],
					'questionType' => $question['questionType'],
					'options' => [],
					'correctOptionsIndex' => []
				];
			}
			
			$questions[$q_i]['options'][] = $question['option'];
			if ($question['isCorrect']) {
				$questions[$q_i]['correctOptionsIndex'][] = ++$o_i;
			}
		}

		Database::disconnect();
		return $questions;
	}
}