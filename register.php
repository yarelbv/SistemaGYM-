<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $tipo = $telefono = "";
$username_err = $password_err = $confirm_password_err = $tipo_err = $telefono_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Ingresa tu nombre de usuario.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "El nombre de usuario puede contener letras, números y carácteres.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "Este nombre de usuario ya está en uso.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Recorcholis! Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }

            // Close statement
            $stmt->close();
        }
    }

     // Validate username
     if(empty(trim($_POST["tipo"]))){
        $tipo_err = "Selecciona tu tipo de usuario(general, estudiante).";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["tipo"]))){
        $tipo_err = "Tu tipo de usuario es general, estudiante.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE tipo = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_tipo);
            
            // Set parameters
            $param_tipo = trim($_POST["tipo"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $tipo_err = "Error al elegir el tipo de usuario";
                } else{
                    $tipo = trim($_POST["tipo"]);
                }
            } else{
                echo "Caracoles! Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }

            // Close statement
            $stmt->close();
        }
    }
    

    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Escribe la contraseña.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "La contraseña debe tener al menos 6 carácteres.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Confirma tu contraseña por favor";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Las contraseñas no coinciden";
        }
    }

    // Validate tipo
    if(empty(trim($_POST["tipo"]))){
        $tipo_err = "Selecciona tu tipo de usuario(general, estudiante).";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["tipo"]))){
        $tipo_err = "Tu tipo de usuario es general, estudiante.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE tipo = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_tipo);
            
            // Set parameters
            $param_tipo = trim($_POST["tipo"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $tipo_err = "Error al elegir el tipo de usuario";
                } else{
                    $tipo = trim($_POST["tipo"]);
                }
            } else{
                echo "Caracoles! Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }

            // Close statement
            $stmt->close();
        }
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
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, tipo, telefono) VALUES (?, ?, ?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssss", $param_username, $param_password, $param_tipo, $param_telefono);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_telefono = $telefono;
            $param_tipo = $tipo;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Registro</h2>
        <p>Completa los siguientes campos </p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirma la contraseña</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Tipo de Usuario</label>
                <input type="text" name="tipo" class="form-control <?php echo (!empty($tipo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tipo; ?>">
                <span class="invalid-feedback"><?php echo tipo_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Número telefónico</label>
                <input type="text" name="telefono" class="form-control <?php echo (!empty($telefono_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $telefono; ?>">
                <span class="invalid-feedback"><?php echo $telefono_err; ?></span>
            </div>    
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión </a></p>
        </form>
    </div>    
</body>
</html>