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

  return saveQuizImageFile();
}

function saveQuizImageFile() {
  $quizData['quizImageFilename'] = '';

  if (!isset($_FILES['quizImage'])) {
    return false;
  }

  $targetDir = __DIR__ . "/images/";
  $filename = basename($_FILES['quizImage']['name']);
  $extName = pathinfo($filename, PATHINFO_EXTENSION);
  $quizData['quizImageFilename'] = $filename ? $targetDir . random_filename(20, $targetDir, $extName) : '';

  if (!$filename) {
    return true;  // as no file is okay, but incorrect file is not okay
  }

  if ( $_FILES['quizImage']['size'] > 500000) {
    return false;
  }

  $imageFileType = pathinfo($filename, PATHINFO_EXTENSION);
  if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'gif') {
    return false;
  }

  if (!move_uploaded_file($_FILES['quizImage']['tmp_name'], $quizData['quizImageFilename'])) {
    echo 'here';
    return false;
  }

  return true;
}

$quizId = $_GET['quizId'];
$quizData = $_POST;

if (preprocess($quizData) && QuizDataEditor::updateQuiz($quizId, $quizData)) {
  redirect('edit.php?' . http_build_query(['quizId' => $_GET['quizId']]), 301);
} else {
  // TODO: make error.php more informational
  die();
  redirect('error.php');
}