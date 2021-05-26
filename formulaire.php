<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=<, initial-scale=1.0">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
    <h1 style="text-align:center"> Gestionnaire de notes </h2>
<? 
echo '<nav class="navbar navbar-expand-sm bg-light navbar-light"><ul class ="navbar-nav"><li class="nav-item"><a class="nav-link" href = "moyenne.php">moyennes</a></li></ul></nav>';
   try
   {
        $bdd = new PDO('mysql:host=localhost;dbname=gestion_de_notes;charset=utf8', 'root', 'root',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
   }
   catch (Exception $e)
   {
        die('Erreur : ' . $e->getMessage());
   }

// l'affichage de la table etdiant avec un lien pour la saisie des notes 
$req1 = $bdd->prepare('SELECT * FROM etudiant');
$req1->execute();
echo '<table class="table table-borderless table-dark">
<thead>
	<tr>
		<th scope="col">Nom</th>
		<th scope="col">prenom</th>
		<th scope="col">numero</th>
        <th scope="col">action</th>
	</tr>
</thead>';
foreach($req1 as $row){
    if($row['statut']=="en-attente"){
        echo '<tbody><tr><td>'.$row['nom'].'</td><td>'.$row['prenom'].'</td><td>'.$row['numero'].'</td><td><a href="formulaire.php?ide='.$row['ide'].'">remplir le bultin</a></td></tr></tbody>'; 
    }
   
}
echo "</table>";
// récuperer l'identifiant de l'etudiant selectionner et proposition de formulaire pour le remplissage de notes 
if(isset($_GET['ide']) && !empty($_GET['ide'])){
    $ide = $_GET['ide'];
    $min = 0;
    $max = 20;
    // récuperer les différents intitulés des matières
    $req2 = $bdd->prepare('SELECT * FROM matiere');
    $req2->execute();

    echo'<h3 class="h3" style="text-align:center">formulaire de notes</h3><form method = "POST" action ="formulaire.php?ide='.$ide.'" style="margin-left:45%;">';

    foreach ($req2 as $row1) 
	{
        echo '<label for id ='.$row1['intitule'].'>'.$row1['intitule'].'</label>
	    <input type = "number" name = '.$row1['intitule'].' id ='.$row1['intitule'].' min='.$min.' max='.$max.'><br>';
	}
    echo '<input type = "submit" value ="valider" ></from>';  
    $req2->closeCursor();
}

if(!empty($_POST) && isset($_GET['ide']) && !empty($_GET['ide'])){
    $ide = $_GET['ide'];
    foreach ($_POST as $key => $value) {
        $req3 = "SELECT idm FROM matiere WHERE intitule=?";
		$result3 = $bdd->prepare($req3);
		$result3->execute([$key]);
        if($result3->rowCount()>0)
		{
			$row = $result3->fetch();
            //enregistrement des notes dans la table note
            $req4="INSERT INTO note (idm,ide,valeur) VALUES(?,?,?)";
		    $result4 = $bdd->prepare($req4);
		    $result4->execute(array($row['idm'],$ide,$value));
		    if($result4->rowCount()<= 0)
		    {
            ?>
                <script> alert("la saisie de notes a echouée");</script>
            <?
		    }
		}
	}
    //mise à jour du statut correspondant au bulletin de  l'etudiant
    $req5 = 'UPDATE etudiant SET statut="remplis" WHERE ide=?';
	$result5 = $bdd->prepare($req5);
	$result5->execute([$ide]);
	if($result5->rowCount()<=0)
	{
    ?>
        <script> alert("erreur me mise à jour de statut");</script>
	<?
	}
    echo '<h3 class="h3">le bulletin est bien remplis</h3>
    <a class="btn btn-danger" role="button" href="moyenne.php?ide='.$ide.'">calculer la moyenne</a>';

}   
?> 
</body>
</html>