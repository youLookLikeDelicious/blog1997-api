#!/bin/bash
* * * * * cd /var/www && php artisan schedule:run >> /dev/null 2>&1 
