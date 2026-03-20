# Traits

Ce package met à disposition plusieurs traits à des fins totalement différentes mais nécessaires dans chacun de nos projets.

## HasActivityLog

[`HasActivityLog.php`](./../src/Traits/Enums/HasActivityLog.php): trait à appliquer sur n'importe quel modèle pour écouter ses évènements.

| Méthode                | Description                                                  |
|------------------------|--------------------------------------------------------------|
| `bootHasActivityLog()` | Écoute les évènements d'un modèle: created, updated, deleted |
| `activityLogs()`       | Définit la relation liée au modèle ActivityLog               |
