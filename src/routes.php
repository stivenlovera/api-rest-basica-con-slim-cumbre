<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();
    $db = new mysqli('localhost', 'root', '', 'proyecto1');
////////////////////////////////
/////////////////LOGIN
///////////////////////////////
    $app->post('/api/login', function (Request $request, Response $response, array $args) use ($container,$db) {
        $result = array(
            'status' => 'error',
            'code'	 => 404,
            'message' => 'error revise sus datos'
        );
    
        $User= $request->getParam('Usuario');
        $Contraseña= $request->getParam('Contraseña');
        $passHash = password_hash($Contraseña, PASSWORD_BCRYPT);
        $sql = "SELECT funcionario.CIP FROM funcionario WHERE  funcionario.User='".$User."';";
        $query = $db->query($sql);
        $fila = mysqli_fetch_assoc($query);
        $respuesta= $fila['CIP'];

        $sql1 = "SELECT usuario.Pass FROM usuario WHERE usuario.CIF='".$respuesta."';";
        $query1 = $db->query($sql1);
        $ResPass = mysqli_fetch_assoc($query1);
        $Pass= $ResPass['Pass'];

        $desencriptado=password_verify($Contraseña, $Pass);
        if ($desencriptado) {
              $result = array(
                    'status' => 'success',
                    'code'	 => 200,
                    'message' => 'leggeado'
                );
        }

 
        echo json_encode($result);
    });
    //Mostrar
    $app->get('/api/persona', function (Request $request, Response $response, array $args)use($db) {

        $sql = 'SELECT * FROM persona  WHERE Estado="1" ORDER BY CI DESC ;';
        
        $query = $db->query($sql);
        $productos = array();
        while ($producto = $query->fetch_assoc()) {
            $productos[] = $producto;
        }
    
        $result = array(
            'status' => 'success',
            'code'	 => 200,
            'data' => $productos
        );
    
        echo json_encode($result);
    });
    //Mostrar una sola persona
    $app->get('/api/persona/{ID}', function (Request $request, Response $response, array $args) use($container,$db) {
    $CI=$request->getAttribute('ID');
    $sql = 'SELECT * FROM persona WHERE CI = '.$CI;
    $query = $db->query($sql);
    $result = array(
        'status' 	=> 'error',
        'code'		=> 404,
        'message' 	=> 'Persona no disponible'
    );

    if($query->num_rows == 1){
        $persona = $query->fetch_assoc();

        $result = array(
            'status' 	=> 'success',
            'code'		=> 200,
            'data' 	=> $persona
        );
    }

    echo json_encode($result);
    });
    ////////////////////////////////////
    ////////Registrar/////
    $app->post('/api/persona/registro', function (Request $request, Response $response, array $args)use($container,$db) {
        $result = array(
            'status' => 'error',
            'code'	 => 404,
            'message' => 'persona NO se ha creado'
        );
    
        $CI= $request->getParam('CI');
        $Nombres= $request->getParam('Nombres');
        $Telefono= $request->getParam('Telefono');
        $Dirrecion= $request->getParam('Dirrecion');
        $FechaN= $request->getParam('FechaN');
    
            $sql = "INSERT INTO Persona(CI,Nombres,Telefono,Dirrecion,FechaN,Estado) VALUES('".$CI."','".$Nombres."','".$Telefono."','".$Dirrecion."','".$FechaN."','1');";
    
            $insert = $db->query($sql);
    
            if($insert){
                $result = array(
                    'status' => 'success',
                    'code'	 => 200,
                    'message' => 'persona creado correctamente'
                );
            }
        
    
        echo json_encode($result);
        });
    //Añadir
    $app->post('/api/persona/nuevo', function (Request $request, Response $response, array $args)use($container,$db) {
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'persona NO se ha creado'
    );

    $CI= $request->getParam('CI');
    $Nombres= $request->getParam('Nombres');
    $Telefono= $request->getParam('Telefono');
    $Dirrecion= $request->getParam('Dirrecion');
    $FechaN= $request->getParam('FechaN');

        $sql = "INSERT INTO Persona(CI,Nombres,Telefono,Dirrecion,FechaN,Estado) VALUES('".$CI."','".$Nombres."','".$Telefono."','".$Dirrecion."','".$FechaN."','1');";

        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'persona creado correctamente'
            );
        }
    

    echo json_encode($result);
    });
    //Modificar segun CI
    $app->put('/api/persona/modificar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'persona NO se ha modificado'
    );
    $ID=$request->getAttribute('ID');

    $Nombres= $request->getParam('Nombres');
    $Telefono= $request->getParam('Telefono');
    $Dirrecion= $request->getParam('Dirrecion');
    $FechaN= $request->getParam('FechaN');

    $sql = "UPDATE persona SET Nombres= '".$Nombres."',Telefono='".$Telefono."',Dirrecion='".$Dirrecion."',FechaN='".$Dirrecion."' WHERE CI='".$ID."';";

    $insert = $db->query($sql);

    if($insert){
        $result = array(
            'status' => 'success',
            'code'	 => 200,
            'message' => 'persona modificado correctamente'
        );
    }

    echo json_encode($result);
    });
    //Eliminar cambiando Estado
    $app->put('/api/persona/eliminar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'persona NO se ha eliminado'
    );
    $ID=$request->getAttribute('ID');
    $eliminar= $request->getParam('eliminar');
    if ($eliminar==="si") {
            
        $sql = "UPDATE persona SET Estado= '0' WHERE CI='".$ID."';";
    
        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'persona elimino correctamente'
            );
        }
    }
    echo json_encode($result);
    });
