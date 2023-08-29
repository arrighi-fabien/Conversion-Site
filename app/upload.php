<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    handleFileConversion();
    exit();
}
header("Location: /");
exit();


/**
 * Handles the conversion of the uploaded files.
 */
function handleFileConversion() {
    $json_allowed_formats = json_decode(file_get_contents('data/formats.json'), true);
    $allowed_formats = $json_allowed_formats["allowed_formats"];
    $quality_formats = array_keys($json_allowed_formats["quality_formats"]);
    $quality_formats = array_diff($quality_formats, ["defaultQuality", "maxQuality"]);

    if (!isset($_FILES["images"]["tmp_name"]) && !is_array($_FILES["images"]["tmp_name"])) {
        echo "Aucun fichier d'image n'a été téléchargé.<br>";
        return;
    }

    $multiple_files = count($_FILES["images"]["tmp_name"]) > 1;
    if ($multiple_files) {
        $zip = new ZipArchive();
        $zip_file_name = "converted_images.zip";
        if ($zip->open($zip_file_name, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            echo "Impossible de créer l'archive.<br>";
            return;
        }
    }

    foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
        $image_file_type = strtolower(pathinfo($_FILES["images"]["name"][$key], PATHINFO_EXTENSION));

        if ($image_file_type !== "pdf" && !getimagesize($tmp_name)) {
            echo "'" . $_FILES["images"]["name"][$key] . "' n'est pas une image.<br>";
            continue;
        }

        $format = $_POST["format-{$key}"];

        if (!in_array($image_file_type, array_keys($allowed_formats)) || !in_array($format, $allowed_formats[$image_file_type])) {
            echo "Le format d'image de '" . $_FILES["images"]["name"][$key] . "' n'est pas pris en charge.<br>";
            continue;
        }

        $quality = false;
        if (in_array($format, $quality_formats)) {
            $quality = checkIntIsInRange($_POST["quality-{$key}"], 0, 100);
        }

        $new_file = convertFile($tmp_name, $format, $quality, $key);

        if ($multiple_files) {
            $i = 1;
            while ($zip->statName($new_file[1])) {
                $new_file[1] = pathinfo($_FILES["images"]["name"][$key], PATHINFO_FILENAME) . " ($i).$format";
                $i++;
            }
            $zip->addFromString($new_file[1], (string)$new_file[0]);
        }
        else {
            header("Content-Type: image/$format");
            header("Content-Disposition: attachment; filename=\"$new_file[1]\"");
            header("Content-Length: " . strlen((string)$new_file[0]));
            echo $new_file[0];
        }
    }
    if ($multiple_files) {
        $zip->close();
        if (file_exists($zip_file_name)) {
            header("Content-Type: application/zip");
            header("Content-Disposition: attachment; filename=\"$zip_file_name\"");
            header("Content-Length: " . filesize($zip_file_name));
            readfile($zip_file_name);
            unlink($zip_file_name);
        }
    }
}

/**
 * Converts an image file to another format.
 * @param string $file The path to the image file.
 * @param string $format The format to convert the image to.
 * @param int|false $quality The quality of the converted image.
 * @param int $key The key of the file in the $_FILES array.
 * @return array An array containing the converted image and its name.
 */
function convertFile($file, $format, $quality, $key) {
    $image = new Imagick($file);
    $image->setImageFormat($format);
    $converted_file_name = pathinfo($_FILES["images"]["name"][$key], PATHINFO_FILENAME) . ".$format";

    if ($format === "ico") {
        $image->resizeImage(256, 256, Imagick::FILTER_LANCZOS, 1);
    }
    elseif ($format === "pdf") {
        $pdf = new Imagick();
        $pdf->newImage(595, 842, "white");
        $pdf->setImageFormat("pdf");
        if ($image->getImageWidth() > 595 && $image->getImageWidth() > $image->getImageHeight()) {
            $pdf->rotateImage("white", 90);
            if ($image->getImageWidth() > $pdf->getImageWidth()) {
                $image->resizeImage($pdf->getImageWidth(), 0, Imagick::FILTER_LANCZOS, 1);
            }
        }
        elseif ($image->getImageHeight() > 842) {
            $image->resizeImage(0, 842, Imagick::FILTER_LANCZOS, 1);
        }
        $pdf->compositeImage($image, Imagick::COMPOSITE_DEFAULT, (int)(($pdf->getImageWidth() - $image->getImageWidth()) / 2), (int)(($pdf->getImageHeight() - $image->getImageHeight()) / 2));
        return [$pdf, $converted_file_name];
    }
    if ($quality) {
        $image->setImageCompressionQuality($quality);
    }

    return [$image, $converted_file_name];
}

/**
 * Checks if a value is an integer between two values.
 * @param int $int The value to check.
 * @param int $min The minimum value.
 * @param int $max The maximum value.
 * @return int The checked value or the maximum value if the value is not an integer or if it is not between the two values.
 */
function checkIntIsInRange($int, $min, $max) {
    if (!filter_var($int, FILTER_VALIDATE_INT) || $int < $min || $int > $max) {
        return $max;
    }
    return $int;
}
