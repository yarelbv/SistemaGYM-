<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$rutina = $fecha = $partecuerpo = $repeticiones = $tiempo = "";
$rutina_err = $fecha_err = $partecuerpo_err = $repeticiones_err = $tiempo_err = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    $input_rutina = trim($_POST["rutina"]);
    if(empty($input_rutina)){
        $rutina_err = "Please enter.";     
    } else{
        $rutina = $input_rutina;
    }

    $input_fecha = trim($_POST["fecha"]);
    if(empty($input_fecha)){
        $fecha_err = "Please enter.";     
    } else{
        $fecha = $input_fecha;
    }
    
    $input_partecuerpo = trim($_POST["partecuerpo"]);
    if(empty($input_partecuerpo)){
        $partecuerpo_err = "Please enter.";     
    } else{
        $partecuerpo = $input_partecuerpo;
    }

    $input_repeticiones = trim($_POST["repeticiones"]);
    if(empty($input_repeticiones)){
        $repeticiones_err = "Please enter.";     
    } else{
        $repeticiones = $input_repeticiones;
    }

    $input_tiempo= trim($_POST["tiempo"]);
    if(empty($input_tiempo)){
        $tiempo_err = "Please enter.";     
    } else{
        $tiempo = $input_tiempo;
    }
    // Check input errors before inserting in database
    if(empty($rutina_err) && empty($fecha_err) && empty($partecuerpo_err)&& empty($repeticiones_err)&& empty($tiempo_err)){
        // Prepare an update statement
        $sql = "UPDATE catalogoentrenador SET rutina=?, fecha=?, partecuerpo=?, repeticiones=?, tiempo=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_rutina, $param_fecha, $param_partecuerpo, $param_repeticiones, $param_tiempo, $param_id);
            
            // Set parameters
            $param_rutina = $rutina;
            $param_fecha = $fecha;
            $param_partecuerpo = $partecuerpo;
            $param_repeticiones = $repeticiones;
            $param_tiempo = $tiempo;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM catalogoentrenador WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $id= $row["id"];
                    $rutina= $row["rutina"];
                    $fecha = $row["fecha"];
                    $partecuerpo = $row["partecuerpo"];
                    $repeticiones = $row["repeticiones"];
                    $tiempo= $row["tiempo"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editar Rutina</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the product record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Rutina</label>
                            <input type="text" name="rutina" class="form-control <?php echo (!empty($rutina_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $rutina; ?>">
                            <span class="invalid-feedback"><?php echo $rutina_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Fecha</label>
                            <input type="text" name="fecha" class="form-control <?php echo (!empty($fecha_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fecha; ?>">
                            <span class="invalid-feedback"><?php echo $fecha_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Parte del cuerpo</label>
                            <input type="text" name="partecuerpo" class="form-control <?php echo (!empty($partecuerpo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $partecuerpo; ?>">
                            <span class="invalid-feedback"><?php echo $partecuerpo_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Repeticiones</label>
                            <input type="text" name="repeticiones" class="form-control <?php echo (!empty($repeticiones_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $repeticiones; ?>">
                            <span class="invalid-feedback"><?php echo $repeticiones_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Tiempo</label>
                            <input type="text" name="tiempo" class="form-control <?php echo (!empty($tiempo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tiempo; ?>">
                            <span class="invalid-feedback"><?php echo $tiempo_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>