//////////////////////////////////
/////////funcionario
/////////////////////////////////
$app->get('/api/funcionario', function (Request $request, Response $response, array $args) use ($container,$db) {
    $sql = 'SELECT * FROM funcionario  ORDER BY CIP DESC;';
    $query = $db->query($sql);
    $productos = array();
    while ($producto = $query->fetch_assoc()) {
        $productos[] = $producto;
    }

    $result = array(
        'status' => 'success',
        'code'	 => 200,
        'data' => $productos
    );

    echo json_encode($result);
        // Sample log message
        //$container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        //return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
    //Mostrar una sola persona
    $app->get('/api/funcionario/{ID}', function (Request $request, Response $response, array $args) use($container,$db) {
    $CI=$request->getAttribute('ID');
    $sql = 'SELECT * FROM persona WHERE CI = '.$CI;
    $query = $db->query($sql);
    $result = array(
        'status' 	=> 'error',
        'code'		=> 404,
        'message' 	=> 'Persona no disponible'
    );

    if($query->num_rows == 1){
        $persona = $query->fetch_assoc();

        $result = array(
            'status' 	=> 'success',
            'code'		=> 200,
            'data' 	=> $persona
        );
    }

    echo json_encode($result);
    });
    //Añadir
    $app->post('/api/funcionario/nuevo', function (Request $request, Response $response, array $args)use($container,$db) {
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'Funcionario NO se ha creado'
    );

    $User= $request->getParam('User');
    $Profesion= $request->getParam('Profesion');
    $FechaI= $request->getParam('FechaI');
    $FechaF= $request->getParam('FechaF');
    $CIP= $request->getParam('CIP');
    $CV= $request->getParam('CV');
    $CodD= $request->getParam('CodD');

        $sql = "INSERT INTO Funcionario(User,Profesion,FechaI,FechaF,CV,CIP,CodD)  VALUES('".$User."','".$Profesion."','".$FechaI."','".$FechaF."','".$CV."','".$CIP."','".$CodD."');";

        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'Funcionario creado correctamente'
            );
        }
    

    echo json_encode($result);
    });
    //Modificar segun CI
    $app->put('/api/funcionario/modificar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'persona NO se ha modificado'
    );
    $ID=$request->getAttribute('ID');

    $Nombres= $request->getParam('Nombres');
    $Telefono= $request->getParam('Telefono');
    $Dirrecion= $request->getParam('Dirrecion');
    $FechaN= $request->getParam('FechaN');

    $sql = "UPDATE persona SET Nombres= '".$Nombres."',Telefono='".$Telefono."',Dirrecion='".$Dirrecion."',FechaN='".$Dirrecion."' WHERE CI='".$ID."';";

    $insert = $db->query($sql);

    if($insert){
        $result = array(
            'status' => 'success',
            'code'	 => 200,
            'message' => 'persona modificado correctamente'
        );
    }

    echo json_encode($result);
    });
    //Eliminar cambiando Estado
    $app->put('/api/funcionario/eliminar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'persona NO se ha eliminado'
    );
    $ID=$request->getAttribute('ID');
    $eliminar= $request->getParam('eliminar');
    if ($eliminar==="si") {
            
        $sql = "UPDATE persona SET Estado= '0' WHERE CI='".$ID."';";
    
        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'persona elimino correctamente'
            );
        }
    }
    echo json_encode($result);
    });
