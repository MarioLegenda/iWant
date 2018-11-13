<?php

namespace App\Web\Controller;

use Symfony\Component\HttpFoundation\Response;

class StaticController
{
    /**
     * @param \Twig_Environment $templating
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getIndex(\Twig_Environment $templating): Response
    {
        $content = $templating->render('application/static/index.html.twig');

        return new Response($content);
    }
    /**
     * @param \Twig_Environment $templating
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getSingleItem(\Twig_Environment $templating): Response
    {
        $content = $templating->render('application/static/single_item.html.twig');

        return new Response($content);
    }
    /**
     * @param \Twig_Environment $templating
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getSearch(\Twig_Environment $templating): Response
    {
        $content = $templating->render('application/static/search.html.twig');

        return new Response($content);
    }
}