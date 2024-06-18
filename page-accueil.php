<?php

/**
 * The template for displaying home page.
 *
 * This is the template that displays our home page.
 * Please note that this is only for our WordPress construct home page
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 */

namespace App;

use Timber\Timber;

$context = Timber::context();

Timber::render('views/templates/home.twig', $context);