//////////////////////////////////
/////////user
/////////////////////////////////
$app->get('/api/user', function (Request $request, Response $response, array $args) use ($container,$db) {
    $sql = 'SELECT * FROM funcionario WHERE funcionario.Estado="1" ORDER BY CIP DESC;';
    $query = $db->query($sql);
    $productos = array();
    while ($producto = $query->fetch_assoc()) {
        $productos[] = $producto;
    }

    $result = array(
        'status' => 'success',
        'code'	 => 200,
        'data' => $productos
    );

    echo json_encode($result);

    });
    //Mostrar una sola persona
    $app->get('/api/user/{ID}', function (Request $request, Response $response, array $args) use($container,$db) {
    $CI=$request->getAttribute('ID');
    $sql = 'SELECT * FROM persona WHERE CI = '.$CI;
    $query = $db->query($sql);
    $result = array(
        'status' 	=> 'error',
        'code'		=> 404,
        'message' 	=> 'Persona no disponible'
    );

    if($query->num_rows == 1){
        $persona = $query->fetch_assoc();

        $result = array(
            'status' 	=> 'success',
            'code'		=> 200,
            'data' 	=> $persona
        );
    }

    echo json_encode($result);
    });
    //Añadir
    $app->post('/api/user/nuevo', function (Request $request, Response $response, array $args)use($container,$db) {
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'User NO se ha creado'
    );

    $Pass= $request->getParam('Pass');
    $passHash = password_hash($Pass, PASSWORD_BCRYPT);
    $CIF= $request->getParam('CIF');

        $sql = "INSERT INTO Usuario(Pass,Estado,CIF) VALUES('".$passHash."','1','".$CIF."');";

        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'usuario creado correctamente'
            );
        }
    

    echo json_encode($result);
    });
    //Modificar segun CI
    $app->put('/api/user/modificar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'persona NO se ha modificado'
    );
    $ID=$request->getAttribute('ID');

    $Nombres= $request->getParam('Nombres');
    $Telefono= $request->getParam('Telefono');
    $Dirrecion= $request->getParam('Dirrecion');
    $FechaN= $request->getParam('FechaN');

    $sql = "UPDATE persona SET Nombres= '".$Nombres."',Telefono='".$Telefono."',Dirrecion='".$Dirrecion."',FechaN='".$Dirrecion."' WHERE CI='".$ID."';";

    $insert = $db->query($sql);

    if($insert){
        $result = array(
            'status' => 'success',
            'code'	 => 200,
            'message' => 'persona modificado correctamente'
        );
    }

    echo json_encode($result);
    });
    //Eliminar cambiando Estado
    $app->put('/api/user/eliminar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'persona NO se ha eliminado'
    );
    $ID=$request->getAttribute('ID');
    $eliminar= $request->getParam('eliminar');
    if ($eliminar==="si") {
            
        $sql = "UPDATE persona SET Estado= '0' WHERE CI='".$ID."';";
    
        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'persona elimino correctamente'
            );
        }
    }
    echo json_encode($result);
    });

