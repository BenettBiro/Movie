<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Bioscoop: De Filmfanaten</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <script>
        let seconden = 0;

        function updateTimer() {
            seconden++;
            const minuten = Math.floor(seconden / 60);
            const restSeconden = seconden % 60;
            const secFormatted = String(restSeconden).padStart(2, '0');
            document.getElementById('timer').textContent = minuten + ':' + secFormatted;
        }

        setInterval(updateTimer, 1000);
    </script>

    <nav>
        <div class="nav-inner">
            <div class="nav-logo">De Filmfanaten</div>
            <div class="nav-right">
                <span class="timer-label">Tijd op pagina: <span id="timer">0:00</span></span>
                <a href="inloggen.php" class="nav-login">Inloggen</a>
                <!DOCTYPE html>
                <html>

                <body>
                </body>

                </html>
            </div>
        </div>
    </nav>

    <div>
        <div class="hero">
            <div class="hero-inner">
                <h1>Welkom bij Bioscoop De Filmfanaten</h1>
                <p>Ontdek onze uitgebreide Film collectie</p>
            </div>
        </div>

        <div class="main-content">
            <h2>Filmcollectie</h2>
            <?php include 'films.php'; ?>
        </div>

        <footer>
            <div class="footer-inner">
                <div>
                    <h4>Over Ons</h4>
                    <p>Bioscoop De Filmfanaten heeft een aantal fantastisch films voor uw ogen</p>
                </div>
                <div>
                    <h4>Snelle Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">films</a></li>
                        <li><a href="about.php">Over Ons</a></li>
                        <li><a href="inloggen.php">Inloggen</a></li>
                    </ul>
                </div>
                <div></div>
            </div>
        </footer>
    </div>

</body>

</html>