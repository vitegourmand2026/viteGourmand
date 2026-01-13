<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../process/service_mail.php';

$message = '';
$messageType = '';

// POST EMAIL

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Adresse e-mail invalide.";
        $messageType = "error";
    } else {

        try {

// VERIF USER

            $stmt = $pdo->prepare("SELECT id, prenom FROM users WHERE email = ? AND role = 'user'");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

// CODE EMAIL

            $code = random_int(100000, 999999);

            if ($user) {
                $stmt = $pdo->prepare("UPDATE users SET reset_code = ? WHERE id = ?");
                $stmt->execute([$code, $user['id']]);

// EMAIL

                $subject = "Code de réinitialisation";
                $htmlContent = "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <div style='max-width:600px;margin:auto;'>
                        <h2 style='background:#2d5f3f;color:#fff;padding:15px;text-align:center;'>
                            Réinitialisation de mot de passe
                        </h2>
                        <div style='padding:20px;text-align:center;background:#f5f5f5;'>
                            <p>Bonjour {$user['prenom']},</p>
                            <p>Voici votre code :</p>
                            <h1 style='letter-spacing:8px;color:#2d5f3f;'>{$code}</h1>
                            <p>Ce code est valable pour une seule utilisation.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";

// API EMAIL

                sendEmailBrevo($email, $subject, $htmlContent);
            }

// MESSAGE SUCCES

            $_SESSION['success'] = "Si cette adresse existe, un code a été envoyé.";
            $_SESSION['reset_email'] = $email;

// REDIRECTION RESET MDP

            header('Location: /code/php/resetMDP.php');
            exit;

        } catch (PDOException $e) {
            $message = "Erreur technique. Veuillez réessayer.";
            $messageType = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="/ressources/icons/toque.png">
<link rel="stylesheet" href="/code/css/reinitMDP.css?v=<?php echo time(); ?>">
<title>Réinitialiser votre mot de passe</title>
</head>
<body>
<?php include "header.php"; ?>

<div>
    <img src="/ressources/ban.png" alt="image de plat du restaurant">
</div>

<!-- FORMULAIRE-->

<section class="form-container">
<form class="contact-form" method="POST">
    <div class="container">
        <h1>RÉINITIALISER VOTRE MOT DE PASSE</h1>
    </div>

    <?php if ($message): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <p>Entrez votre e-mail pour recevoir un code de vérification.</p>

    <div>
        <label for="email">Votre E-mail</label>
        <input type="email" id="email" name="email" placeholder="Votre e-mail" required>
    </div>

    <button type="submit" class="submit-btn">ENVOYER</button>
</form>
</section>
</body>
</html>
