<?php
require_once __DIR__ . '/../config/App.php';


use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Core\Logger;
use App\Helpers\Security;
use Twig\TwigFunction;

class Controller
{
    protected Environment $twig;

    public function __construct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            if (!Security::validateCsrfToken($token)) {
                http_response_code(403);
                die('CSRF token validation failed.');
            }
        }

        $loader = new FilesystemLoader(BASE_PATH . '/App/Views');
        $this->twig = new Environment($loader, [
            'cache' => false, // disable caching for development
            'debug' => true
        ]);

        // Add CSRF helpers to Twig
        $this->twig->addFunction(new TwigFunction('csrf_token', [Security::class, 'getCsrfToken']));
        $this->twig->addFunction(new TwigFunction('csrf_field', [Security::class, 'csrfField'], ['is_safe' => ['html']]));
        $this->twig->addGlobal('session', $_SESSION);
    }

    protected function log(string $level, string $message, array $context = []): void
    {
        $level = strtolower($level);
        if (method_exists(Logger::class, $level)) {
            Logger::$level($message, $context);
        } else {
            Logger::info($message, $context);
        }
    }
    protected function render(string $view, array $data = []): void
    {
        $viewFile = $this->resolveViewPath($view);

        // Ensure session is up to date in Twig
        $this->twig->addGlobal('session', $_SESSION);

        if (str_ends_with($viewFile, '.twig')) {
            // Twig needs path relative to App/Views
            $relativeTwigPath = str_replace(BASE_PATH . '/App/Views/', '', $viewFile);
            $relativeTwigPath = str_replace('\\', '/', $relativeTwigPath); // Ensure forward slashes for Twig
            echo $this->twig->render($relativeTwigPath, $data);
        } elseif (is_file($viewFile)) {
            extract($data, EXTR_SKIP);
            require $viewFile;
        } else {
            throw new RuntimeException("View not found: {$view}");
        }
    }

    protected function renderWithLayout(string $view, array $data = []): void
    {
        $header = $this->resolveViewPath('layouts/header');
        $nav = $this->resolveViewPath('layouts/navbar'); // The file is navbar.twig/php
        $footer = $this->resolveViewPath('layouts/footer');

        extract($data, EXTR_SKIP);

        // Render header
        if (is_file($header)) {
            if (str_ends_with($header, '.twig')) {
                echo $this->twig->render('layouts/header.twig', $data);
            } else {
                require $header;
            }
        }

        // Render nav
        if (is_file($nav)) {
            if (str_ends_with($nav, '.twig')) {
                echo $this->twig->render('layouts/navbar.twig', $data);
            } else {
                require $nav;
            }
        }

        $this->render($view, $data);

        // Render footer
        if (is_file($footer)) {
            if (str_ends_with($footer, '.twig')) {
                echo $this->twig->render('layouts/footer.twig', $data);
            } else {
                require $footer;
            }
        }
    }

    protected function redirect(string $path, int $statusCode = 302): void
    {
        $path = '/' . ltrim($path, '/');
        $url = defined('BASE_URL') ? (BASE_URL . $path) : $path;
        header('Location: ' . $url, true, response_code: $statusCode);
        exit;
    }

    private function resolveViewPath(string $view): string
    {
        $relative = str_replace('.', '/', trim($view));
        $relative = ltrim($relative, '/');

        $basePath = BASE_PATH . '/App/Views/' . $relative;

        if (is_file($basePath . '.php')) {
            return $basePath . '.php';
        }

        if (is_file($basePath . '.twig')) {
            return $basePath . '.twig';
        }

        return $basePath . '.php';
    }
}
