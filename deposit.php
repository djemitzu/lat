<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Deposit</title>

    <link rel="stylesheet" href="bootstrap-5.3.2-dist/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hedvig+Letters+Serif:opsz@12..24&family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script>
        function validateForm() {
            var isValid = true;
            var groupid = document.getElementById('groupid').value;

            // Fetch data from the database to check if the groupid exists
            $.ajax({
                url: 'check_groupid.php',
                type: 'post',
                data: {
                    groupid: groupid
                },
                success: function(response) {
                    if (response == 0) {
                        $('#error-groupid').text('Group ID tidak valid');
                        isValid = false;
                    } else {
                        $('#error-groupid').text('');
                    }
                }
            });


            return isValid;
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
    if (!isset($_REQUEST["groupid"])) {
    ?>
        <div class="container mx-auto">
            <h1 class="mt-3 text-center">Deposit</h1>
            <form action="deposit.php" method="post" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="groupid" class="form-label">Group ID</label>
                    <input type="text" class="form-control" id="groupid" name="groupid" placeholder="Type to search...">
                    <div id="error-groupid" class="alert alert-danger d-none"></div>
                </div>

                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" />
                    <div id="error-tanggal" class="alert alert-danger d-none"></div>
                </div>

                <div class="mb-3">
                    <label for="dk" class="form-label">DK</label>
                    <input type="text" class="form-control" id="dk" name="dk" />
                    <div id="error-dk" class="alert alert-danger d-none"></div>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" />
                    <div id="error-amount" class="alert alert-danger d-none"></div>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" aria-label="type" id="type" name="type">
                        <option value="">Pilih Satu</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Cash">Cash</option>
                    </select>
                    <div id="error-type" class="alert alert-danger d-none"></div>
                </div>

                <div class="mb-3">
                    <label for="cardno" class="form-label">Card No</label>
                    <input type="text" class="form-control" id="cardno" name="cardno" />
                    <div id="error-cardno" class="alert alert-danger d-none"></div>
                </div>

                <div class="mb-3">
                    <label for="expired" class="form-label">Expired</label>
                    <input type="date" class="form-control" id="expired" name="expired" />
                    <div id="error-expired" class="alert alert-danger d-none"></div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-light me-2 ">Submit</button>
                    <button type="reset" class="btn btn-light">Reset</button>
                </div>
            </form>
        </div>


        <!-- <script src=""> -->
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
                            field: '#groupid',
                            error: 'error-groupid',
                            condition: $('#groupid').val() == "" || $('#groupid').val().length > 6,
                            message: 'Group ID belum diisi atau lebih dari 6 karakter'
                        },
                        {
                            field: '#tanggal',
                            error: 'error-tanggal',
                            condition: $('#tanggal').val() == "",
                            message: 'Tanggal belum diisi'
                        },
                        {
                            field: '#dk',
                            error: 'error-dk',
                            condition: $('#dk').val() == "",
                            message: 'DK belum diisi'
                        },
                        {
                            field: '#amount',
                            error: 'error-amount',
                            condition: $('#amount').val() == "" || isNaN($('#amount').val()),
                            message: 'Amount belum diisi atau bukan angka'
                        },
                        {
                            field: '#type',
                            error: 'error-type',
                            condition: $('#type').val() == "",
                            message: 'Tolong pilih satu tipe'
                        },
                        {
                            field: '#cardno',
                            error: 'error-cardno',
                            condition: $('#cardno').val() == "" || $('#cardno').val().length > 16,
                            message: 'Card No belum diisi atau lebih dari 16 karakter'
                        },
                        {
                            field: '#expired',
                            error: 'error-expired',
                            condition: $('#expired').val() == "",
                            message: 'Expired belum diisi'
                        },
                        {
                            field: '#expired',
                            error: 'error-expired',
                            condition:new Date($('#expired').val()) < new Date(),
                            message: 'Kartu tidak valid'
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
        $data["group_id"] = $_REQUEST["groupid"];
        $data["tanggal"] = $_REQUEST["tanggal"];
        $data["dk_id"] = $_REQUEST["dk"];
        $data["amount"] = $_REQUEST["amount"];
        $data["type"] = $_REQUEST["type"];
        $data["card_number"] = $_REQUEST["cardno"];
        $data["expired_date"] = $_REQUEST["expired"];

        $sqlI = <<<EOD
INSERT INTO deposit(group_id, tanggal, dk_id, amount, type, card_number, expired_date)
values (:group_id, :tanggal, :dk_id, :amount, :type, :card_number, :expired_date)
ON DUPLICATE KEY UPDATE
group_id=:group_id, tanggal=:tanggal, dk_id=:dk_id, amount=:amount, type=:type, card_number=:card_number, expired_date=:expired_date;
EOD;

        $con = openConnection();
        createRow($con, $sqlI, $data);

        $sqlS = <<<EOD
SELECT group_id, tanggal, dk_id, amount, type, card_number, expired_date FROM deposit Where group_id=:group_id;
EOD;

        $hasil = queryArrayValue1($con, $sqlS, array("group_id" => $_REQUEST["groupid"]));

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
                echo
                '<td>' . ($key === 'amount' ? formatUang($value) : $value) . '</td>';

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

</body>

</html>