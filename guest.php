<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Guest</title>

  <link rel="stylesheet" href="bootstrap-5.3.2-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Times+New+Roman">
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
    return ($matauang ? "Rp. " : "") . number_format($number, 0, ",", ".");
  }
  if (!isset($_REQUEST["guestid"])) {
  ?>
    <div class="container mx-auto">
      <h1 class="mt-3 text-center">Guest</h1>
      <form action="guest.php" method="post" onsubmit="return validateForm()">
        <div class="mb-3">
          <label for="guestid" class="form-label">Guest ID</label>
          <input type="text" class="form-control" id="guestid" name="guestid" />
          <div id="error-guestid" class="alert alert-danger d-none"></div>
        </div>

        <div class="mb-3">
          <label for="nama" class="form-label">Nama</label>
          <input type="text" class="form-control" id="nama" name="nama" />
          <div id="error-nama" class="alert alert-danger d-none"></div>
        </div>

        <div class="mb-3">
          <label for="jenid" class="form-label">Jenis ID</label>
          <select class="form-select" id="jenid" name="jenid">
            <option value="">Pilih Salah Satu</option>
            <option value="KTP">KTP</option>
            <option value="Paspor">Paspor</option>
          </select>
          <div id="error-jenid" class="alert alert-danger d-none"></div>
        </div>

        <div class="mb-3">
          <label for="noid" class="form-label">Nomor ID</label>
          <input type="text" class="form-control" id="noid" name="noid" />
          <div id="error-noid" class="alert alert-danger d-none"></div>
        </div>

        <div class="mb-3">
          <label for="conno" class="form-label">Contact Number</label>
          <input type="text" class="form-control" id="conno" name="conno" />
          <div id="error-conno" class="alert alert-danger d-none"></div>
        </div>

        <div class="d-flex justify-content-center">
          <button type="submit" class="btn btn-light me-2 ">Submit</button>
          <button type="reset" class="btn btn-light">Reset</button>
        </div>
      </form>
    </div>

    <!-- <script src="val.js"></script> -->
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
            field: '#guestid',
            error: 'error-guestid',
            condition: $('#guestid').val() == "" || $('#guestid').val().length > 6,
            message: 'Guest ID belum diisi atau lebih dari 6 karakter'
          },
          {
            field: '#nama',
            error: 'error-nama',
            condition: $('#nama').val() == "",
            message: 'Nama belum diisi'
          },
          {
            field: '#jenid',
            error: 'error-jenid',
            condition: $('#jenid').val() == "",
            message: 'Tolong pilih salah satu Jenis'
          },
          {
            field: '#noid',
            error: 'error-noid',
            condition: $('#noid').val() == "" || $('#noid').val().length > 20,
            message: 'Nomor ID belum diisi atau lebih dari 20 karakter'
          },
          {
            field: '#conno',
            error: 'error-conno',
            condition: $('#conno').val() == "" || isNaN($('#conno').val()),
            message: 'Contact Number belum diisi atau bukan angka'
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
    $data["guest_id"] = $_REQUEST["guestid"];
    $data["nama"] = $_REQUEST["nama"];
    $data["jenis_id"] = $_REQUEST["jenid"];
    $data["nomor_id"] = $_REQUEST["noid"];
    $data["contact_number"] = $_REQUEST["conno"];

    $sqlI = <<<EOD
INSERT INTO guest(guest_id, nama, jenis_id, nomor_id, contact_number)
values (:guest_id, :nama, :jenis_id, :nomor_id, :contact_number)
ON DUPLICATE KEY UPDATE
nama=:nama, jenis_id=:jenis_id, nomor_id=:nomor_id, contact_number=:contact_number;
EOD;

    $con = openConnection();
    createRow($con, $sqlI, $data);

    $sqlS = <<<EOD
SELECT guest_id, nama, jenis_id, nomor_id, contact_number FROM guest Where guest_id=:guest_id;
EOD;

    $hasil = queryArrayValue1($con, $sqlS, array("guest_id" => $_REQUEST["guestid"]));

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
        echo '<td>' . $value . '</td>';
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