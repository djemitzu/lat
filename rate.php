<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rate</title>

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
  function formatUang($number, $matauang = true)
  {
    return ($matauang ? "Rp " : "") . number_format($number, 0, ",", ".");
  }
  if (!isset($_REQUEST["jenkamar"])) {
  ?>
    <div class="container  mx-auto">
      <h1 class="mt-3 text-center">Rate</h1>
      <form action="rate.php" method="post">
        <div class="mb-3">
          <label for="jenkamar" class="form-label">Jenis Kamar</label>
          <input type="text" class="form-control" id="jenkamar" name="jenkamar" />
          <div id="error-jenkamar" class="alert alert-danger d-none"></div>
        </div>
        <div class="mb-3">
          <label for="harga" class="form-label">Rate</label>
          <input type="text" class="form-control" id="harga" name="harga" />
          <div id="error-harga" class="alert alert-danger d-none"></div>
        </div>
        <div class="d-flex justify-content-center">
          <button type="submit" class="btn btn-light me-2 ">Submit</button>
          <button type="reset" class="btn btn-light">Reset</button>
        </div>
      </form>
    </div>

    <!-- <script src="valrate.js"></script> -->
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
              field: '#jenkamar',
              error: 'error-jenkamar',
              condition: $('#jenkamar').val() == "" || !isNaN($('#jenkamar').val()) || $('#jenkamar').val().length < 3,
              message: 'Jenis kamar tidak boleh kosong, dan harus berupa teks'
            },
            {
              field: '#harga',
              error: 'error-harga',
              condition: $('#harga').val() == "" || isNaN($('#harga').val()) || $('#harga').val() <= 0,
              message: 'Harga tidak boleh kosong, dan harus berupa angka'
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

    echo '</div>';
    echo '<br>';
    echo '<br>';

    $data = array();
    $data["jenis_kamar"] = $_REQUEST["jenkamar"];
    $data["rate"] = $_REQUEST["harga"];

    $sqlI = <<<EOD
INSERT INTO rate(jenis_kamar, rate)
values (:jenis_kamar, :rate)
ON DUPLICATE KEY UPDATE
jenis_kamar=:jenis_kamar, rate=:rate;
EOD;

    $con = openConnection();
    createRow($con, $sqlI, $data);

    $sqlS = <<<EOD
SELECT jenis_kamar, rate FROM rate Where jenis_kamar=:jenis_kamar;
EOD;

    $hasil = queryArrayValue1($con, $sqlS, array("jenis_kamar" => $_REQUEST["jenkamar"]));

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
        echo '<td>' . ($key === 'rate' ? formatUang($value) : $value) . '</td>';

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


  <script type=" text/javascript" src="bootstrap-5.3.2-dist/js/bootstrap.min.js"></script>

</body>

</html>