//////////////////////////////////
/////////apikey
/////////////////////////////////
$app->get('/api/apikeys', function (Request $request, Response $response, array $args) use ($container,$db) {
    $sql = 'SELECT * FROM apikeys WHERE apikeys.Estado="1"  ORDER BY CIP DESC;';
    $query = $db->query($sql);
    $productos = array();
    while ($producto = $query->fetch_assoc()) {
        $productos[] = $producto;
    }

    $result = array(
        'status' => 'success',
        'code'	 => 200,
        'data' => $productos
    );

    echo json_encode($result);
        // Sample log message
        //$container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        //return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
    //Mostrar una sola persona
    $app->get('/api/apikey/{ID}', function (Request $request, Response $response, array $args) use($container,$db) {
    $CI=$request->getAttribute('ID');
    $sql = 'SELECT * FROM persona WHERE CI = '.$CI;
    $query = $db->query($sql);
    $result = array(
        'status' 	=> 'error',
        'code'		=> 404,
        'message' 	=> 'Persona no disponible'
    );

    if($query->num_rows == 1){
        $persona = $query->fetch_assoc();

        $result = array(
            'status' 	=> 'success',
            'code'		=> 200,
            'data' 	=> $persona
        );
    }

    echo json_encode($result);
    });
    //Añadir
    $app->post('/api/apikey/nuevo', function (Request $request, Response $response, array $args)use($container,$db) {
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'ApiKey NO se ha creado'
    );

    $Api= $request->getParam('Api');
    $Fecha= $request->getParam('Fecha');
    $CIU= $request->getParam('CIU');

        $sql = "INSERT INTO ApiKey(Api,Fecha,Estado,CIU) VALUES('".$Api."','".$Fecha."','1','".$CIU."');";

        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'ApiKey creado correctamente'
            );
        }
    

    echo json_encode($result);
    });
    //Modificar segun CI
    $app->put('/api/apikey/modificar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'persona NO se ha modificado'
    );
    $ID=$request->getAttribute('ID');

    $Nombres= $request->getParam('Nombres');
    $Telefono= $request->getParam('Telefono');
    $Dirrecion= $request->getParam('Dirrecion');
    $FechaN= $request->getParam('FechaN');

    $sql = "UPDATE persona SET Nombres= '".$Nombres."',Telefono='".$Telefono."',Dirrecion='".$Dirrecion."',FechaN='".$Dirrecion."' WHERE CI='".$ID."';";

    $insert = $db->query($sql);

    if($insert){
        $result = array(
            'status' => 'success',
            'code'	 => 200,
            'message' => 'persona modificado correctamente'
        );
    }

    echo json_encode($result);
    });
    //Eliminar cambiando Estado
    $app->put('/api/apikey/eliminar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'persona NO se ha eliminado'
    );
    $ID=$request->getAttribute('ID');
    $eliminar= $request->getParam('eliminar');
    if ($eliminar==="si") {
            
        $sql = "UPDATE persona SET Estado= '0' WHERE CI='".$ID."';";
    
        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'persona elimino correctamente'
            );
        }
    }
    echo json_encode($result);
    });
    //////////////////////////////////
