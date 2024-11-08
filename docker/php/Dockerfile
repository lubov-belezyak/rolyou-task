# Используем образ PHP 5.6 с Apache
FROM php:5.6-apache

# Обновляем репозитории Debian на архивные
RUN sed -i 's|http://deb.debian.org/debian|http://archive.debian.org/debian|g' /etc/apt/sources.list && \
    sed -i '/stretch-updates/d' /etc/apt/sources.list && \
    sed -i '/security.debian.org/d' /etc/apt/sources.list && \
    echo "Acquire::Check-Valid-Until false;" > /etc/apt/apt.conf.d/99no-check-valid && \
    apt-get update -o Acquire::AllowInsecureRepositories=true

# Устанавливаем нужные зависимости
RUN apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    unzip \
    curl \
    git \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/freetype2 --with-jpeg-dir=/usr/include \
    && docker-php-ext-install gd pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копируем файлы проекта в контейнер
COPY . /var/www/html/


WORKDIR /var/www/html
# Копируем файл конфигурации Apache
COPY docker/apache/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Устанавливаем права для веб-сервера Apache
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
RUN mkdir -p /var/log/apache2 && chmod -R 755 /var/log/apache2

# Включаем mod_rewrite для Apache
RUN a2enmod rewrite

# Открываем порты для Apache (по умолчанию 80)
EXPOSE 80
