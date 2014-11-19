<?php
namespace Kizilare\Template;

class Loader
{
    protected $twig;

    protected $base;

    protected $data;

    public function __construct()
    {
        $this->base = \Kizilare\Framework\App::getInstance()->getConfig( 'base' );
        $this->data['BASE'] = $this->base;

        \Twig_Autoloader::register();

        $loader = new \Twig_Loader_Filesystem( 'src' );
        $this->twig = new \Twig_Environment( $loader );

        $base_dir = dirname( $_SERVER['SCRIPT_FILENAME'] );
        $base_url = dirname( $_SERVER['SCRIPT_NAME'] );
        $action_url = $base_url;
        preg_match( "/(?P<file>\/\w+\.php)/", $_SERVER['REQUEST_URI'], $matches );
        if (!empty( $matches['file'] )) {
            $action_url .= $matches['file'];
        }

        $this->assign( 'BASE_PATH', $base_dir );
        $this->assign( 'BASE_URL', $base_url );
        $this->assign( 'ACTION_URL', $action_url );
    }

    /**
     * @param string $variable
     * @param mixed  $value
     */
    public function assign( $variable, $value )
    {
        $this->data[$variable] = $value;
    }

    /**
     * Load the template.
     *
     * @param string $view_file Template to load
     *
     * @return string
     */
    public function fetch( $view_file )
    {
        return $this->twig->render( $view_file . '.twig', $this->data );
    }
}