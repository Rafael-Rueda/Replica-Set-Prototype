FROM php:8.2-apache

# Instalar dependências necessárias do sistema operacional
RUN apt update && apt install -y \
    curl \
    unzip \
    nano \
    libssl-dev \
    libcurl4-openssl-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Instalar a extensão MongoDB para PHP via PECL (conforme documentação)
RUN pecl install mongodb || true \
    && docker-php-ext-enable mongodb

# Instalar composer diretamente no diretório correto
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# Definir diretório de trabalho para executar composer install
WORKDIR /var/www/html

# Opcionalmente, ativar módulo rewrite do Apache
RUN a2enmod rewrite

# (Opcional - apenas para debug) conferir instalação do MongoDB
RUN php -m | grep mongodb

# Verificar versão do Composer (opcional, apenas debug visual durante build)
RUN composer --version

# Reiniciar Apache após configuração (boas práticas)
CMD ["apache2-foreground"]
