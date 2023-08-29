# Use an official PHP runtime as the base image
FROM php:8.1-apache

# Set the ServerName directive to suppress the warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install build dependencies
RUN apt-get update && apt-get install -y build-essential libtool autoconf automake \
    libjpeg-dev libpng-dev libtiff-dev zlib1g-dev libfontconfig1-dev \
    libglib2.0-dev libx11-dev libxext-dev libxt-dev libxml2-dev \
    libzip-dev

# Download and build ImageMagick (replace URL with your desired version)
RUN curl -LO https://imagemagick.org/archive/ImageMagick-7.1.1-15.tar.gz && \
    tar xvzf ImageMagick-7.1.1-15.tar.gz && \
    cd ImageMagick-7.1.1-15 && \
    ./configure && make && make install && \
    cd .. && rm -rf ImageMagick-7.1.1-15*

# Install Imagick PHP extension linked to ImageMagick
RUN pecl install imagick-3.7.0 && docker-php-ext-enable imagick

# Change upload file size limit
RUN echo "upload_max_filesize = 50M" > /usr/local/etc/php/conf.d/upload_max_filesize.ini

# Enable the Zip extension
RUN docker-php-ext-install zip

# Set the working directory
WORKDIR /var/www/html

# Copy your PHP files to the container
COPY app/ .

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]