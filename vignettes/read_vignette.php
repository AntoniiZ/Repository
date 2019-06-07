<?php
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){

    require_once "../config.php";
    $sql = "SELECT * FROM Vignettes_data WHERE Id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = trim($_GET["id"]);

        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else{
                header("location: ../error.php");
                exit();
            }

        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }
    $sql = "SELECT Name FROM Categories WHERE CategoryId = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = trim($row["CategoryId"]);

        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $rowCategory = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else{
                header("location: ../error.php");
                exit();
            }

        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }

    $sql = "SELECT Name FROM Countries WHERE CountryId = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = trim($row["CountryId"]);

        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $rowCountry = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else{
                header("location: ../error.php");
                exit();
            }

        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
} else{
    header("location: ../error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1>View recorded vignettes</h1>
                </div>
                <div class="form-group">
                    <label>Regnum</label>
                    <p class="form-control-static"><?php echo $row["Regnum"]; ?></p>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <p class="form-control-static"><?php echo $rowCategory["Name"]; ?></p>
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <p class="form-control-static"><?php echo $rowCountry["Name"]; ?></p>
                </div>
                <div class="form-group">
                    <label>Valid from</label>
                    <p class="form-control-static"><?php echo $row["Valid_from"]; ?></p>
                </div>
                <div class="form-group">
                    <label>Valid to</label>
                    <p class="form-control-static"><?php echo $row["Valid_to"]; ?></p>
                </div>
                <p><a href="index.php" class="btn btn-primary">Back</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>