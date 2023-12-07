<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AR</title>

  <link rel="stylesheet" href="bootstrap-5.3.2-dist/css/bootstrap.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hedvig+Letters+Serif:opsz@12..24&family=Roboto:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <script>
    function getDKData() {
      var dk_id = $("#dk_id").val();
      $.ajax({
        url: 'getdk.php',
        type: 'post',
        data: {
          dk_id: dk_id
        },
        dataType: 'json',
        success: function(response) {
          // Tampilkan data yang diterima dari getdk.php ke dalam form
          $("#group_id").val(response.group_id);
          $("#room_id").val(response.room_id);
          $("#tanggal").val(response.tanggal);
          $("#jenis").val(response.jenis);
          $("#keterangan").val(response.keterangan);
          $("#amount").val(response.amount);
        }
      });
    }
  </script>
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>

<body>
  <?php
  include "library.php";
  function formatUang($number, $matauang = true)
  {
    return ($matauang ? "Rp " : "") . number_format($number, 0, ",", ".");
  }
  if (!isset($_REQUEST["ar_id"])) {
  ?>
    <div class="container  mx-auto">
      <h1 class="mt-3 text-center">AR</h1>
      <form action="ar.php" method="post" onsubmit="return validateForm()">
        <div class="mb-3">
          <label for="ar_id" class="form-label">AR ID</label>
          <input type="text" class="form-control" id="ar_id" name="ar_id" />
          <div id="error-ar_id" class="alert alert-danger d-none"></div>
        </div>

        <div class="mb-3">
          <label for="tanggal" class="form-label">Tanggal</label>
          <input type="date" class="form-control" id="tanggal" name="tanggal" />
          <div id="error-tanggal" class="alert alert-danger d-none"></div>
        </div>

        <div class="mb-3">
          <label for="jenis" class="form-label">Jenis</label>
          <select class="form-select" aria-label="jenis" name="jenis">
            <option value="">Pilih Satu</option>
            <option value="Room">Room</option>
            <option value="Food&Beverages">Food & Beverages</option>
            <option value="Laundry">Laundry</option>
            <option value="Minibar">Minibar</option>
            <option value="Penalty">Penalty</option>
            <option value="Payment">Payment</option>
          </select>
          <div id="error-jenis" class="alert alert-danger d-none"></div>
        </div>

        <div class="mb-3">
          <label for="keterangan" class="form-label">Keterangan</label>
          <input type="text" class="form-control" id="keterangan" name="keterangan" />
          <div id="error-keterangan" class="alert alert-danger d-none"></div>
        </div>

        <div class="mb-3">
          <label for="amount" class="form-label">Amount</label>
          <input type="number" class="form-control" id="amount" name="amount" />
          <div id="error-amount" class="alert alert-danger d-none"></div>
        </div>

        <div class="mb-3">
          <label for="dk_id" class="form-label">DK ID</label>
          <input type="text" class="form-control" id="dk_id" name="dk_id" onchange="getDKData()" />
          <div id="error-dk_id" class="alert alert-danger d-none"></div>
        </div>

        <div class="mb-3">
          <label for="payment_id" class="form-label">Payment ID</label>
          <input type="text" class="form-control" id="payment_id" name="payment_id" />
          <div id="error-payment_id" class="alert alert-danger d-none"></div>
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
              field: '#ar_id',
              error: 'error-ar_id',
              condition: $('#ar_id').val() == "" || $('#ar_id').val().length > 6,
              message: 'AR ID belum diisi atau lebih dari 6 karakter'
            },
            {
              field: '#tanggal',
              error: 'error-tanggal',
              condition: $('#tanggal').val() == "",
              message: 'Tanggal belum diisi'
            },
            {
              field: '#jenis',
              error: 'error-jenis',
              condition: $('#jenis').val() == "",
              message: 'Tolong pilih salah satu jenis'
            },
            {
              field: '#keterangan',
              error: 'error-keterangan',
              condition: $('#keterangan').val() == "",
              message: 'Keterangan belum diisi'
            },
            {
              field: '#amount',
              error: 'error-amount',
              condition: $('#amount').val() == "" || isNaN($('#amount').val()),
              message: 'Amount belum diisi atau bukan angka'
            },
            {
              field: '#dk_id',
              error: 'error-dk_id',
              condition: $('#dk_id').val() == "" || $('#dk_id').val().length > 6,
              message: 'DK ID belum diisi atau lebih dari 6 karakter'
            },
            {
              field: '#payment_id',
              error: 'error-payment_id',
              condition: $('#payment_id').val() == "" || $('#payment_id').val().length > 6,
              message: 'Payment ID belum diisi atau lebih dari 6 karakter'
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
    echo '</br>';
    echo '</br>';

    $data = array();
    $data["ar_id"] = $_REQUEST["ar_id"];
    $data["tanggal"] = $_REQUEST["tanggal"];
    $data["jenis"] = $_REQUEST["jenis"];
    $data["keterangan"] = $_REQUEST["keterangan"];
    $data["amount"] = $_REQUEST["amount"];
    $data["dk_id"] = $_REQUEST["dk_id"];
    $data["payment_id"] = $_REQUEST["payment_id"];

    $sqlI = <<<EOD
INSERT INTO ar(ar_id, tanggal, jenis, keterangan, amount, dk_id, payment_id)
values (:ar_id, :tanggal, :jenis, :keterangan, :amount, :dk_id, :payment_id)
ON DUPLICATE KEY UPDATE
tanggal=:tanggal, jenis=:jenis, keterangan=:keterangan, amount=:amount, dk_id=:dk_id, payment_id=:payment_id;
EOD;

    $con = openConnection();
    createRow($con, $sqlI, $data);

    $sqlS = <<<EOD
SELECT ar_id, tanggal, jenis, keterangan, amount, dk_id, payment_id FROM ar Where ar_id=:ar_id;
EOD;

    $hasil = queryArrayValue1($con, $sqlS, array("ar_id" => $_REQUEST["ar_id"]));

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
        echo '<td>' . ($key === 'amount' ? formatUang($value) : $value) . '</td>';

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