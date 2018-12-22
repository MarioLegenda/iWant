<?php

namespace App\Web\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    /**
     * @param string $genericCatchAll
     * @param \Twig_Environment $templating
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getGenericCatchAll(string $genericCatchAll, \Twig_Environment $templating): Response
    {
        $valids = [
            'guide',
            'progress',
            'features',
            'for-you',
        ];

        if (!in_array($genericCatchAll, $valids)) {
            throw new NotFoundHttpException();
        }

        $content = $templating->render('application/static/simple_static.html.twig');

        return new Response($content);
    }
}