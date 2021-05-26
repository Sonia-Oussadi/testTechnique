<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
<?
echo '<nav class="navbar navbar-expand-sm bg-light navbar-light"><ul class ="navbar-nav"><li class="nav-item"><a class="nav-link" href = "formulaire.php">Accueil</a></li></ul></nav>';

if(!empty($_GET['ide']) && isset($_GET['ide'])){
    $ide = $_GET['ide'];
    $moyenne=0;
    $coefficient = 0;
    $somme=0;
    try
   {
        $bdd = new PDO('mysql:host=localhost;dbname=gestion_de_notes;charset=utf8', 'root', 'root',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
   }
   catch (Exception $e)
   {
        die('Erreur : ' . $e->getMessage());
   }
   //récuperer les notes de l'etudiant selectionné pour le calcul de moyenne 
    $req1 = "SELECT * FROM note WHERE ide=?";
    $result1 = $bdd->prepare($req1);
    $result1->execute([$ide]);
    foreach($result1 as $row){
        $req2 = "SELECT coefficient FROM matiere INNER JOIN note ON matiere.idm=note.idm WHERE matiere.idm=?";
        $result2 = $bdd->prepare($req2);
        $result2->execute([$row['idm']]);
        if($result2->rowCount()>0){
            $row2 = $result2->fetch();
            $coefficient = $row2['coefficient'];
            $somme=$somme+$coefficient;
            $moyenne = $moyenne+$row['valeur']*$coefficient;
        }

    }
    $moyenne = $moyenne/$somme;
    // mise à jour de la moyenne de l'etudiant
    $req3 = 'UPDATE etudiant SET moyenne=? WHERE ide=?';
	$result3 = $bdd->prepare($req3);
	$result3->execute(array($moyenne,$ide));
    if($result3->rowCount()>0){
    ?>
    <script>alert("la moyenne est mise à jour")</script>
    <?
    }else{
    ?>
        <script>alert("erreur de mise à jour")</script>
    <?
    }

   
}
// l'affichage de toutes les moyennes des etudiants qui ont un bulletin remplis 
$req4 = "SELECT * FROM etudiant";
$result4 = $bdd->prepare($req4);
$result4->execute();
echo '<table class="table table-borderless table-dark">
<thead>
    <tr>
        <th scope="col">Nom</th>
        <th scope="col">prenom</th>
        <th scope="col">numero</th>
        <th scope="col">moyenne</th>
        <th scope="col">statut</th>

    </tr>
</thead>';
foreach($result4 as $row2){
    if($row2['statut']=="remplis"){
        echo '<tbody><tr><td>'.$row2['nom'].'</td><td>'.$row2['prenom'].'</td><td>'.$row2['numero'].'</td><td>'.$row2['moyenne'].'</td><td>'.$row2['statut'].'</td></tr></tbody>';
    }
}
echo "</table>";

?>
    
</body>
</html>