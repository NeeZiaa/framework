<?php
namespace NeeZiaa\Twig;

use App\Models\Admin\SettingsModel;
use NeeZiaa\App;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig {

    private Environment $twig;

    public function __construct(?array $extensions = null)
    {

        $app = App::getInstance();

        $loader = new FilesystemLoader(dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views');

        if ($app->getSettings()->get('DEBUG')) {
            $options = [];
        }  else {
            $options = [
                'cache' => $app->getSettings()->get('CACHE_PATH')
            ];
        }

        $twig = new Environment($loader, $options);

        if(!is_null($extensions)) {
            foreach ($extensions['function'] as $fu){
                $name = '\NeeZiaa\Twig\Extensions\\'. ucfirst($fu) . 'Extension';
                foreach ((new $name())->getFunctions() as $v) {
                    $twig->addFunction($v);
                }
                $twig->addExtension(new $name());
            }
            foreach ($extensions['filters'] as $fi){
                $name = '\NeeZiaa\Twig\\'. ucfirst($fi) . 'Extension';
                $twig->addFunction(
                    (new $name())
                        ->getFilters()
                );
                $twig->addExtension(new $name());
            }
        }

        $this->twig = $twig;
    }

    public function render(string $filename, ?array $array_loader = NULL, ?array $twig = null): Environment
    {

        $twig_array = [];

        if (!is_null($array_loader)) {
            $twig_array = array_merge($twig_array, $array_loader);
        }

        echo $this->twig->render($filename . '.html.twig', $twig_array);

        return $this->twig;

    }

}