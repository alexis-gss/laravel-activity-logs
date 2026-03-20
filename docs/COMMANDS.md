# Commandes

Ce package met à disposition plusieurs commandes à des fins totalement différentes mais nécessaires dans chacun de nos projets.

> **Note:** vous avez aussi la possibilité de lister la totalité des commandes artisan **disponibles** et **visibles** dans le projet via la commande `php artisan`, une section `alexis-gss` sera dédiée aux commandes ajoutées par nos packages.

## alexis-gss:install-activity-logs

```bash
php artisan alexis-gss:install-activity-logs
```

Commande d'installation qui nettoie et publie tous les fichiers nécessaires au bon fonctionnement du package activity-logs dans un projet Laravel.

### Étapes d'installation

| Étape                           | Description                                                                         |
|---------------------------------|-------------------------------------------------------------------------------------|
| `Resources files (directories)` | Copie les répertoires de ressources (pages React)                                   |
| `Resources files (files)`       | Copie les fichiers utilitaires TypeScript (`activity-logs.d.ts`, `SidebarLink.tsx`) |

### Comportement

- Chaque étape est indépendante: si l'une échoue, les suivantes continuent et l'erreur est affichée en rouge,
- Les traductions sont ajoutées au registre commun `TranslationRegistry`.