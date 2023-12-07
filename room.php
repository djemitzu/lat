<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "hotel");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// SQL query
$sql = "SELECT * FROM `rate`";
$result = $con->query($sql);
?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Room</title>
    <link rel="stylesheet" href="bootstrap-5.3.2-dist/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hedvig+Letters+Serif:opsz@12..24&family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>

<body>

    <?php
    include "library.php";
    if (!isset($_REQUEST["rid"])) {

    ?>
        <div class="container mx-auto">
            <h1 class="mt-3 text-center">Room</h1>
            <form action="room.php" method="post" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="rid" class="form-label">Room ID</label>
                    <input type="text" class="form-control" id="rid" name="rid" maxlength="6" />
                    <div id="error-rid" class="alert alert-danger d-none"></div>
                </div>

                <div class="mb-3">
  <label for="lt" class="form-label">Lantai</label>
  <select class="form-select" id="lt" name="lt">
    <?php
      for ($i = 1; $i <= 20; $i++) {
        if ($i != 4) {
          echo "<option value='$i'>Lantai $i</option>";
        }
      }
    ?>
  </select>
  <div id="error-lt" class="alert alert-danger d-none"></div>
</div>


                <div class="mb-3">
                    <label for="jnkm" class="form-label">Jenis Kamar</label>
                    <select class="form-select" aria-label="jnkm" name="jnkm" id="jnkm">
                        <option value="">Pilih Satu</option>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row["jenis_kamar"] . '">' . $row["jenis_kamar"] . '</option>';
                            }
                        } else {
                            echo "No results";
                        }
                        $con->close();
                        ?>
                    </select>
                    <div id="error-jnkm" class="alert alert-danger d-none"></div>
                </div>

                <div class="mb-3">
                    <label>Status Kamar</label><br />
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="stats" id="active" value="0">
                        <label class="form-check-label" for="active">Aktif</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="stats" id="non_active" value="1">
                        <label class="form-check-label" for="non_active">Non-Aktif</label>
                    </div>
                    <div id="error-stats" class="alert alert-danger d-none"></div>
                </div>

                <div class="mb-3">
                    <label for="ket" class="form-label">Keterangan</label>
                    <input type="text" class="form-control" id="ket" name="ket" />
                    <div id="error-ket" class="alert alert-danger d-none"></div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-light me-2 ">Submit</button>
                    <button type="reset" class="btn btn-light">Reset</button>
                </div>
            </form>
        </div>


        <script>
            function gagal() {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Pastikan semua field sudah terisi!",
                });
            }

            $(document).ready(function() {
                $('form').on('submit', function(e) {
                    var validations = [{
                            field: '#rid',
                            error: 'error-rid',
                            condition: $('#rid').val() == "" || $('#rid').val().length > 6,
                            message: 'Room ID belum diisi atau lebih dari 6 karakter'
                        },
                        {
                            field: '#lt',
                            error: 'error-lt',
                            condition: $('#lt').val() == "" || isNaN($('#lt').val()) || $('#lt').val() > 20,
                            message: 'Lantai belum diisi atau bukan angka atau lebih dari 20'
                        },
                        {
                            field: '#jnkm',
                            error: 'error-jnkm',
                            condition: $('#jnkm').val() == "",
                            message: 'Tolong pilih satu Jenis Kamar'
                        },
                        {
                            field: 'input[name="stats"]',
                            error: 'error-stats',
                            condition: !$('input[name="stats"]:checked').val(),
                            message: 'Harap pilih satu Status Kamar'
                        },
                        {
                            field: '#ket',
                            error: 'error-ket',
                            condition: $('#ket').val() == "",
                            message: 'Keterangan belum diisi'
                        }
                    ];

                    $('.alert').addClass('d-none');
                    $('.alert').text('');

                    validations.forEach(function(validation) {
                        if (validation.condition) {
                            $('#' + validation.error).removeClass('d-none');
                            $('#' + validation.error).text(validation.message);
                            e.preventDefault();
                        }
                    });

                    if (!e.isDefaultPrevented()) {
                        succes(e);
                    } else {
                        gagal();
                    }
                });
            });
        </script>

    <?php
    } else {


        echo '<div class="container col-md-6 mx-auto">';
        echo "<h1 class=\"mt-3 text-center\">Result</h1>";
        echo '</div><br><br>';

        $data = array(
            "room_id" => $_REQUEST["rid"],
            "lantai" => $_REQUEST["lt"],
            "jenis_kamar" => $_REQUEST["jnkm"],
            "status_kamar" => $_REQUEST["stats"],
            "keterangan" => $_REQUEST["ket"]
        );

        $sqlI = <<<EOD
INSERT INTO room(room_id, lantai, jenis_kamar, status_kamar, keterangan)
VALUES (:room_id, :lantai, :jenis_kamar, :status_kamar, :keterangan)
ON DUPLICATE KEY UPDATE
lantai=:lantai, jenis_kamar=:jenis_kamar, status_kamar=:status_kamar, keterangan=:keterangan;
EOD;

        $con = openConnection();
        createRow($con, $sqlI, $data);

        $sqlS = <<<EOD
SELECT room_id, lantai, jenis_kamar, status_kamar, keterangan FROM room Where room_id=:room_id;
EOD;

        $hasil = queryArrayValue1($con, $sqlS, array("room_id" => $_REQUEST["rid"]));

        if ($hasil) {
            echo '<div class="container col-md-6 mx-auto">';
            echo '<table class="table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col">Key</th>';
            echo '<th scope="col">Value</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($hasil as $key => $value) {
                echo '<tr>';
                echo '<td>' . ucfirst(str_replace("_", " ", $key)) . '</td>';

                if ($key == 'status_kamar') {
                    echo '<td>' . ($value == '0' ? 'Aktif' : 'Non-Aktif') . '</td>';
                } else {
                    echo '<td>' . $value . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '<script>$(document).ready(function() {
    succes();
  });

  function succes() {
    Swal.fire({
      icon: "success",
      title: "Data anda telah tersimpan!",
      showConfirmButton: false,
      timer: 1500
    });
  }</script>';
        } else {
            echo "Tidak ditemukan";
        }
    }
    ?>



    <script type="text/javascript" src="bootstrap-5.3.2-dist/js/bootstrap.min.js"></script>

</body>

</html>