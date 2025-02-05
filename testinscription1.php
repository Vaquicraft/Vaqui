<?php
 ob_start();
 session_start();
 
 // Si l'utilisateur est déjà enregistré, on le redirige vers l'accueil.
 
 if( isset($_SESSION['user'])!="" ){
  header("Location: accueil.php");
  
 }

// Pour le moment, il n'y a pas d'erreur.
 $error = false;

 if ( isset($_POST['btn-signup']) ) {
  
  // On efface les informations enregistrées par l'utilisateur pour éviter l'injection SQL.
  $name = trim($_POST['login']);
  $name = strip_tags($login);
  $name = htmlspecialchars($login);
  
  $password = trim($_POST['password']);
  $password = strip_tags($password);
  $password = htmlspecialchars($password);
  
  $passwordverif = trim($_POST['passwordverif']);
  $passwordverif = strip_tags($passwordverif);
  $passwordverif = htmlspecialchars($passwordverif);
  
  $mail = trim($_POST['mail']);
  $mail = strip_tags($mail);
  $mail = htmlspecialchars($mail);
  

  
  // Vérifications pour le pseudo
  if (empty($login)) {
   $error = true;
   $nameError = "Merci d'entrer un pseudo.";
  } else if (strlen($login) < 3) {
   $error = true;
   $nameError = "Votre pseudo doit avoir au moins 3 caractères.";
   
  // Vérifications pour le mot de passe
  if (empty($password)){
   $error = true;
   $passError = "Merci d'entrer un mot de passe.";
  } else if(strlen($password) < 6) {
   $error = true;
   $passError = "Votre mot de passe doit contenir au moins 6 caractères.";
  } 
  if (strlen($_POST['password']) > 3 AND $_POST['password'] == $_POST['passwordverif'])
	  $error = true;
  $nameError = "Les mots de passes ne correspondent pas, ou le mot de passe choisi est inférieur à 6 caractères.";
  }
  if ($_POST['login'] == $_POST['password'])
	  $error = true;
	  $nameError = "Votre mot de passe ne peut être identique à votre pseudo.";
 }
  
  // Vérifications pour l'adresse mail 
  if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['mail']))
		{
			
		}
	else
		{
			echo "Votre adresse e-mail est invalide !";
		}

   // Vérification de l'existence de l'email renseignée par l'utilisateur. 
   //$query = "SELECT mail FROM users WHERE mail='$mail'";
  // $result = mysql_query($query);
  // $count = mysql_num_rows($result);
  // if($count!=0){
   // $error = true;
    //$emailError = "L'adresse mail est déjà utilisée.";
   
	  
  

  
  
  // Hachage du mot de passe 
  $pass = hash('sha256', $pass);
  
  // S'il n'y a pas d'erreur, on continue l'inscription.
  if( !$error ) {
   
   $query = "INSERT INTO users(login,password,mail, date_inscription) VALUES('$login','$password','$mail', CURDATE())";
   $res = mysql_query($query);
    
   if ($res) {
    $errTyp = "success";
    $errMSG = "Successfully registered, you may login now";
    unset($login);
    unset($mail);
    unset($password);
   } else {
    $errTyp = "danger";
    $errMSG = "Something went wrong, try again later..."; 
   } 
    
  }
  
  
 
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Inscription</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="container">

 <div id="login-form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    
     <div class="col-md-12">
        
         <div class="form-group">
             <h2 class="">Inscription</h2>
            </div>
        
         <div class="form-group">
             <hr />
            </div>
            
            <?php
   if ( isset($errMSG) ) {
    
    ?>
    <div class="form-group">
             <div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
    <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
             </div>
                <?php
   }
   ?>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
             <input type="text" name="login" class="form-control" placeholder="Pseudo" maxlength="50" value="<?php echo $login ?>" />
                </div>
                <span class="text-danger"><?php echo $nameError; ?></span>
            </div>
			
			 <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
             <input type="password" name="password" class="form-control" placeholder="Mot de Passe" maxlength="50" />
                </div>
                <span class="text-danger"><?php echo $passError; ?></span>
            </div>
			
			 <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
             <input type="password" name="passwordverif" class="form-control" placeholder="Confirmation du mot de passe" maxlength="50" />
                </div>
                <span class="text-danger"><?php echo $passError; ?></span>
            </div>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
             <input type="email" name="mail" class="form-control" placeholder="Adresse Mail" maxlength="50" value="<?php echo $mail ?>" />
                </div>
                <span class="text-danger"><?php echo $emailError; ?></span>
            </div>
            
           
            
            <div class="form-group">
             <hr />
            </div>
            
            <div class="form-group">
             <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Valider</button>
            </div>
            
            <div class="form-group">
             <hr />
            </div>
            
           
        
        </div>
   
    </form>
    </div> 

</div>

</body>
</html>
<?php ob_end_flush(); ?>