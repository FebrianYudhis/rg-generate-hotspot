<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Data Hotspot</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row align-items-center vh-100">
            <div class="col-10 mx-auto">
                <div class="card bg-light">
                    <h5 class="card-header">Generate Data Hotspot</h5>
                    <div class="card-body">
                        <form action="proses.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="file" class="form-label"><b>Silahkan Pilih File :</b></label>
                                <input class="form-control" type="file" id="file" name="file" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" id="format1" value="Lama">
                                <label class="form-check-label" for="format1">
                                    Format Lama
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" id="format2" value="Baru">
                                <label class="form-check-label" for="format2">
                                    Format Baru
                                </label>
                            </div>
                            <button type="submit" class="mt-3 btn btn-primary w-100">Proses</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>