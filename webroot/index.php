<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<title>Films</title>
		<link rel="stylesheet" href="https://cdn.concisecss.com/concise.min.css">
		<link rel="stylesheet" href="https://cdn.concisecss.com/concise-utils/concise-utils.min.css">
		<link rel="stylesheet" href="https://cdn.concisecss.com/concise-ui/concise-ui.min.css">
		<link rel="stylesheet" href="./css/style.css">
	</head>
	<body container>
		<h2 class="_bb1 _mts">Films</h2>
<?php
  if (isset($_GET['mes'])){      
	  $filtreMes=intval($_GET['mes']);
  }else{
	  $filtreMes=-1;
  }
  if (isset($_GET['page'])){
	  $page = intval($_GET['page']);
  }else{
	  $page=1;
  }
  ?>
		<!-- formulaire pour filtrer l'affichage suivant un réalisateur -->

		<form  method="GET" action="?page=1">
			Réalisateur : <select name="mes">
				<option value='-1'>Tous</option>
      <?php
       $connexion = mysqli_connect("dwarves.iut-fbleau.fr", "mouhamad", "mouhamad", "mouhamad");
      
       $Realquery = mysqli_query($connexion, "select DISTINCT nom from Artiste, Film where Film.idMes = Artiste.idArtiste");
     
       foreach ($Realquery as $realisateur) {
				 if($realisateur["nom"] == $_GET["mes"]){
         echo "<option value=\"" . $realisateur["nom"] ."\"selected='selected'>" . $realisateur["nom"] ."</option>";
				 }
				 else{
         echo "<option value=\"" . $realisateur["nom"] ."\">" . $realisateur["nom"] ."</option>";	 
				 }
       }
      ?>
			</select> 
			<button type="submit" class="btn">Chercher</button>
		</form>

		<!-- Table des films -->

		<table>
			<thead>
				<tr>
					<th>Titre</th>
					<th>Année</th>
					<th>Genre</th>
					<th>Réalisateur</th>
				</tr>
			</thead>
			<tbody>
  <?php
  $nombreLignes = 0;
        
  
  if (isset($_GET["mes"]) AND $_GET["mes"] != -1) {
    
		$query = mysqli_query($connexion, "SELECT * FROM Film, Artiste WHERE Film.idMes = Artiste.idArtiste and Artiste.nom = '" .  $_GET["mes"] ."' LIMIT " . ($page-1)*10 . ",10");

    foreach ($query as $film) {
      echo "<tr>";
      echo "<td><a href='fiche.php?film=".$film['idFilm']."'>"
        .$film['titre']
        ."</a></td><td>"
        .$film['annee']
        ."</td><td>"
        .$film['genre']
        ."</td><td>".$film['prenom']." ".$film['nom']
        ."</td>";
      echo "</tr>";
    }
  }
  else {
    $query = mysqli_query($connexion, "SELECT * FROM Film, Artiste WHERE Film.idMes = Artiste.idArtiste");
    foreach ($query as $film) {
        $nombreLignes++;
    }
    $query = mysqli_query($connexion, "SELECT * FROM Film, Artiste WHERE Film.idMes = Artiste.idArtiste LIMIT " . ($page-1)*10 . ",10");
    
    foreach ($query as $film) {
      echo "<tr>";
      echo "<td><a href='fiche.php?film=".$film['idFilm']."'>"
        .$film['titre']
        ."</a></td><td>"
        .$film['annee']
        ."</td><td>"
        .$film['genre']
        ."</td><td>".$film['prenom']." ".$film['nom']
        ."</td>";
      echo "</tr>";
    }
  }
       
  mysqli_close($connexion);
?>
			</tbody>
		</table>

		<!-- Barre de pagination -->
<?php
$nbpages = ceil($nombreLignes/10);
$prev=$page-1;
$next=$page+1;
if ($prev<1) $prev=1;
if ($next>$nbpages) $next=$nbpages;
?>
		<ul class="_mts button-group">
			<li>
				<a class="item" href="?<?php echo $filtreMes;?>&page=<?php echo $prev;?>">
					«
				</a>
			</li>

<?php
for($i=1;$i<=$nbpages;$i++){
	$class="";
	if ($i==$page) $class="-active";
	echo "<li class='item $class'><a href='?mes=$filtreMes&page=$i'>$i</a></li>";
}
?>

			<li>
				<a class="item" href="?mes=<?php echo $filtreMes;?>&page=<?php echo $next;?>">
					»
				</a>
			</li>
		</ul>
	</body>
</html>