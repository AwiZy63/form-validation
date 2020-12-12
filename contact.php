<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

require __DIR__.'/vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__.'/templates');
$twig = new Environment($loader, [
    'debug' => true,
    'strict_variables' => true,
]);
$twig->addExtension(new DebugExtension());
$errors = [];

if ($_POST) {
    $emailMaxlenght = 190;
    if (empty($_POST['email'])) {
        $errors['email'] = 'Veuillez renseigner votre email';
    } elseif (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
        $errors['email'] = "Cet email n'est pas valide, merci d'en renseigner une valide";
    } elseif (strlen($_POST['email']) >= $emailMaxlenght) {
        $errors['email'] = "Votre email doit faire au maximum {$emailMaxlenght} caractères inclus";
    }

    $minLenght = 3;
    $subjectMaxlenght = 190;
    if (empty($_POST['subject'])) {
        $errors['subject'] = "Merci de renseigner l'objet de votre demande de contact";
    } elseif (strlen($_POST['subject']) < $minLenght || strlen($_POST['subject']) >= $subjectMaxlenght) {
        $errors['subject'] = "Votre objet doit faire au minimum {$minLenght} et au maximum {$subjectMaxlenght} caractères inclus";
    } elseif (preg_match('/<[^>]*>/', $_POST['subject']) === 1) {
        $errors['subject'] = "Vous ne pouvez pas utiliser de code HTML dans votre sujet";
    }
    
    $textareaMaxlenght = 1000;
    if (empty($_POST['message'])) {
        $errors['message'] = "Veuillez écrire votre message";
    } elseif (strlen($_POST['message']) < $minLenght || strlen($_POST['message']) >= $textareaMaxlenght) {
        $errors['message'] = "Votre message doit faire au minimum {$minLenght} et au maximum {$textareaMaxlenght} caractères inclus";
    } elseif (preg_match('/<[^>]*>/', $_POST['message']) === 1) {
        $errors['message'] = "Vous ne pouvez pas utiliser de code HTML dans votre message";
    }
}

echo $twig->render('contact.html.twig', [
    'errors' => $errors,
]);