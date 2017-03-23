<?php  
require '../vendor/autoload.php';  
require '../src/models/patients.php';  
require '../src/handlers/exceptions.php';

$config = include('../src/config.php');

date_default_timezone_set('America/New_York');

$app = new \Slim\App(['settings'=> $config]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$capsule->getContainer()->singleton(
  Illuminate\Contracts\Debug\ExceptionHandler::class,
  App\Exceptions\Handler::class
);

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->get('/v1/patients/', function($request, $response) {
  return $response->getBody()->write(Patients::all()->toJson());
});

$app->get('/v1/patients/{id}/', function($request, $response, $args) {
  $id = $args['id'];
  $model = Patients::find($id);
  $response->getBody()->write($model->toJson());
  return $response;
});

$app->post('/v1/patients/', function($request, $response, $args) {
  $data = $request->getParsedBody();
  $model = new Patients();
  $model->NAME_L = $data['NAME_L'];
  $model->NAME_F = $data['NAME_F'];
  $model->MRN = $data['MRN'];
  $model->DOB = $data['DOB'];
  $model->GENDER = $data['GENDER'];
  $model->PATIENT_EMAIL = $data['PATIENT_EMAIL'];
  $model->PATIENT_MOBILE = $data['PATIENT_MOBILE'];
  $model->PATIENT_PH = $data['PATIENT_PH'];

  $model->save();

  return $response->withStatus(201)->getBody()->write($model->toJson());
});

$app->delete('/v1/patients/{id}/', function($request, $response, $args) {
  $id = $args['id'];
  $model = Patients::find($id);
  $model->delete();

  return $response->withStatus(200);
});

$app->put('/v1/patients/{id}/', function($request, $response, $args) {
  $id = $args['id'];
  $data = $request->getParsedBody();
  $model = Patients::find($id);
  $model->NAME_L = $data['NAME_L'] ?: $model->NAME_L;
  $model->NAME_F = $data['NAME_F'] ?: $model->NAME_F;
  $model->MRN = $data['MRN'] ?: $model->MRN;
  $model->DOB = $data['DOB'] ?: $model->DOB;
  $model->GENDER = $data['GENDER'] ?: $model->GENDER;
  $model->PATIENT_EMAIL = $data['PATIENT_EMAIL'] ?: $model->PATIENT_EMAIL;
  $model->PATIENT_MOBILE = $data['PATIENT_MOBILE'] ?: $model->PATIENT_MOBILE;
  $model->PATIENT_PH = $data['PATIENT_PH'] ?: $model->PATIENT_PH;

  $model->save();

  return $response->getBody()->write($model->toJson());
});

$app->run();