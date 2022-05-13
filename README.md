# Cosas de Anime
Proyecto Anime realizado con la siguientes tecnologias, frameworks y librerias:

### Definición del esquema de cada miniproyecto

    PHP Nodejs

### Configuración del proyecto
------
* Enlace simbolico del contenido media en linux: sudo ln -s /media/jorge/9A3EB3183EB2EBFF/media/ /var/www/Anime/php

* Conectar postgresql con pgadmin4 

    -    sudo nano /etc/postgresql/12/main/postgresql.conf

            listen_addresses = '*' 

    -    sudo nano /etc/postgresql/12/main/pg_hba.conf

        -    local all postgres trust 
        
            Añadir al final del fichero
            
        -    host all all 127.0.0.1:128 trust 

    -   sudo passwd postgres
    -   su postgres
    -    psql

        -    alter user postgres with password 'postgres';
        
    -    sudo systemctl restart postgresql.service / sudo /etc/init.d/postgresql restart

*    Instalar pgadmin4 

    -    wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -

    -    echo "deb http://apt.postgresql.org/pub/repos/apt/ `lsb_release -cs`-pgdg main" |sudo tee  /etc/apt/sources.list.d/pgdg.list

    -    sudo apt-get update

    -    sudo apt-get install pgadmin4 pgadmin4-apache2

    -    http://localhost/pgadmin4

    -    user: postgres@localhost

    -    password: localhost
    
* Instalar potgresql 
    -   sudo apt-get update
    -   sudo apt-get install -y postgresql-12
    -   sudo systemctl status postgresql

### Modulos
Lista de Anime
-------
    Este modulo es que se utiliza para listar los animes
Blog
-------
    Este modulo es que se utiliza para listar los articulos del blog