/////////Departamento
/////////////////////////////////
$app->get('/api/departamento', function (Request $request, Response $response, array $args) use ($container,$db) {
    $sql = 'SELECT * FROM Departamento WHERE Departamento.Estado="1"  ORDER BY Cod DESC;';
    $query = $db->query($sql);
    $productos = array();
    while ($producto = $query->fetch_assoc()) {
        $productos[] = $producto;
    }

    $result = array(
        'status' => 'success',
        'code'	 => 200,
        'data' => $productos
    );

    echo json_encode($result);
        // Sample log message
        //$container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        //return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
    //Mostrar una sola persona
    $app->get('/api/departamento/{ID}', function (Request $request, Response $response, array $args) use($container,$db) {
    $CI=$request->getAttribute('ID');
    $sql = 'SELECT * FROM Departamento WHERE Cod = '.$CI;
    $query = $db->query($sql);
    $result = array(
        'status' 	=> 'error',
        'code'		=> 404,
        'message' 	=> 'departamento no disponible'
    );

    if($query->num_rows == 1){
        $persona = $query->fetch_assoc();

        $result = array(
            'status' 	=> 'success',
            'code'		=> 200,
            'data' 	=> $persona
        );
    }

    echo json_encode($result);
    });
    //Añadir
    $app->post('/api/departamento/nuevo', function (Request $request, Response $response, array $args)use($container,$db) {
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'Departamento NO se ha creado'
    );
    $Nombre= $request->getParam('Nombres');
    $Dirrecion= $request->getParam('Dirrecion');
    $Fecha= $request->getParam('Fecha');

        $sql = "INSERT INTO Departamento(Nombres,Dirrecion,Fecha,Estado) VALUES('".$Nombre."','".$Dirrecion."','".$Fecha."','1');";

        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'Departamento creado correctamente'
            );
        }
    

    echo json_encode($result);
    });
    //Modificar segun CI
    $app->put('/api/departamento/modificar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'departamento NO se ha modificado'
    );
    $ID=$request->getAttribute('ID');

    $Nombre= $request->getParam('Nombres');
    $Dirrecion= $request->getParam('Dirrecion');
    $Fecha= $request->getParam('Fecha');

    $sql = "UPDATE Departamento SET Nombres= '".$Nombre."',Dirrecion='".$Dirrecion."',Fecha='".$Fecha."' WHERE Cod='".$ID."';";

    $insert = $db->query($sql);

    if($insert){
        $result = array(
            'status' => 'success',
            'code'	 => 200,
            'message' => 'departamento modificado correctamente'
        );
    }

    echo json_encode($result);
    });
    //Eliminar cambiando Estado
    $app->put('/api/departamento/eliminar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'departamento NO se ha eliminado'
    );
    $ID=$request->getAttribute('ID');
    $eliminar= $request->getParam('eliminar');
    if ($eliminar==="si") {
            
        $sql = "UPDATE Departamento SET Estado= '0' WHERE departamento.Cod='".$ID."';";
    
        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'departamento elimino correctamente'
            );
        }
    }
    echo json_encode($result);
    });
   //////////////////////////////////
/////////Proyecto
/////////////////////////////////
$app->get('/api/proyecto', function (Request $request, Response $response, array $args) use ($container,$db) {
    $sql = 'SELECT * FROM Proyecto WHERE Proyecto.Estado="1"  ORDER BY Cod DESC;';
    $query = $db->query($sql);
    $productos = array();
    while ($producto = $query->fetch_assoc()) {
        $productos[] = $producto;
    }

    $result = array(
        'status' => 'success',
        'code'	 => 200,
        'data' => $productos
    );

    echo json_encode($result);

    });
    //Mostrar una sola persona
    $app->get('/api/proyecto/{ID}', function (Request $request, Response $response, array $args) use($container,$db) {
    $CI=$request->getAttribute('ID');
    $sql = 'SELECT * FROM Proyecto WHERE Cod = '.$CI;
    $query = $db->query($sql);
    $result = array(
        'status' 	=> 'error',
        'code'		=> 404,
        'message' 	=> 'Proyecto no disponible'
    );

    if($query->num_rows == 1){
        $persona = $query->fetch_assoc();

        $result = array(
            'status' 	=> 'success',
            'code'		=> 200,
            'data' 	=> $persona
        );
    }

    echo json_encode($result);
    });
    //Añadir
    $app->post('/api/proyecto/nuevo', function (Request $request, Response $response, array $args)use($container,$db) {
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'proyecto NO se ha creado'
    );

    $Nombres= $request->getParam('Nombres');
    $FechaI= $request->getParam('FechaI');
    $FechaF= $request->getParam('FechaF');
    $Descripcion= $request->getParam('Descripcion');


        $sql = "INSERT INTO Proyecto(Nombres,FechaI,FechaF,Descripcion,Estado)VALUES('".$Nombres."','".$FechaI."','".$FechaF."','".$Descripcion."','1');";

        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'proyecto creado correctamente'
            );
        }
    

    echo json_encode($result);
    });
    //Modificar segun CI
    $app->put('/api/proyecto/modificar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'proyecto NO se ha modificado'
    );
    $ID=$request->getAttribute('ID');

    $Nombres= $request->getParam('Nombres');
    $FechaI= $request->getParam('FechaI');
    $FechaF= $request->getParam('FechaF');
    $Descripcion= $request->getParam('Descripcion');

    $sql = "UPDATE Proyecto SET Nombres= '".$Nombres."',FechaI='".$FechaI."',FechaF='".$FechaF."',Descripcion='".$Descripcion."' WHERE Cod='".$ID."';";

    $insert = $db->query($sql);

    if($insert){
        $result = array(
            'status' => 'success',
            'code'	 => 200,
            'message' => 'proyecto modificado correctamente'
        );
    }

    echo json_encode($result);
    });
    //Eliminar cambiando Estado
    $app->put('/api/proyecto/eliminar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'Proyecto NO se ha eliminado'
    );
    $ID=$request->getAttribute('ID');
    $eliminar= $request->getParam('eliminar');
    if ($eliminar==="si") {
            
        $sql = "UPDATE Proyecto SET Estado='0' WHERE Proyecto.Cod='".$ID."';";
    
        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'Proyecto elimino correctamente'
            );
        }
    }
    echo json_encode($result);
    });    

       //////////////////////////////////
