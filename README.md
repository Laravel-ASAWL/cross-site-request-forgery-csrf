# Laravel ASAWL - 03 Cross-Site Request Forgery (CSRF)

## Cross-Site Request Forgery (CSRF)

La Falsificación de Peticiones en Sitios Cruzados (CSRF, por sus siglas en inglés) es un tipo de ataque en el que un sitio web malicioso, correo electrónico o blog induce a un usuario a realizar acciones no deseadas en una aplicación web en la que el usuario ha iniciado sesión.

1.	El usuario inicia sesión: El usuario inicia sesión en una aplicación web legítima (por ejemplo, en la banca en línea).
2.	El atacante envía un enlace malicioso: El atacante envía al usuario un enlace malicioso a través de correo electrónico, redes sociales o cualquier otro medio. Este enlace puede estar oculto en una imagen o en un formulario invisible.
3.	El usuario hace clic en el enlace: Cuando el usuario hace clic en el enlace malicioso, se envía una solicitud a la aplicación web legítima sin que el usuario lo sepa. Esta solicitud puede realizar acciones no deseadas, como transferir dinero, cambiar la contraseña o publicar un mensaje.
4.	La aplicación ejecuta la solicitud: La aplicación web legítima, al no poder distinguir si la solicitud proviene del usuario o del atacante, ejecuta la acción solicitada.

### Directrices de Cross-Site Request Forgery (CSRF) en Laravel

Laravel proporciona un middleware integrado para proteger contra ataques CSRF:

```php
<!-- Vista vulnerable a CSRF -->
<form action="{{ route('comment') }}" method="POST" class="...">
    <div class="...">
        <label for="comment" class="...">Tu comentario</label>
        <textarea name="comment" id="comment" rows="6"
            class="..."
            placeholder="Escribe un comentario..."
            required>
        </textarea>
    </div>
    <button type="submit"
        class="...">
        Enviar comentario
    </button>
</form>
```

Middleware VerifyCsrfToken: Este middleware genera automáticamente un token [CSRF](https://laravel.com/docs/11.x/csrf) para cada sesión de usuario. Este token se incluye en todos los formularios y solicitudes AJAX de la aplicación. Globalmente, se puede aplicar el middleware a todas las rutas de la aplicación agregándolo al array $middleware en app/Http/Kernel.php, aunque es más seguro realizarlo de manera Individualmente, ya que se puede aplicar el middleware a una ruta utilizando el método middleware en los controladores o rutas, con eso se determina el acceso a ciertas rutas concretas y no de forma global.

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(...)
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'https://*.cloudworkstations.dev',
        ]);
    })
    ->withExceptions(...)
    ->create();
```

Validación del token: Cuando se envía una solicitud, Laravel verifica si el token [CSRF](https://laravel.com/docs/11.x/csrf) incluido en la solicitud coincide con el token almacenado en la sesión del usuario. Si los tokens no coinciden, la solicitud se rechaza.

```php
<!-- Vista vulnerable a CSRF -->
<form action="{{ route('comment') }}" method="POST" class="...">
    @csrf
    <div class="...">
        <label for="comment" class="...">Tu comentario</label>
        <textarea name="comment" id="comment" rows="6"
            class="..."
            placeholder="Escribe un comentario..."
            required>
        </textarea>
    </div>
    <button type="submit"
        class="...">
        Enviar comentario
    </button>
</form>
```

### Recomendaciones para prevenir la Cross-Site Request Forgery (CSRF) en Laravel

-	Nunca deshabilitar la protección CSRF a menos que haya una muy buena razón y se entienda y se asuman completamente los riesgos.
-	Siempre verificar el token en todas las solicitudes de CRUD de datos y asegurarse de que todas las solicitudes POST, PUT, PATCH y DELETE se verifique el token CSRF.
-	Nunca incluir el token CSRF en enlaces GET ya que los enlaces GET no deben modificar datos, por lo que no es necesario incluir el token CSRF en ellos.

### Mitigación de Cross-Site Request Forgery (CSRF) en Laravel

- Validar el token de seguridad.

Como se muestra en el controlador: [app/Http/Controllers/CommentController.php](./app/Http/Controllers/CommentController.php)

```php
// Validación del token de seguridad
if ($request->session()->token() == csrf_token())
{
    ...
}
```

- Inclusión del token de seguridad en el formulario.

Como se muestra en la vista: [resources/views/comments.blade.php](./resources/views/comments.blade.php)

```php
<form action="..." method="POST" class="...">
    <!-- Inclusión del token de seguridad en el formulario -->
    @csrf
    ...
</form>
```
