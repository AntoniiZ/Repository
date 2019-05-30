<?php
require_once "config.php";

$categories = $countries = [];
$sql = "SELECT CategoryId, Name FROM Categories";
if ($stmt = mysqli_prepare($link, $sql)) {
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            array_push($categories, [$row['CategoryId'], $row['Name']]);
        }
    }
    mysqli_stmt_close($stmt);
}

$sql = "SELECT CountryId, Name FROM Countries";
if ($stmt = mysqli_prepare($link, $sql)) {
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            array_push($countries, [$row['CountryId'], $row['Name']]);
        }
    }
    mysqli_stmt_close($stmt);
}

$err = [];
$input_regnum = $input_valid_from = $input_valid_to = "";
$selected_countryid = $selected_categoryid = "disabled";

if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];
    $input_regnum = trim($_POST["regnum"]);
    $input_categoryid = trim($_POST["categoryid"]);
    $input_countryid = trim($_POST["countryid"]);

    if (empty($input_regnum) || !is_string($input_regnum)) {
        array_push($err, "Please enter a valid regnum.");
    }
    if($input_categoryid == "disabled" || $input_countryid == "disabled"){
        array_push($err, "Please select a category/country");
    }
    $selected_categoryid = $input_categoryid;
    $selected_countryid = $input_countryid;

    if(empty($err)){
        $sql = "UPDATE Vignettes_data SET Regnum=?, CategoryId=?, CountryId=? WHERE Id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "siii", $param_regnum, $param_categoryid, $param_countryid, $param_id);

            $param_regnum = $input_regnum;
            $param_categoryid = $selected_categoryid;
            $param_countryid = $selected_countryid;
            $param_id = $id;

            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }

        mysqli_close($link);
    }

    mysqli_close($link);
} else{
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){

        $id =  trim($_GET["id"]);

        $sql = "SELECT * FROM Vignettes_data WHERE Id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            $param_id = $id;

            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                } else{
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }


        mysqli_close($link);
    }  else{
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                <?php
                foreach ($err as $val) {
                    echo '<div class="alert alert-danger">' . $val . '</div><br>';
                }
                ?>

                <div class="page-header">
                    <h2>Update Record</h2>
                </div>

                <div class="form-group">
                    <label>Regnum</label>
                    <input type="text" name="regnum" class="form-control" value="<?php echo $input_regnum; ?>">
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="categoryid" class="form-control">
                        <?php
                        $t = "";
                        echo "<option value=$t disabled selected>" . "Choose a category" . "</option>";
                        foreach($categories as $category){
                            if($selected_categoryid == $category[0]) {
                                echo "<option value=$category[0] selected>" . $category[1] . "</option>";
                            } else {
                                echo "<option value=$category[0]>" . $category[1] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <select name="countryid" class="form-control">
                        <?php
                        $t = "";
                        echo "<option value=$t disabled selected>" . "Choose a country" . "</option>";
                        foreach($countries as $country){
                            if($selected_countryid == $country[0]) {
                                echo "<option value=$country[0] selected>" . $country[1] . "</option>";
                            } else {
                                echo "<option value=$country[0]>" . $country[1] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>


                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="index.php" class="btn btn-default">Cancel</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>