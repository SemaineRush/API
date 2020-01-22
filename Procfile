web: heroku-php-apache2 public/
php bin/console d:d:d --force 
php bin/console d:d:c
php bin/console d:s:u --force 
"mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem