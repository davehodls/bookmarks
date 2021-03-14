<?php
$timestamp = time();
$submittedTimeStamp = !empty($_POST['timestamp']) ? $_POST['timestamp'] : null;
$errors = array();
if (!empty($_POST)) {
    //die("<pre>POST: " . print_r($_POST, true));
    //die("<pre>SERVER: " . print_r($_SERVER, true));
    //die("<pre>GLOBALS: " . print_r($GLOBALS, true));
    if (empty($_FILES['bookmarks'])) {
        $errors[] = "Bookmarks file wasn't uploaded.";
    } else {
        echo "<pre>FILE: " . print_r($_FILES, true) . "</pre>";
        //FILE: Array
        //(
        //    [bookmarks] => Array
        //    (
        //        [name] => bookmarks old.html
        //        [type] => text/html
        //        [tmp_name] => C:\xampp\tmp\php5968.tmp
        //        [error] => 0
        //        [size] => 2120
        //    )
        //)
        $bookmarks = $_FILES['bookmarks'];
        // check the file extension
        $fileNameInfo = explode('.', $bookmarks['name']);
        echo "<pre>fileNameInfo: " . print_r($fileNameInfo, true) . "</pre>";
        $fileExtension = end($fileNameInfo);
        if ($fileExtension != 'html') {
            $errors[] = "File extension is not .html.";
        } else {
            echo "fileExtension = $fileExtension<br>";
            //$fileContents = file_get_contents();
            //die("fileContents: $fileContents");
            // parse the file as an HTML DOMDocument
            $dom = new DOMDocument();
            if ($dom->loadHTMLFile($bookmarks['tmp_name'], LIBXML_BIGLINES)) {
                // try to parse the loaded DOM file
            } else {
                $errors[] = "Unable to load HTML file to DOMDocument.";
            }
            // save to a file hopefully with some decent formatting
            $domFixedFileName = "{$fileNameInfo[0]}.fixed.{$fileExtension}";
            if ($dom->saveHTMLFile($domFixedFileName)) {
                die("saved new DOM file name $domFixedFileName");
            } else {
                die("unable to save new DOM file name $domFixedFileName");
            }
        }
    }
}
?>
<?php echo !empty($_POST['timestamp']) ? "<p>Submitted timestamp: {$_POST['timestamp']}</p>" : null; ?>
<p><?php echo date('Y-m-d H:i:s', strtotime($timestamp)); ?></p>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="bookmarks">
    <input type="submit">
    <input type="hidden" name="timestamp" value="<?php echo $timestamp; ?>">
</form>
<?php if (!empty($errors)) { ?>
<p>
    <?php foreach ($errors as $error) { ?>
        <span style="color: red; font-weight: bold"><?php echo $error; ?></span><br>
    <?php } ?>
</p>
<?php } ?>
<?php // functions!!
