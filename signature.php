<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
    <?php include 'header.php'; ?>
    <?php $UserRights = $_SESSION['UserRights']; ?>
    <?php
    $e = '';
    if (isset($_GET['e'])) {
        $e = $_GET['e'];
        $e = $e == 1 ? 'File uploaded' : ($e == 0 ? 'An error occurred while updating the signature.' : "File deleted");
    }
    $UserGUID = $_SESSION["UserGUID"];
    require_once("classes/spn_setting.php");
    require_once("classes/DBCreds.php");
    $DBCreds = new DBCreds();
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
    $mysqli->set_charset('utf8');
    $s = new spn_setting();
    $s->getsetting_info($_SESSION['SchoolID'], false);

    echo "<input hidden name='setting_rapnumber_1_val' id='setting_rapnumber_1_val' value='" . $s->_setting_rapnumber_1 . "'>";
    echo "<input hidden name='setting_rapnumber_2_val' id='setting_rapnumber_2_val' value='" . $s->_setting_rapnumber_2 . "'>";
    echo "<input hidden name='setting_rapnumber_3_val' id='setting_rapnumber_3_val' value='" . $s->_setting_rapnumber_3 . "'>";

    ?>
    <style>
        .custom-file-upload {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding: 6px 12px;
            background-color: #e9e9e9;
            color: black;
            border-radius: 4px;
        }

        .custom-file-upload:hover {
            background-color: #e0e0e0;
            cursor: pointer;
        }

        .custom-file-upload i {
            margin-right: 5px;
        }

        .custom-file-upload input[type=file] {
            position: absolute;
            font-size: 100px;
            opacity: 0;
            right: 0;
            top: 0;
        }

        #preview {
            width: 150px;
            height: 75px;
            /* Ajusta el tamaño de la vista previa según sea necesario */
            display: none;
            /* Inicialmente oculta la vista previa */
        }
    </style>
    <main id="main" role="main">
        <section>
            <div class="container container-fs">
                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 full-inset brd-bottom clearfix">
                        <h1 class="primary-color">HANDTEKENING</h1>
                        <?php include 'breadcrumb.php'; ?>
                    </div>
                    <div class="default-secondary-bg-color col-md-12 full-inset filter-bar brd-bottom clearfix">
                        <form action="ajax/upload_signature.php" method="post" class="form-inline" enctype="multipart/form-data" role="form" id="uploadForm">
                            <fieldset style="display: flex; align-items: center; gap: 20px;">
                                <div>
                                    <?php
                                    $signature = "";
                                    $get_signature = "SELECT signature FROM app_useraccounts WHERE UserGUID = '$UserGUID' AND signature IS NOT NULL LIMIT 1";
                                    $result = mysqli_query($mysqli, $get_signature);
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_array($result)) {
                                            $signature = $row["signature"];
                                        }
                                        // Generar un identificador único para evitar la caché
                                        $cache_buster = uniqid();
                                        if (file_exists("signatures/$signature")) {
                                            echo "<img width='150' height='75' src='signatures/$signature?$cache_buster'>";
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="form-group" style="cursor: pointer;">
                                    <label class="custom-file-upload" style="cursor: pointer;">
                                        <input style="cursor: pointer;" type="file" name="signature" id="file-upload" onchange="previewFile()" accept="image/*">
                                        <i style="cursor: pointer;" class="fas fa-cloud-upload-alt"></i>
                                        <span style="cursor: pointer;" id="file-name">Upload Signature</span>
                                        <img style="cursor: pointer;" style="margin-left: 5px;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAmklEQVR4nO3UMQrCQBBG4Q9yEfEE3sBKG7URBDvLnMfWI+RauYGIiBiUhQ2sIhKTQCz84cGwDG+HgV0GygjjvmRTHHHCrKtsgTPukQvWbWVbXBNZzQ27b2U5qjeymir2NM4BRaRMRGVyHnpapUiEoe6c4i/8vR3uE2GoOyfDJBLqYZJhhU1Llq/Tzz+826Y8fW3BHm7pbcLe8gAYKVC3DgVnQgAAAABJRU5ErkJggg==">
                                </div>
                                <img id="preview" src="#" alt="Image Preview">
                                <div class="form-group">
                                    <button class="btn btn-primary btn-m-w btn-m-h" onclick="validarArchivo(event)">Save in LVS</button>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-danger btn-m-w btn-m-h" id="delete">Delete</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12 full-inset">
                            <div class="sixth-bg-color brd-full">
                                <div class="box">
                                    <div class="box-content full-inset">
                                        <?php if ($e != "") { ?>
                                            <div style="margin-bottom: 20px;" class="full-inset alert alert-info reset-mrg-bottom" id="message_rapport">
                                                <p><i class="fa fa-info" style="margin-right: 5px;"></i><?php echo $e ?></p>
                                            </div>
                                        <?php } ?>
                                        <div class="full-inset alert alert-warning reset-mrg-bottom" id="message_rapport">
                                            <p><i class="fa fa-info" style="margin-right: 5px;"></i>Upload je handtekening.
                                                De gebruikte resolutie voor je handtekening is 800 × 400 pixel. De file grootte moet max 1 MB zijn.
                                                Je handteking wordt gebruikt om op de rapporten te komen.
                                                Je handteking is veilig bewaard en je kan altijd je handtekening verwijderen uit het systeem.</p>
                                        </div>
                                        <div class="full-inset alert alert-warning reset-mrg-bottom" style="margin-top: 20px;" id="message_rapport">
                                            <p><i class="fa fa-info" style="margin-right: 5px;"></i>Je kan hier een digitale handtekening maken en downloaden. Vervolgens kun je deze uploaden in de LVS. <a style="font-weight: bold;" href="https://signaturely.com/online-signature/draw/">https://signaturely.com/online-signature/draw/</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 full-inset">
                            <div id="loader_spn" class="hidden">
                                <div class="loader_spn"></div>
                            </div>
                        </div>
                    </div>

                </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('delete').addEventListener('click', function() {
            if (confirm('Are you sure you want to delete the signature?')) {
                window.location.href = 'ajax/upload_signature.php?delete';
            }
        });
    });

    function previewFile() {
        var fileInput = document.getElementById('file-upload');
        var file = fileInput.files[0];
        var img = document.getElementById('preview');
        var fileName = document.getElementById('file-name');

        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                img.style.display = 'inline-block';
            }
            reader.readAsDataURL(file);
            fileName.innerText = file.name; // Actualiza el texto del botón con el nombre del archivo
        } else {
            img.style.display = 'none';
            fileName.innerText = 'Upload File';
        }
    }

    function validarArchivo(event) {
        event.preventDefault();

        var fileInput = document.getElementById('file-upload');
        var archivo = fileInput.files[0];

        var fileSize = archivo.size;
        var maxSize = 1024 * 1025;
        if (fileSize > maxSize) {
            alert('The file size must be less than 1MB.');
            return false;
        }

        var img = new Image();
        img.src = window.URL.createObjectURL(archivo);
        img.onload = function() {
            var width = img.width;
            var height = img.height;
            if (width > 800 || height > 400) {
                alert('Image dimensions must be less than 800x400.');
                return false;
            }
            document.getElementById('uploadForm').submit();
        };
    }
</script>