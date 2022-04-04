<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $tipo = $password = $confirm_password = $telefono = "";
$username_err = $tipo_err = $password_err = $confirm_password_err = $telefono_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                    $tipo = trim($_POST["tipo"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    }

    // Validate telefono
    if(empty(trim($_POST["telefono"]))){
        $telefono_err = "Escribe solo números.";
    } elseif(!preg_match('/^[0-9_]+$/', trim($_POST["telefono"]))){
        $telefono_err = "Escribe solo números.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE telefono= ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_telefono);
            
            // Set parameters
            $param_telefono = trim($_POST["telefono"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $telefono_err = "Número ya existente";
                } else{
                    $telefono = trim($_POST["tipo"]);
                }
            } else{
                echo "Repampanos! Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($tipo_err) && empty($password_err) && empty($confirm_password_err) && empty($telefono_err) ){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, tipo, password, telefono) VALUES (?, ?, ?, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_tipo, $param_password, $param_telefono);
            
            // Set parameters
            $param_username = $username;
            $param_tipo = $tipo;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_telefono = $telefono;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: index.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Zona de Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Registrarte</h2>
        <p>Por favor ingrese su informacón para crear una cuenta.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nombre de Usuario</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirme su Contraseña</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Telefono</label>
                <input type="text" name="telefono" class="form-control <?php echo (!empty($telefono_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $telefono; ?>">
                <span class="invalid-feedback"><?php echo $telefono; ?></span>
            </div>
            <divc class="form-group">
                <label>Tipo de usuario</label>
                <input type="text" name="tipo" class="form-control <?php echo (!empty($tipo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tipo; ?>">
                <span class="invalid-feedback"><?php echo $tipo_err; ?></span>
            </divc>
            
            <br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Crear Cuenta">
                <input type="reset" class="btn btn-secondary ml-2" value="Limpiar">
            </div>
            <p>¿Ya tienes una cuenta? <a href="login.php">Ingrese Aquí</a>.</p>
        </form>
    </div>    
</body>
</html>
