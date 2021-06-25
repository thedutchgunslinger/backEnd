<?php
include('process/db.php');

if (
    $_SERVER['REQUEST_METHOD'] == "POST"
    && isset($_POST["title"]) && $_POST["title"] != ""
) {
    $conn = db_connect();
    $title = $_POST['title'];

    // Count total files


    // Prepared statement
    $query = "INSERT INTO images (name,image,title) VALUES(?,?,?)";

    $statement = $conn->prepare($query);
    $targetDir = "upload/";
    $watermarkImagePath = 'process/watermark.png';


    // File upload path 
    $fileName = basename($_FILES["files"]["name"][0]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    $target_file = 'upload/' . $fileName;

    // Allow certain file formats 
    $allowTypes = array('jpg', 'png', 'jpeg');
    if (in_array($fileType, $allowTypes)) {
        // Upload file to the server 
        if (move_uploaded_file($_FILES["files"]["tmp_name"][0], $targetFilePath)) {
            // Load the stamp and the photo to apply the watermark to 
            $watermarkImg = imagecreatefrompng($watermarkImagePath);
            switch ($fileType) {
                case 'jpg':
                    $im = imagecreatefromjpeg($targetFilePath);
                    break;
                case 'jpeg':
                    $im = imagecreatefromjpeg($targetFilePath);
                    break;
                case 'png':
                    $im = imagecreatefrompng($targetFilePath);
                    break;
                default:
                    $im = imagecreatefromjpeg($targetFilePath);
            }

            // Set the margins for the watermark 
            $marge_right = 10;
            $marge_bottom = 10;

            // Get the height/width of the watermark image 
            $sx = imagesx($watermarkImg);
            $sy = imagesy($watermarkImg);

            // Copy the watermark image onto our photo using the margin offsets and  
            // the photo width to calculate the positioning of the watermark. 
            imagecopy($im, $watermarkImg, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($watermarkImg), imagesy($watermarkImg));

            // Save image and free memory 
            imagepng($im, $targetFilePath);
            imagedestroy($im);

            if (file_exists($targetFilePath)) {
                $statusMsg = "The image with watermark has been uploaded successfully.";
            } else {
                $statusMsg = "Image upload failed, please try again.";
            }
        } else {
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    } else {
        $statusMsg = 'Sorry, only JPG, JPEG, and PNG files are allowed to upload.';
    }




    // Upload file
    {

        // Execute query
        $statement->execute(
            array($fileName, $target_file, $title)
        );
    }
}
// }

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Admin Dashboard</title>
</head>

<body>
    <a href="process/logout.php" class="button">Logout</a>
    <h2 id="title">Admin Dashboard PJ's kleine wereld</h2>
    <form method='post' action='' enctype='multipart/form-data' class="formHandler">
        <h1>Upload Afbeelding</h1>
        <input type='file' name='files[]' id="customFileInput" onchange="showPreview(this);" accept="image/jpg, image/jpeg, image/png" />
        <img src="#" id="previewImg">
        <!-- <input type='file' name='files[]' multiple /> -->
        <input type="text" name="title" placeholder="Titel">
        <input type='submit' value='Submit' name='submit' class="button" />
        <!-- <button type="submit">Upload</button> -->
    </form>
    <form method='post' action='' enctype='multipart/form-data' class="formHandler2">
        <h1>Upload Youtube Video</h1>
        <input type="text" name="video" placeholder="Youtube id, exp: lz4YVXt8vjs" onkeyup="showPreviewVideo(this);">
        <iframe id="previewVideo" width="100%" height="400px" src="https://www.youtube.com/embed/NpEaa2P7qZI">
        </iframe>
        <!-- <input type='file' name='files[]' multiple /> -->
        <input type="text" name="titleVideo" placeholder="Titel">
        <input type='submit' value='Submit' name='submit' class="button" />
        <!-- <button type="submit">Upload</button> -->
    </form>
    <form method='post' action='' enctype='multipart/form-data' class="formHandler3">
        <h1>Upload Art work</h1>
        <input type='file' name='files[]' id="customFileInput" onchange="showPreviewArt(this);" accept="image/jpg, image/jpeg, image/png" multiple />
        <img src="#" id="previewArt">
        <!-- <input type='file' name='files[]' multiple /> -->
        <input type="text" name="Arttitle" placeholder="Titel">
        <input type='submit' value='Submit' name='submit' class="button" />
        <!-- <button type="submit">Upload</button> -->
    </form>

    <?php
    if (
        $_SERVER['REQUEST_METHOD'] == "POST"
        && isset($_POST["titleVideo"]) && $_POST["titleVideo"] != ""
        && isset($_POST["video"]) && $_POST["video"] != ""
    ) {
        //hier zetten we de POST data in een variabelle
        $titleVideo = $_POST['titleVideo'];
        $video = $_POST['video'];
        $video = "https://www.youtube.com/embed/" . $video;



        //hier roepen we de functie voor met de database te verbinden uit db.php
        $db = db_connect();

        //hier voegen we de gegevens toe aan de database, we geven eerst aan waar de gegevens moeten worden ingevuld en daarna wat de data moet zijn
        $sql = "INSERT INTO video ( title, url) VALUES ( :title,  :url)";

        //voordat we de data opsturen willen we eerste onze variabele in de query zetten
        $stmt = $query = $db->prepare($sql);
        $stmt->bindParam(':title', $titleVideo);
        $stmt->bindParam(':url', $video);
        //nu is het tijd om de query uit te voeren
        $query->execute();
    }

    if (
        $_SERVER['REQUEST_METHOD'] == "POST"
        && isset($_POST["Arttitle"]) && $_POST["Arttitle"] != ""
    ) {
        $conn = db_connect();
        $title = $_POST['Arttitle'];

        // Count total files


        // Prepared statement
        $query = "INSERT INTO art (name,image,title) VALUES(?,?,?)";

        $statement = $conn->prepare($query);
        $targetDir = "upload/";
        $watermarkImagePath = 'process/watermark.png';


        // File upload path 
        $fileName = basename($_FILES["files"]["name"][0]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $target_file = 'upload/' . $fileName;

        // Allow certain file formats 
        $allowTypes = array('jpg', 'png', 'jpeg');
        if (in_array($fileType, $allowTypes)) {
            // Upload file to the server 
            if (move_uploaded_file($_FILES["files"]["tmp_name"][0], $targetFilePath)) {
                // Load the stamp and the photo to apply the watermark to 
                $watermarkImg = imagecreatefrompng($watermarkImagePath);
                switch ($fileType) {
                    case 'jpg':
                        $im = imagecreatefromjpeg($targetFilePath);
                        break;
                    case 'jpeg':
                        $im = imagecreatefromjpeg($targetFilePath);
                        break;
                    case 'png':
                        $im = imagecreatefrompng($targetFilePath);
                        break;
                    default:
                        $im = imagecreatefromjpeg($targetFilePath);
                }

                // Set the margins for the watermark 
                $marge_right = 10;
                $marge_bottom = 10;

                // Get the height/width of the watermark image 
                $sx = imagesx($watermarkImg);
                $sy = imagesy($watermarkImg);

                // Copy the watermark image onto our photo using the margin offsets and  
                // the photo width to calculate the positioning of the watermark. 
                imagecopy($im, $watermarkImg, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($watermarkImg), imagesy($watermarkImg));

                // Save image and free memory 
                imagepng($im, $targetFilePath);
                imagedestroy($im);

                if (file_exists($targetFilePath)) {
                    $statusMsg = "The image with watermark has been uploaded successfully.";
                } else {
                    $statusMsg = "Image upload failed, please try again.";
                }
            } else {
                $statusMsg = "Sorry, there was an error uploading your file.";
            }
        } else {
            $statusMsg = 'Sorry, only JPG, JPEG, and PNG files are allowed to upload.';
        }




        // Upload file
        {

            // Execute query
            $statement->execute(
                array($fileName, $target_file, $title)
            );
        }
    }
    ?>

    <script src="js/main.js"></script>
    <script src="registerSW.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>