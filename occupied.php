<?php
    // Create connection
    $conn = mysqli_connect("localhost", "root", "", "hotel");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to get data from cities table
    $sql = "SELECT group_id FROM `group`";
    $result = mysqli_query($conn, $sql);

    // Check if there are any results
    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row["group_id"] . "'>";
        }
    }

    // Close connection
    mysqli_close($conn);
    ?>
    <!DOCTYPE html>
    <html lang="en" data-bs-theme="dark">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Occupied</title>

        <link rel="stylesheet" href="bootstrap-5.3.2-dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Times+New+Roman">
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script>
            function getRate(roomID) {
                var data = {
                    "room_id": roomID
                };

                fetch('ajaxocc.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then(response => response.json())
                    .then(data => {
                        var rate = data.rate;
                        document.getElementById("rate").value = rate;
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hedvig+Letters+Serif:opsz@12..24&family=Roboto:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
    </head>

    <body>


        <?php
        include "library.php";
        function formatUang($number, $matauang = true)
        {
            return ($matauang ? "Rp " : "") . number_format($number, 0, ",", ".");
        }
        if (!isset($_REQUEST["rid"]) && !isset($_REQUEST["gid"])) {
        ?>
            <div class="container mx-auto">
                <h1 class="mt-3 text-center">Occupied</h1>
                <form action="occupied.php" method="post" onsubmit="return validateForm()">
                    <div class="mb-3">
                        <label for="rid" class="form-label">Room ID</label>
                        <input type="text" class="form-control" id="rid" name="rid" onchange="getRate(this.value)" />
                        <div id="error-rid" class="alert alert-danger d-none"></div>
                    </div>

                    <div class="mb-3">
                        <label for="gid" class="form-label">Guest ID</label>
                        <input type="text" class="form-control" id="gid" name="gid" />
                        <div id="error-gid" class="alert alert-danger d-none"></div>
                    </div>

                    <div class="mb-3">
                        <label for="vid" class="form-label">Voucher ID</label>
                        <input type="text" class="form-control" id="vid" name="vid" />
                        <div id="error-vid" class="alert alert-danger d-none"></div>
                    </div>

                    <div class="mb-3">
                        <label for="dari" class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" id="dari" name="dari" />
                        <div id="error-dari" class="alert alert-danger d-none"></div>
                    </div>

                    <div class="mb-3">
                        <label for="sampai" class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="sampai" name="sampai" />
                        <div id="error-sampai" class="alert alert-danger d-none"></div>
                    </div>

                    <div class="mb-3">
                        <label for="ready" class="form-label">Ready Time</label>
                        <input type="datetime-local" class="form-control" id="ready" name="ready" />
                        <div id="error-ready" class="alert alert-danger d-none"></div>
                    </div>

                    <div class="mb-3">
                        <label for="checkin" class="form-label">Check-In Time</label>
                        <input type="datetime-local" class="form-control" id="checkin" name="checkin" />
                        <div id="error-checkin" class="alert alert-danger d-none"></div>
                    </div>

                    <div class="mb-3">
                        <label for="checkout" class="form-label">Check-Out Time</label>
                        <input type="datetime-local" class="form-control" id="checkout" name="checkout" />
                        <div id="error-checkout" class="alert alert-danger d-none"></div>
                    </div>

                    <div class="mb-3">
                        <label for="rate" class="form-label">Rate</label>
                        <!-- Tambahkan value yang sudah diformat ke dalam input -->
                        <input type="text" class="form-control" id="rate" name="rate" value="<?php echo isset($arr['rate']) ? formatUang($arr['rate']) : ''; ?>" />
                        <div id="error-rate" class="alert alert-danger d-none"></div>
                    </div>


                    <div class="mb-3">
                        <label for="groupid" class="form-label">Group ID</label>
                        <input class="form-control" list="datalistOptions" name="groupid" id="groupid" placeholder="Type to search...">
                        <datalist id="datalistOptions">
                            <?php
                            // Create connection
                            $conn = mysqli_connect("localhost", "root", "", "hotel");

                            // Check connection
                            if (!$conn) {
                                die("Connection failed: " . mysqli_connect_error());
                            }

                            // Query to get data from cities table
                            $sql = "SELECT group_id FROM `group`";
                            $result = mysqli_query($conn, $sql);

                            // Check if there are any results
                            if (mysqli_num_rows($result) > 0) {
                                // Output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row["group_id"] . "'>";
                                }
                            }

                            // Close connection
                            mysqli_close($conn);
                            ?>
                        </datalist>
                        <div id="error-groupid" class="alert alert-danger d-none"></div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-light me-2 ">Submit</button>
                        <button type="reset" class="btn btn-light">Reset</button>
                    </div>
                </form>
            </div>

            <script src="valocc.js"></script>
        <?php

        } else {
            echo '<div class="container col-md-6 mx-auto">';
            echo "<h1 class=\"mt-3 text-center\">Result</h1>";

            echo $header;
            echo '</div>';
    
            echo '<br>';
            echo '<br>';

            $data = array();
            $data["room_id"] = $_REQUEST["rid"];
            $data["guest_id"] = $_REQUEST["gid"];
            $data["voucher_id"] = $_REQUEST["vid"];
            $data["dari_tanggal"] = $_REQUEST["dari"];
            $data["sampai_tanggal"] = $_REQUEST["sampai"];
            $data["ready_time"] = $_REQUEST["ready"];
            $data["checkin_time"] = $_REQUEST["checkin"];
            $data["checkout_time"] = $_REQUEST["checkout"];
            $data["rate"] = $_REQUEST["rate"];
            $data["group_id"] = $_REQUEST["groupid"];

            $sqlI = <<<EOD
INSERT INTO occupied(room_id, guest_id, voucher_id, dari_tanggal, sampai_tanggal, ready_time, checkin_time, checkout_time, rate, group_id)
values (:room_id, :guest_id, :voucher_id, :dari_tanggal, :sampai_tanggal, :ready_time, :checkin_time, :checkout_time, :rate, :group_id)
ON DUPLICATE KEY UPDATE
voucher_id=:voucher_id, dari_tanggal=:dari_tanggal, sampai_tanggal=:sampai_tanggal, ready_time=:ready_time, checkin_time=:checkin_time, checkout_time=:checkout_time, rate=:rate, group_id=:group_id;
EOD;

            $con = openConnection();
            createRow($con, $sqlI, $data);

            $sqlS = <<<EOD
SELECT * FROM occupied Where room_id=:room_id;
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



        <script type="text/javascript" src="bootstrap-5.3.2-dist/js/bootstrap.min.js"></script>

    </body>

    </html>