<?php
require_once "../config.php";

$categories = $countries = [];
$selected_countryid = $selected_categoryid = "disabled";
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $input_regnum = trim($_POST["regnum"]);
    $input_categoryid = trim($_POST["categoryid"]);
    $input_countryid = trim($_POST["countryid"]);

    $from = new DateTime('now');

    $until = new DateTime($from->format('Y-m-d'));
    $until = $until->modify("+1 month");

    if (empty($input_regnum) || !is_string($input_regnum)) {
        array_push($err, "Please enter a valid regnum.");
    }
    if($input_categoryid == "disabled" || $input_countryid == "disabled"){
        array_push($err, "Please select a category/country");
    }
    $selected_categoryid = $input_categoryid;
    $selected_countryid = $input_countryid;
    if (empty($err)) {
        $sql = "INSERT INTO Vignettes_data (Regnum, CategoryId, CountryId, Valid_from, Valid_to) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "siiss", $input_regnum, $selected_categoryid, $selected_countryid, $from->format('Y-m-d'), $until->format('Y-m-d'));

            if (mysqli_stmt_execute($stmt)) {
                header("location: ../index.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($link);

    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper {
            width: 500px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
        }

        .alert-danger {
            margin-bottom: 0px;
        }

    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h2>Add a vignette</h2>
                </div>
                <?php
                    foreach ($err as $val) {
                        echo '<div class="alert alert-danger">' . $val . '</div><br>';
                    }
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

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
                                        echo "<option value=$category[0] " . "selected>" . $category[1] . "</option>";
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
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>