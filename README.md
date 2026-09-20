# DOCKER TEMPLATE

## BASIS-template

Je kunt deze template gebruiken om meerdere repositories aan te maken en tijdens de ontwikkeling van je webapp de standaardmicroservices tot je beschikking te hebben.

## Starten

**Volg deze stappen om de template voor je project te gebruiken.**

### Template gebruiken

1. Klik op de groene knop met de tekst `Use this template`.
2. Kies `Create a new repository`.
3. Geef je repository een passende naam, bijvoorbeeld `myfirstproject`.
4. Clone het project naar je ontwikkelmachine.

### Je project instellen

1. Pas het `.env`-bestand aan zodat de databasenaam overeenkomt met die van je project.

### Starten

1. Start je services/containers door een `terminal` te openen in de map van je project.
2. Voer het volgende commando uit: `docker compose up`

## Beschikbare applicaties

### Webapp

Ga naar http://localhost om je webapp te openen.

### PhpMyAdmin

Ga naar http://localhost:8000 om de gebruikersinterface van je MySQL-database, PhpMyAdmin, te openen.

### Mailhog

Ga naar http://localhost:8025 om de mailgebruikersinterface van Mailhog te openen.
