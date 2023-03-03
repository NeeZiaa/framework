namespace NeeZiaa\Router\Utils\Controller;

use NeeZiaa\Router\Utils\Init;

class Name1Controller
{
    private array $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function index(): void
    {
        Init::render('name2');
    }
}
