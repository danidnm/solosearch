<?php

namespace SoloSearch\Core\Block;

use Psr\Container\ContainerInterface;
use Slim\Views\Twig;

class Template extends AbstractBlock
{
    protected Twig $twig;
    protected string $template = '';

    public function __construct(ContainerInterface $container, Twig $twig, string $name = '', array $data = [])
    {
        parent::__construct($container, $name, $data);
        $this->twig = $twig;
        if (isset($data['template'])) {
            $this->template = $data['template'];
        }
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;
        return $this;
    }

    public function toHtml(): string
    {
        if (empty($this->template)) {
            return '';
        }
        
        return $this->twig->fetch($this->template, [
            'block' => $this,
            'data' => $this->getData()
        ]);
    }
}
