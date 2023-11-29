<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Bienvenue sur notre plateforme</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="jumbotron jumbotron-fluid">
        <div class="container">
          <h1 class="display-4">Bienvenue sur notre plateforme Istama-Travel !</h1>
          <p class="lead">Nous sommes ravis de vous accueillir en tant que nouvel étudiant.</p>
          <p>
            Voici quelques informations importantes pour commencer :
          </p>
          <ul class="list-unstyled">
            <li>Votre numéro d'étudiant : <strong>{{ $student->matricule }}</strong></li>
            <li>Votre adresse e-mail : <strong>{{ $student->User->email }}</strong></li>

          </ul>
          <p>
            Pour vous connecter à votre compte, veuillez utiliser votre adresse e-mail et votre mot de passe que vous avez défini lors de l'inscription.
          </p>
          <p>
            <a href="/" class="btn btn-primary btn-lg btn-block">Commencer</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0IlN0l5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