/////////Planificacion
/////////////////////////////////
$app->get('/api/planificacion', function (Request $request, Response $response, array $args) use ($container,$db) {
    $sql = 'SELECT * FROM planificacion ORDER BY Cod DESC;';
    $query = $db->query($sql);
    $productos = array();
    while ($producto = $query->fetch_assoc()) {
        $productos[] = $producto;
    }

    $result = array(
        'status' => 'success',
        'code'	 => 200,
        'data' => $productos
    );

    echo json_encode($result);

    });
    //Mostrar una sola planificacion
    $app->get('/api/planificacion/{ID}', function (Request $request, Response $response, array $args) use($container,$db) {
    $CI=$request->getAttribute('ID');
    $sql = 'SELECT * FROM planificacion WHERE Cod = '.$CI;
    $query = $db->query($sql);
    $result = array(
        'status' 	=> 'error',
        'code'		=> 404,
        'message' 	=> 'planificacion no disponible'
    );

    if($query->num_rows == 1){
        $persona = $query->fetch_assoc();

        $result = array(
            'status' 	=> 'success',
            'code'		=> 200,
            'data' 	=> $persona
        );
    }

    echo json_encode($result);
    });
    //Añadir
    $app->post('/api/planificacion/nuevo', function (Request $request, Response $response, array $args)use($container,$db) {
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'planificacion NO se ha creado'
    );

    $Objetivo= $request->getParam('Objetivo');
    $CodP= $request->getParam('CodP');
  

        $sql = "INSERT INTO Planificacion(Objetivo,Estado,CodP)VALUES('".$Objetivo."','1','".$CodP."');";

        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'Planificacion creado correctamente'
            );
        }
    

    echo json_encode($result);
    });
    //Modificar segun CI
    $app->put('/api/planificacion/modificar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'Planificacion NO se ha modificado'
    );
    $ID=$request->getAttribute('ID');

    $Objetivo= $request->getParam('Objetivo');
    $CodP= $request->getParam('CodP');

    $sql = "UPDATE Planificacion SET Objetivo= '".$Objetivo."',CodP='".$CodP."' WHERE Cod='".$ID."';";

    $insert = $db->query($sql);

    if($insert){
        $result = array(
            'status' => 'success',
            'code'	 => 200,
            'message' => 'Planificacion modificado correctamente'
        );
    }

    echo json_encode($result);
    });
    //Eliminar cambiando Estado
    $app->put('/api/planificacion/eliminar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'proyecto NO se ha eliminado'
    );
    $ID=$request->getAttribute('ID');
    $eliminar= $request->getParam('eliminar');
    if ($eliminar==="si") {
            
        $sql = "UPDATE planificacion SET Estado='0' WHERE planificacion.Cod='".$ID."';";
    
        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'proyecto elimino correctamente'
            );
        }
    }
    echo json_encode($result);
    });    
     //////////////////////////////////
