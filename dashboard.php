<?php
include('process/db.php');

if (
    $_SERVER['REQUEST_METHOD'] == "POST"
    && isset($_POST["title"]) && $_POST["title"] != ""
) {
    $conn = db_connect();
    $title = $_POST['title'];
    // Count total files
    $countfiles = count($_FILES['files']['name']);

    // Prepared statement
    $query = "INSERT INTO images (name,image,title) VALUES(?,?,?)";

    $statement = $conn->prepare($query);

    // Loop all files
    for ($i = 0; $i < $countfiles; $i++) {

        // File name
        $filename = $_FILES['files']['name'][$i];

        // Location
        $target_file = 'upload/' . $filename;

        // file extension
        $file_extension = pathinfo(
            $target_file,
            PATHINFO_EXTENSION
        );

        $file_extension = strtolower($file_extension);

        // Valid image extension
        $valid_extension = array("png", "jpeg", "jpg");

        if (in_array($file_extension, $valid_extension)) {

            // Upload file
            if (move_uploaded_file(
                $_FILES['files']['tmp_name'][$i],
                $target_file
            )) {

                // Execute query
                $statement->execute(
                    array($filename, $target_file, $title)
                );
            }
        }
    }

    echo "File upload successfully";
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Admin Dashboard</title>
</head>

<body>
    <a href="process/logout.php" class="button">Logout</a>
    <h2 id="title">Admin Dashboard PJ's kleine wereld</h2>
    <form method='post' action='' enctype='multipart/form-data' class="formHandler">
        <h1>Upload Afbeelding</h1>
        <input type='file' name='files[]' id="customFileInput" onchange="showPreview(this);" accept="image/jpg, image/jpeg, image/png" multiple />
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
    ?>

    <script src="js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>