<?php

//connexion à la base de données
require_once '_connect.php';

$pdo = new PDO (DSN, USER, PASS);

//récupération de la base de données pour l'afficher sous forme de liste
$query = "SELECT * FROM friend";

$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_OBJ);

foreach($friends as $friend) {

    echo '<br>';
    echo $friend->firstname . ' ' . $friend->lastname;
    echo '</br>';

}

//Création du tableau d'erreurs et si tout est ok envoie des données dans la base de données
$errors= [];

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $friend = array_map('trim', $_POST);

    if(!isset($friend['firstname']) || empty($friend['firstname']))
        $errors[] = 'Le prénom est obligatoire';

    if(strlen($friend['firstname']) > 45)
        $errors[] = 'Le prénom est trop long';

    if(!isset($friend['lastname']) || empty($friend['lastname']))
        $errors[] = 'Le nom est obligatoire';  
        
    if(strlen($friend['lastname']) > 45)
        $errors[] = 'Le nom est trop long';

    if (empty($errors)) {
        $query = "INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname);";

        $statement = $pdo->prepare($query);
        $statement->bindValue(':firstname', $friend['firstname'], PDO::PARAM_STR);
        $statement->bindValue(':lastname', $friend['lastname'], PDO::PARAM_STR);
        $statement->execute();
        header('Location: /');
        die();
    }
}
     
if(count($errors) > 0) {
    echo '<ul>';
    foreach($errors as $error) {
        echo '<li>'.$error.'</li>';
    }
    echo '</ul>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire</title>
</head>
<body>
    <!-- Création du formulaire -->
    <form method="post">

        <h1>Create your friend</h1>

            <p>
                <label for="firstname">Firstname :</label>
                <input type="text" name="firstname" id="firstname">
            </p>

            <p>
                <label for="lastname">Lastname :</label>
                <input type="text" name="lastname" id="lastname">
            </p>

            <p>
                <button type="submit">Send</button>
            </p>

    </form>

</body>
</html>