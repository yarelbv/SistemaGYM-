<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$rutina = $fecha = $partecuerpo = $repeticiones = $tiempo = "";
$rutina_err = $fecha_err = $partecuerpo_err = $repeticiones_err = $tiempo_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

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

    $input_tiempo = trim($_POST["tiempo"]);
    if(empty($input_tiempo)){
        $tiempo_err = "Please enter.";     
    } else{
        $tiempo = $input_tiempo;
    }

    

    // Check input errors before inserting in database
    if(empty($rutina_err) && empty($fecha_err) && empty($partecuerpo_err)&& empty($repeticiones_err)&& empty($tiempp_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO catalogoentrenador (rutina, fecha, partecuerpo, repeticiones, tiempo) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_rutina, $param_fecha, $param_partecuerpo, $param_repeticiones,  $param_tiempo);
            
            // Set parameters
            $param_rutina = $rutina;
            $param_fecha = $fecha;
            $param_partecuerpo = $partecuerpo;
            $param_repeticiones = $repeticiones;
            $param_tiempo = $tiempo;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 10 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Rutinas</h2>
                    <p>Presiona el botón para guardar la rutina</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nombre de Rutina: </label>
                            <input type="text" name="rutina" class="form-control <?php echo (!empty($rutina_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $rutina; ?>">
                            <span class="invalid-feedback"><?php echo $rutina_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Fecha: </label>
                            <input type="text" name="fecha" class="form-control <?php echo (!empty($fecha_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fecha; ?>">
                            <span class="invalid-feedback"><?php echo $fecha_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Parte del cuerpo: </label>
                            <input type="text" name="partecuerpo" class="form-control <?php echo (!empty($partecuerpo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $partecuerpo; ?>">
                            <span class="invalid-feedback"><?php echo $partecuerpo_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Repeticiones: </label>
                            <input type="text" name="repeticiones" class="form-control <?php echo (!empty($repeticiones_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $repeticiones; ?>">
                            <span class="invalid-feedback"><?php echo $repeticiones_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Tiempo: </label>
                            <input type="text" name="tiempo" class="form-control <?php echo (!empty($tiempo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tiempo; ?>">
                            <span class="invalid-feedback"><?php echo $tiempo_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>