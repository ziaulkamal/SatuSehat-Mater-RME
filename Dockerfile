# Gunakan image resmi PHP 8.1 sebagai dasar
FROM php:8.1-fpm

# Instal dependensi yang diperlukan
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Bersihkan cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instal ekstensi PHP yang diperlukan
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instal Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set direktori kerja
WORKDIR /var/www/html

# Copy kode aplikasi Laravel ke dalam container
COPY . /var/www/html

# Copy permissions dari aplikasi
COPY --chown=www-data:www-data . /var/www/html

# Ubah pengguna saat ini menjadi www
USER www-data

# Port yang perlu diexpose
EXPOSE 9000

# Jalankan PHP-FPM server
CMD ["php-fpm"]