/////////Actividad
/////////////////////////////////
$app->get('/api/actividad', function (Request $request, Response $response, array $args) use ($container,$db) {
    $sql = 'SELECT * FROM `actividad`';
    $query = $db->query($sql);
    $productos = array();
    while ($producto = $query->fetch_assoc()) {
        $productos[] = $producto;
    }

    $result = array(
        'status' => 'success',
        'code'	 => 200,
        'data' => $productos
    );

    echo json_encode($result);

    });
    //Mostrar una sola actividad
    $app->get('/api/actividad/{ID}', function (Request $request, Response $response, array $args) use($container,$db) {
    $CI=$request->getAttribute('ID');
    $sql = 'SELECT * FROM actividad WHERE Cod = '.$CI;
    $query = $db->query($sql);
    $result = array(
        'status' 	=> 'error',
        'code'		=> 404,
        'message' 	=> 'actividad no disponible'
    );

    if($query->num_rows == 1){
        $persona = $query->fetch_assoc();

        $result = array(
            'status' 	=> 'success',
            'code'		=> 200,
            'data' 	=> $persona
        );
    }

    echo json_encode($result);
    });
    //Añadir
    $app->post('/api/actividad/nuevo', function (Request $request, Response $response, array $args)use($container,$db) {
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'actividad NO se ha creado'
    );

    $Nombre= $request->getParam('Nombre');
    $FechaI= $request->getParam('FechaI');
    $FechaF= $request->getParam('FechaF');
    $CodP= $request->getParam('CodP');

        $sql = "INSERT INTO Actividad(Nombre,FechaI,FechaF,Estado,CodP) VALUES('".$Nombre."','".$FechaI."','".$FechaF."','1','".$CodP."');";

        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'actividad creado correctamente'
            );
        }
    

    echo json_encode($result);
    });
    //Modificar segun CI
    $app->put('/api/actividad/modificar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'actividad NO se ha modificado'
    );
    $ID=$request->getAttribute('ID');

    $Nombre= $request->getParam('Nombre');
    $FechaI= $request->getParam('FechaI');
    $FechaF= $request->getParam('FechaF');
    $CodP= $request->getParam('CodP');

    $sql = "UPDATE actividad SET Nombre= '".$Nombre."',FechaI='".$FechaI."',FechaF='".$FechaF."',CodP='".$CodP."' WHERE Cod='".$ID."';";

    $insert = $db->query($sql);

    if($insert){
        $result = array(
            'status' => 'success',
            'code'	 => 200,
            'message' => 'actividad modificado correctamente'
        );
    }

    echo json_encode($result);
    });
    //Eliminar cambiando Estado
    $app->put('/api/actividad/eliminar/{ID}', function (Request $request, Response $response, array $args) use($container,$db){
    $result = array(
        'status' => 'error',
        'code'	 => 404,
        'message' => 'actividad NO se ha eliminado'
    );
    $ID=$request->getAttribute('ID');
    $eliminar= $request->getParam('eliminar');
    if ($eliminar==="si") {
            
        $sql = "UPDATE actividad SET Estado='0' WHERE actividad.Cod='".$ID."';";
    
        $insert = $db->query($sql);

        if($insert){
            $result = array(
                'status' => 'success',
                'code'	 => 200,
                'message' => 'actividad elimino correctamente'
            );
        }
    }
    echo json_encode($result);
    });    

    $app->get('/api/reporte', function (Request $request, Response $response, array $args) use ($container,$db) {
        $sql = 'SELECT * FROM planificacion ORDER BY Cod DESC;';
        $query = $db->query($sql);
        $productos = array();
        while ($producto = $query->fetch_assoc()) {
            $productos[] = $producto;
        }
    
        $result = array(
            'status' => 'success',
            'code'	 => 200,
            'data' => $productos
        );
    
        echo json_encode($result);
    
        });
};
