# spip-remix/bridge-http-server

## Installation

```bash
composer require spip-remix/bridge-http-server
```

## Rational

Un RequestHandler reçoit une ServerRequest en entrée et renvoie une Response.
Il reçoit un payload de type ServerRequest et change le type du payload en Response.
C'est un Stage. Comme un Pipeline, c'est un Stage,
le RequestHandler de SPIP, c'est le Pipeline pour PSR-15

Les Middlewares, ce sont les stages (pipeline/middleware-interface, ou pipeline/rule-interface)
Le Payload, c'est la ServerRequest
Le pipeline s'interrompt quand un middleware renvoie une Response
Quand tout les middlewares sont passés sans interruption, HttpPipeline appel un RequestHandler final qui renvoie une réponse par défaut

## Usage

- HttpPipeline
- Pour les tests, SpipFrameworkHandler renvoie la réponse par défaut.

## Exemple

- Logger
- Error
- SecurityScreen
  - un truc
  - un autre truc
- Ajax
- PreRouting
  - action
  - espace privé
  - espace public
  - debug mode (-dev)
- UserLand
  - ...
- Router
