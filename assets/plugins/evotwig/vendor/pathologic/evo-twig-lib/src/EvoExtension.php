<?php


namespace Pathologic\EvoTwig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

class EvoExtension extends AbstractExtension implements GlobalsInterface
{
    protected $modx;
    protected $params = [];

    public function __construct(\DocumentParser $modx, array $params = [])
    {
        $this->modx = $modx;
        $this->params = $params;
    }

    public function getGlobals(): array
    {
        return [
            'modx'           => $this->modx,
            'documentObject' => $this->modx->documentObject ?? [],
            'resource'       => $this->getResource(),
            'debug'          => $this->params['debug'],
            'config'         => $this->modx->config,
            'ajax'           => isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest',
            'plh'            => new class($this->modx) implements \ArrayAccess {
                private $modx;

                public function __construct(\DocumentParser $modx)
                {
                    $this->modx = $modx;
                }

                #[\ReturnTypeWillChange]
                public function offsetExists($offset)
                {
                    return isset($this->modx->placeholders[$offset]);
                }

                #[\ReturnTypeWillChange]
                public function offsetGet($offset)
                {
                    return $this->modx->getPlaceholder($offset);
                }

                #[\ReturnTypeWillChange]
                public function offsetSet($offset, $value)
                {
                }

                #[\ReturnTypeWillChange]
                public function offsetUnset($offset)
                {
                }

                public function toArray()
                {
                    return $this->modx->placeholders;
                }
            },
            '_GET'           => $_GET,
            '_POST'          => $_POST,
            '_REQUEST'       => $_REQUEST,
            '_COOKIE'        => $_COOKIE,
            '_SESSION'       => new class implements \ArrayAccess {
                #[\ReturnTypeWillChange]
                public function offsetExists($offset)
                {
                    return isset($_SESSION[$offset]);
                }

                #[\ReturnTypeWillChange]
                public function offsetGet($offset)
                {
                    return $_SESSION[$offset] ?? null;
                }

                #[\ReturnTypeWillChange]
                public function offsetSet($offset, $value)
                {
                }

                #[\ReturnTypeWillChange]
                public function offsetUnset($offset)
                {
                }

                public function toArray()
                {
                    return $_SESSION;
                }
            }
        ];
    }

    public function getFunctions(): array
    {
        $functions = [];
        $functions[] = new TwigFunction('makeUrl', [
            $this,
            'makeUrl'
        ]);
        $functions[] = new TwigFunction('runSnippet', [
            $this->modx,
            'runSnippet'
        ]);
        $functions[] = new TwigFunction('parseChunk', [
            $this->modx->tpl,
            'parseChunk'
        ]);
        $functions[] = new TwigFunction('getChunk', [
            $this->modx,
            'getChunk'
        ]);

        return $functions;
    }

    public function getFilters(): array
    {
        $filters = [];
        $filters[] = new TwigFilter('url', function($id, $absolute = false) {
            return $this->makeUrl($id, [], $absolute);
        });

        return $filters;
    }

    public function makeUrl($id, array $args = [], $absolute = false): string
    {
        return $this->modx->makeUrl($id, '', http_build_query($args), $absolute ? 'full' : '');
    }

    private function getResource()
    {
        $resource = [];
        if (!empty($this->modx->documentObject)) {
            foreach ($this->modx->documentObject as $key => $value) {
                $resource[$key] = is_array($value) ? $value[1] : $value;
            }
        }

        return $resource;
    }
}
