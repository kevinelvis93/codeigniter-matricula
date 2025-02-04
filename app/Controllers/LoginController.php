<?php

namespace App\Controllers;

use App\Models\LoginModel;

class LoginController extends BaseController
{
    public function index()
    {
        $data['header'] = view('template/header', ['menu' => null]);
        $data['footer'] = view('template/footer');
        return view('login/index', $data);
    }

    public function autenticar()
    {
        // Obtener datos del formulario
        $usuario = $this->request->getPost('usuario');
        $password = $this->request->getPost('password');

        // Consultar el usuario usando el modelo
        $loginModel = new LoginModel();
        $user = $loginModel->buscarUsuario($usuario);

        // Validar usuario y contraseña
        if ($user && password_verify($password, $user->password)) {
            // Recuperar los roles desde el modelo
            $roles = $user->roles ?? []; // Asegúrate de que siempre sea un array

            // Guardar información en la sesión
            session()->set([
                'usuario_id' => $user->id,
                'nombre'     => $user->nombres . ' ' . $user->apellido_paterno,
                'roles'      => $roles, // Almacena los roles correctamente
                'isLoggedIn' => true,
            ]);

            // Redirigir al dashboard (plantilla.php)
            return redirect()->to('/plantilla');
        } else {
            // Si falla la autenticación, redirige al login con un mensaje de error
            return redirect()->back()->with('error', 'Credenciales inválidas. Inténtalo nuevamente.');
        }
    }

    public function logout()
    {
        // Destruir la sesión
        session()->destroy();
        return redirect()->to('/login');
    }
}
