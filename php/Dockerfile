FROM php:8.4.1-apache
LABEL maintainer="votre-email@example.com"
LABEL description="Image PHP avec Apache, Composer, Yarn et Node.js" 

# Fix Apache configuration 
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Mettre à jour et installer les dépendances nécessaires
#cron
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    locales apt-utils gnupg git libicu-dev g++ libpng-dev libzip-dev libonig-dev libxslt-dev unzip curl htop rsyslog apt-transport-https ca-certificates && \
    echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* 

# Désactiver le module imklog dans rsyslog.conf
#RUN sed -i '/^module(load="imklog")/s/^/#/' /etc/rsyslog.conf

# Configurer rsyslog pour les logs de cron 
#RUN echo "cron.* /var/log/cron.log" >> /etc/rsyslog.conf && \
#    echo "*.* /var/log/syslog" >> /etc/rsyslog.conf && \
#    service rsyslog restart 

# Installer Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php 
    
# Configurer et installer les extensions PHP 
RUN docker-php-ext-configure intl && \
    docker-php-ext-install pdo pdo_mysql mysqli gd opcache intl zip calendar dom mbstring xsl && \
    pecl install apcu && docker-php-ext-enable apcu
    
# Installer Yarn 
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \ 
    apt-get update && apt-get install -y yarn && \ 
    apt-get clean && \ 
    rm -rf /var/lib/apt/lists/* 

# Installer NVM, Node.js et npm 
ENV NVM_DIR /usr/local/nvm 
ENV NODE_VERSION 16 
RUN mkdir -p $NVM_DIR && \ 
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash && \
    . $NVM_DIR/nvm.sh && nvm install $NODE_VERSION && nvm use $NODE_VERSION && \ 
    ln -s $NVM_DIR/versions/node/v$NODE_VERSION/bin/node /usr/local/bin/node && \ 
    ln -s $NVM_DIR/versions/node/v$NODE_VERSION/bin/npm /usr/local/bin/npm && \ 
    ln -s $NVM_DIR/versions/node/v$NODE_VERSION/bin/npx /usr/local/bin/npx 

# Copy custom vhosts configuration 
COPY vhosts/vhosts.conf /etc/apache2/sites-available/000-default.conf



# Copier les tâches cron 
#COPY root /etc/cron.d/root 
# Ajouter une étape de débogage pour vérifier l'existence du fichier 
#RUN ls -l /etc/cron.d/ && cat /etc/cron.d/root 

# Ajuster les permissions du fichier cron 
#RUN chmod 644 /etc/cron.d/root 
# Créer et ajuster les permissions des fichiers de log 
#RUN touch /var/log/syslog && chmod 644 /var/log/syslog && touch /var/log/cron.log && chmod 644 /var/log/cron.log 
# Convertir les fichiers crontab en format UNIX 
#RUN apt-get update && \ 
#    apt-get install -y dos2unix && \ 
#    dos2unix /etc/cron.d/root 

COPY . /var/www/

# Activer le module rewrite d'Apache 
#RUN a2enmod rewrite

# Installer mod_ssl pour activer SSL
#RUN apt-get update && \
#    apt-get install -y libapache2-mod-ssl && \ # cette ligne la bug
#    a2enmod ssl && \ 
#    a2enmod rewrite

# Si dans docker compose pas besoin des ligne dessous
#COPY /etc/letsencrypt/live/kotolearn.com/fullchain.pem:/etc/ssl/certs/
#COPY /etc/letsencrypt/live/kotolearn.com/privkey.pem:/etc/ssl/private/

# Assurez-vous que les fichiers ont les bonnes permissions
RUN chown -R www-data:www-data /var/www && \ 
    find /var/www -type d -exec chmod 755 {} \; && \
    find /var/www -type f -exec chmod 644 {} \; && \
    chmod -R 755 /var/www/

# Valide la modification pour le hostname
RUN a2ensite 000-default.conf && a2enmod rewrite

EXPOSE 80

# Définir le répertoire de travail 
WORKDIR /var/www/ 
# Commande pour démarrer cron et Apache 
#service cron start && service rsyslog start &&
CMD  apache2-foreground