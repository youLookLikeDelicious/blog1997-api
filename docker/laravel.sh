#!/bin/bash
cd /var/www && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1 
