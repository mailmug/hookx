$(brew --prefix php@8.2)/bin/phpize --clean                                                                    ✔   
$(brew --prefix php@8.2)/bin/phpize 
./configure --with-php-config=$(brew --prefix php@8.2)/bin/php-config   
make clean && make -j4  
$(brew --prefix php@8.2)/bin/php -d extension=./modules/hookx.so test.php    