#!/bin/bash
set -e

# Import shared utilities (verbose flag, run functions).
source "$(pwd)/vendor/alexis-gss/laravel-frontend/utils.sh"

# Installation.

php artisan alexis-gss:console-message alert "Installing activity-logs" &&

    # Packages files.
    php artisan alexis-gss:console-message info "Publishing files" &&
    php artisan alexis-gss:install-activity-logs &&
    
    # Database migrations & seeders.
    php artisan alexis-gss:console-message info "Updating database" &&
    php artisan migrate:fresh --seed &&
    php artisan alexis-gss:console-message task "Database updated" &&

    # Asset compilation.
    php artisan alexis-gss:console-message info "Compiling assets" &&
    run_silent npm run prod &&
    php artisan alexis-gss:console-message task "Build complete" &&

    # Final cleanup.
    php artisan optimize:clear &&

    # Display installation completion message.
    php artisan alexis-gss:console-message success "Package <comment>activity-logs</comment> installed successfully."
