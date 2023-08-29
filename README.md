# Project description

This project is a website for converting images into different formats.

## Supported formats

Input files | Output files
----------------- | ------------------
.jpg | .jpg
.png | .png
.bmp | .bmp
.webp | .webp
.jpeg | .pdf
&nbsp; | .ico

⚠ Not all output formats are supported for all input formats.

## Features

- Convert multiple images simultaneously.
- No data stored on server.

## Technologies used

- [ImageMagick](https://www.imagemagick.org/script/index.php)



# Installation and execution

## Prerequisites

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Installation

1. Clone this repo: `git clone https://github.com/arrighi-fabien/Convertion-Site.git`
2. Go to the directory: `cd {your-repo}`.
3. Build the Docker image and launch the container: `docker-compose up --build`
4. Open your browser and go to http://localhost:8080

# Usage

1. Select one or more files to convert.
2. Choose output format(s).
3. Click on the "Convert" button.
4. The converted files are automatically downloaded.