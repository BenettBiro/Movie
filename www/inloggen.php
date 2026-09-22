<?php ?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - Bioscoop</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="inloggen.css">
</head>

<body>

    <div class="login-wrapper">
        <div class="login-card">

            <h1>Inloggen</h1>

            <form action="inloggen_process.php" method="post">

                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="Email" name="Email" id="Email" placeholder="jouw@email.com">
                </div>

                <div class="form-group">
                    <label for="Password">Wachtwoord</label>
                    <input type="Password" name="Password" id="Password" placeholder="*******">
                </div>

                <button type="submit" name="submit" class="login-btn">
                    Inloggen
                </button>

            </form>

            <p class="login-footer">
                Nog geen account?
                <a href="registreren.php">Registreer hier</a>
            </p>

        </div>
    </div>

</body>

</html>