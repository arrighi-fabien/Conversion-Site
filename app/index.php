<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Convert images to different formats.">
    <meta name="keywords" content="image, converter, convert, format, png, jpg, jpeg, gif, bmp, webp, ico, pdf">
    <link rel="icon" type="image/ico" href="favicon.ico" sizes="256x256">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
    <title>Image converter</title>
</head>

<body>
    <div class="page-title center-text">
        <h1>Image converter</h1>
        <h2>Convert images to different formats</h2>
    </div>
    <form action="upload.php" method="post" enctype="multipart/form-data" class="files-form">
        <label class="files-form__selector btn btn--primary btn--neon">
            <span class="files-form__selector__text">Select images</span>
            <span class="files-form__selector__icon">
                <svg>
                    <use href="assets/sprite.svg#folder"></use>
                </svg>
            </span>
            <?php
                $json_allowed_formats = json_decode(file_get_contents('data/formats.json'), true);
                $allowed_formats = implode(', .', array_keys($json_allowed_formats['allowed_formats']));
                $allowed_formats = '.' . $allowed_formats;
            ?>
            <input type="file" name="images[]" id="images" accept="<?= $allowed_formats ?>" multiple>
        </label>
        <p class="small-text center-text">20 files maximum at once with 20 MB maximum per file</p>
        <div class="file-info">
            <table>
                <tbody>

                </tbody>
            </table>
        </div>
        <input id="submit-btn" class="btn btn--secondary" type="submit" value="Convert" style="display: none;">
    </form>
    <div class="card-grid">
        <div class="card-info">
            <div class="card-info__icon">
                <svg fill="url(#gradient)">
                    <use href="assets/sprite.svg#fingerprint"></use>
                </svg>
                <svg fill="url(#gradient)">
                    <use href="assets/sprite.svg#fingerprint"></use>
                </svg>
            </div>
            <div class="card-info__text">
                <h3>No data stored</h3>
                <p>Your files are never saved on the server. Your files stay your property.</p>
            </div>
        </div>
        <div class="card-info">
            <div class="card-info__icon">
                <svg stroke="url(#gradient)">
                    <use href="assets/sprite.svg#cloud"></use>
                </svg>
                <svg stroke="url(#gradient)">
                    <use href="assets/sprite.svg#cloud"></use>
                </svg>
            </div>
            <div class="card-info__text">
                <h3>All the work on our server</h3>
                <p>All the work is done for you. Your machine has nothing to do.</p>
            </div>
        </div>
        <div class="card-info">
            <div class="card-info__icon">
                <svg stroke="url(#gradient)">
                    <use href="assets/sprite.svg#flash"></use>
                </svg>
                <svg stroke="url(#gradient)">
                    <use href="assets/sprite.svg#flash"></use>
                </svg>
            </div>
            <div class="card-info__text">
                <h3>Fast</h3>
                <p>Just upload the files, choose the output extension and wait a few seconds.</p>
            </div>
        </div>
        <div class="card-info">
            <div class="card-info__icon">
                <svg stroke="url(#gradient)">
                    <use href="assets/sprite.svg#gear"></use>
                </svg>
                <svg stroke="url(#gradient)">
                    <use href="assets/sprite.svg#gear"></use>
                </svg>
            </div>
            <div class="card-info__text">
                <h3>Multiple options</h3>
                <p>Numerous extensions are available, as well as quality options.</p>
            </div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg">
            <linearGradient id="gradient" x2="1" y2="1">
                <stop stop-color="#5100EB" stop-opacity="0.3" />
                <stop offset="100%" stop-color="#5100EB" />
            </linearGradient>
        </svg>
    </div>
</body>

</html>